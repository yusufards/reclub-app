<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Sport;
use App\Models\Venue;
use App\Models\RoomParticipant;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Mail\RoomJoinRequest;
use App\Mail\RoomJoinConfirmed;

class RoomController extends Controller
{
    /**
     * Display a listing of active rooms with filters.
     */
    public function index(Request $request)
    {
        $rooms = $this->getFilteredRooms($request);
        $sports = Sport::all();
        $cities = Venue::distinct()->pluck('city');
        $stat_interaksi = $this->getUserInteractionStats();

        return view('rooms.index', compact('rooms', 'sports', 'cities', 'stat_interaksi'));
    }

    /**
     * Show the form for creating a new room.
     */
    public function create(Request $request)
    {
        $sports = Sport::all();
        $venues = Venue::all();
        $selectedVenueId = $request->get('venue_id');

        $initialData = $request->only([
            'title',
            'description',
            'start_datetime',
            'max_participants',
            'cost_per_person',
            'sport_id'
        ]);

        return view('rooms.create', compact('sports', 'venues', 'selectedVenueId', 'initialData'));
    }

    /**
     * Store a newly created room.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sport_id' => 'required|exists:sports,id',
            'venue_id' => 'required|exists:venues,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_datetime' => 'required|date|after:now',
            'max_participants' => 'required|integer|min:2',
            'total_cost' => 'required|numeric|min:0',
        ]);

        if (!$this->isAdmin() && $this->hasScheduleConflict($request->start_datetime)) {
            return back()->withInput()->withErrors([
                'start_datetime' => 'Anda sudah memiliki jadwal Room di waktu yang persis sama!'
            ]);
        }

        $costPerPerson = 0;
        if ($validated['total_cost'] > 0 && $validated['max_participants'] > 0) {
            $costPerPerson = ceil($validated['total_cost'] / $validated['max_participants']);
        }

        $room = DB::transaction(function () use ($validated, $costPerPerson) {
            $room = Room::create([
                'host_id' => Auth::id(),
                'sport_id' => $validated['sport_id'],
                'venue_id' => $validated['venue_id'],
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'start_datetime' => $validated['start_datetime'],
                'max_participants' => $validated['max_participants'],
                'cost_per_person' => $costPerPerson,
                'is_active' => true,
                'code' => strtoupper(Str::random(6)),
            ]);

            if (!$this->isAdmin()) {
                RoomParticipant::create([
                    'room_id' => $room->id,
                    'user_id' => Auth::id(),
                    'status' => 'confirmed',
                    'responded_at' => now(),
                ]);
            }

            ActivityLog::create([
                'actor_id' => Auth::id(),
                'action' => 'room_created',
                'subject_type' => Room::class,
                'subject_id' => $room->id,
                'description' => 'Membuat room baru: ' . $room->title
            ]);

            return $room;
        });

        if ($this->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('success', 'Room berhasil dibuat oleh Admin.');
        }

        return redirect()->route('rooms.show', $room)->with('success', 'Room berhasil dibuat & Anda otomatis bergabung!');
    }

    /**
     * Display the specified room.
     */
    public function show(Room $room)
    {
        // Load data dengan filter agar yang rejected tidak ikut terambil di view
        $room->load([
            'host',
            'venue',
            'sport',
            'participants' => function ($q) {
                $q->whereIn('status', ['confirmed', 'pending']);
            },
            'participants.user'
        ]);

        $isParticipant = false;
        if (Auth::check()) {
            // Cek manual karena participants di atas sudah difilter
            $isParticipant = RoomParticipant::where('room_id', $room->id)
                ->where('user_id', Auth::id())
                ->whereIn('status', ['confirmed', 'pending'])
                ->exists();
        }

        return view('rooms.show', compact('room', 'isParticipant'));
    }

    /**
     * Show form for editing.
     */
    public function edit(Room $room)
    {
        $this->authorizeRoomAccess($room);
        $sports = Sport::all();
        $venues = Venue::all();
        $room->total_cost = $room->cost_per_person * $room->max_participants;

        return view('rooms.edit', compact('room', 'sports', 'venues'));
    }

    /**
     * Update the specified room.
     */
    public function update(Request $request, Room $room)
    {
        $this->authorizeRoomAccess($room);

        $validated = $request->validate([
            'sport_id' => 'required|exists:sports,id',
            'venue_id' => 'required|exists:venues,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_datetime' => 'required|date',
            'max_participants' => 'required|integer|min:2',
            'total_cost' => 'required|numeric|min:0',
        ]);

        $costPerPerson = 0;
        if ($validated['total_cost'] > 0 && $validated['max_participants'] > 0) {
            $costPerPerson = ceil($validated['total_cost'] / $validated['max_participants']);
        }

        $room->update([
            'sport_id' => $validated['sport_id'],
            'venue_id' => $validated['venue_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'start_datetime' => $validated['start_datetime'],
            'max_participants' => $validated['max_participants'],
            'cost_per_person' => $costPerPerson,
        ]);

        return redirect()->route('rooms.show', $room)->with('success', 'Room berhasil diperbarui!');
    }

    /**
     * Remove the specified room.
     */
    public function destroy(Room $room)
    {
        $this->authorizeRoomAccess($room);
        $room->delete();
        $route = $this->isAdmin() ? 'admin.dashboard' : 'dashboard';
        return redirect()->route($route)->with('success', 'Room berhasil dihapus.');
    }

    /**
     * Join a room (Logic Updated for Re-Join).
     */
    public function join(Room $room)
    {
        if ($this->isAdmin()) {
            return back()->with('error', 'Admin tidak bisa join sebagai peserta.');
        }

        // Cek Kuota (Hanya hitung yang confirmed)
        if ($room->participants()->where('status', 'confirmed')->count() >= $room->max_participants) {
            return back()->with('error', 'Maaf, kuota room ini sudah penuh.');
        }

        // Cek Apakah User Sudah Pernah Join?
        $existingParticipant = $room->participants()->where('user_id', Auth::id())->first();
        $participant = null;

        if ($existingParticipant) {
            // A. Jika statusnya REJECTED, kita izinkan masuk lagi (Update jadi Pending)
            if ($existingParticipant->status == 'rejected') {
                $existingParticipant->update([
                    'status' => 'pending',
                    'responded_at' => null, // Reset respon host
                    'created_at' => now(),  // Refresh waktu agar naik ke atas di list host
                ]);
                $participant = $existingParticipant;
            }
            // B. Jika status Pending atau Confirmed, tolak request
            else {
                return back()->with('info', 'Anda sudah terdaftar atau menunggu konfirmasi di room ini.');
            }
        } else {
            // C. Jika belum pernah join, Buat Baru
            $participant = RoomParticipant::create([
                'room_id' => $room->id,
                'user_id' => Auth::id(),
                'status' => 'pending',
                'responded_at' => null,
            ]);
        }

        // Kirim Notifikasi Email ke Host
        if ($participant && $room->host->email) {
            try {
                Mail::to($room->host->email)->send(new RoomJoinRequest($participant));
            } catch (\Exception $e) {
                Log::error('Gagal kirim email join: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Permintaan bergabung berhasil dikirim! Menunggu konfirmasi Host.');
    }

    /**
     * Leave a room.
     */
    public function leave(Room $room)
    {
        if ($room->host_id === Auth::id()) {
            return back()->with('error', 'Host tidak bisa keluar dari room. Hapus room jika ingin membatalkan.');
        }

        $participant = RoomParticipant::where('room_id', $room->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($participant) {
            $participant->delete();
            return redirect()->route('home')->with('success', 'Anda telah keluar dari Room.');
        }

        return back()->with('error', 'Anda belum bergabung di room ini.');
    }

    /**
     * Join a room by code.
     */
    public function joinByCode(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        if ($this->isAdmin()) {
            return back()->with('error', 'Admin hanya bisa memantau, tidak bisa join room.');
        }

        $room = Room::where('code', strtoupper($request->code))
            ->where('is_active', true)
            ->where('start_datetime', '>=', now()->subHours(2))
            ->first();

        if (!$room) {
            return back()->with('error', 'Kode Room tidak valid atau Room sudah kadaluarsa.');
        }

        $isParticipant = $room->participants()->where('user_id', Auth::id())->exists();
        if ($isParticipant) {
            return redirect()->route('rooms.show', $room)->with('info', 'Anda sudah bergabung di room ini.');
        }

        return redirect()->route('rooms.show', $room)
            ->with('success', 'Room ditemukan! Silakan klik tombol "Gabung Room Ini Sekarang".');
    }

    /**
     * API Polling Check Status.
     */
    public function checkStatus($id)
    {
        $exists = Room::where('id', $id)->exists();

        if (!$exists) {
            session()->flash('error', 'Room tersebut telah dihapus oleh Host atau sudah tidak tersedia.');
            return response()->json(['status' => 'deleted']);
        }

        return response()->json(['status' => 'active']);
    }

    /**
     * Host Menerima Peserta (Confirm) - MANUAL ID LOOKUP
     */
    public function confirm(Request $request, $roomId, $participantId)
    {
        // 1. Cari Data Manual
        $room = Room::find($roomId);
        $participant = RoomParticipant::find($participantId);

        if (!$room || !$participant)
            return back()->with('error', 'Data tidak ditemukan.');

        // 2. Validasi Host
        if (Auth::id() !== $room->host_id)
            return back()->with('error', 'Hanya Host yang berhak menerima peserta.');

        // 3. Validasi Relasi
        if ($participant->room_id != $room->id)
            return back()->with('error', 'Data peserta tidak valid.');

        // 4. Cek Kuota
        $currentCount = $room->participants()->where('status', 'confirmed')->count();
        if ($currentCount >= $room->max_participants)
            return back()->with('error', 'Gagal menerima. Slot penuh!');

        // 5. Update Status
        $participant->update(['status' => 'confirmed', 'responded_at' => now()]);

        // 6. Kirim Notifikasi
        $this->notifyParticipant($participant, 'accepted');

        return back()->with('success', 'Peserta berhasil diterima!');
    }

    /**
     * Host Menolak Peserta (Reject) - MANUAL ID LOOKUP
     */
    public function reject(Request $request, $roomId, $participantId)
    {
        // 1. Cari Data Manual
        $room = Room::find($roomId);
        $participant = RoomParticipant::find($participantId);

        if (!$room || !$participant)
            return back()->with('error', 'Data tidak ditemukan.');
        if (Auth::id() !== $room->host_id)
            return back()->with('error', 'Hanya Host yang berhak menolak.');

        // 2. Update Status
        $participant->update(['status' => 'rejected', 'responded_at' => now()]);

        // 3. Kirim Notifikasi
        $this->notifyParticipant($participant, 'rejected');

        return back()->with('success', 'Permintaan bergabung telah ditolak.');
    }

    // ==========================================================
    //           MAGIC LINK ACTIONS (UNTUK EMAIL)
    // ==========================================================

    public function confirmFromEmail($participantId)
    {
        $participant = RoomParticipant::with(['room', 'user'])->find($participantId);
        if (!$participant)
            abort(404);

        if ($participant->status === 'confirmed')
            return $this->magicLinkResponse('success', 'Sudah Dikonfirmasi', 'Peserta ini sudah Anda terima sebelumnya.', $participant);

        if ($participant->room->participants()->where('status', 'confirmed')->count() >= $participant->room->max_participants) {
            return $this->magicLinkResponse('failed', 'Gagal Konfirmasi', 'Mohon maaf, slot room ini sudah penuh.', $participant);
        }

        $participant->update(['status' => 'confirmed', 'responded_at' => now()]);
        $this->notifyParticipant($participant, 'accepted');

        return $this->magicLinkResponse('success', 'Berhasil Diterima! 🎉', "Anda telah berhasil MENERIMA {$participant->user->name}. Notifikasi terkirim.", $participant);
    }

    public function rejectFromEmail($participantId)
    {
        $participant = RoomParticipant::with(['room', 'user'])->find($participantId);
        if (!$participant)
            abort(404);

        if ($participant->status === 'rejected')
            return $this->magicLinkResponse('failed', 'Sudah Ditolak', 'Peserta ini sudah Anda tolak sebelumnya.', $participant);

        $participant->update(['status' => 'rejected', 'responded_at' => now()]);
        $this->notifyParticipant($participant, 'rejected');

        return $this->magicLinkResponse('failed', 'Berhasil Ditolak', "Anda telah MENOLAK permintaan join dari {$participant->user->name}.", $participant);
    }

    private function magicLinkResponse($status, $title, $msg, $p)
    {
        return view('pages.confirmation_success', [
            'status' => $status,
            'title' => $title,
            'message' => $msg,
            'participant' => $p,
            'room_url' => route('rooms.show', $p->room->id)
        ]);
    }

    // ==========================================================
    //                        PRIVATE HELPERS
    // ==========================================================

    private function notifyParticipant(RoomParticipant $participant, $type = 'accepted')
    {
        $userPeserta = $participant->user;
        $room = $participant->room;

        if ($type == 'accepted') {
            if ($userPeserta->email) {
                try {
                    Mail::to($userPeserta->email)->send(new RoomJoinConfirmed($participant));
                } catch (\Exception $e) {
                    Log::error('Mail Error: ' . $e->getMessage());
                }
            }
            if ($userPeserta->phone) {
                $pesan = "Halo *{$userPeserta->name}*! 👋\n\nPermintaan join di room *{$room->title}* DITERIMA ✅.\n📅 " . $room->start_datetime->format('d M, H:i') . " WIB\n📍 " . ($room->venue->name ?? 'Venue') . "\nSampai jumpa!";
                $this->sendWhatsapp($userPeserta->phone, $pesan);
            }
        } else if ($type == 'rejected') {
            if ($userPeserta->phone) {
                $pesan = "Halo *{$userPeserta->name}*,\n\nMohon maaf, permintaan join ke *{$room->title}* ditolak oleh Host 🚫.\nSilakan cari room lain.";
                $this->sendWhatsapp($userPeserta->phone, $pesan);
            }
        }
    }

    private function sendWhatsapp($nomor, $pesan)
    {
        try {
            $nomor = preg_replace('/^0/', '62', $nomor);
            $nomor = preg_replace('/[^0-9]/', '', $nomor);
            $token = env('FONNTE_TOKEN', '');
            if (!$token)
                return;
            Http::withHeaders(['Authorization' => $token])->post('https://api.fonnte.com/send', [
                'target' => $nomor,
                'message' => $pesan,
                'countryCode' => '62',
            ]);
        } catch (\Exception $e) {
            Log::error('WA Error: ' . $e->getMessage());
        }
    }

    private function getFilteredRooms(Request $request)
    {
        $query = Room::query()
            ->where('is_active', true)
            ->where('start_datetime', '>=', now())
            ->with(['venue', 'sport', 'host'])

            // [FIX] EAGER LOAD hanya yang aktif (Confirmed/Pending)
            ->with([
                'participants' => function ($q) {
                    $q->whereIn('status', ['confirmed', 'pending']);
                }
            ])

            // [FIX UTAMA] HITUNG JUMLAH hanya yang Confirmed/Pending. (Rejected TIDAK DIHITUNG)
            ->withCount([
                'participants' => function ($q) {
                    $q->whereIn('status', ['confirmed', 'pending']);
                }
            ]);

        if ($request->filled('sport_id'))
            $query->where('sport_id', $request->sport_id);
        if ($request->filled('city'))
            $query->whereHas('venue', fn($q) => $q->where('city', $request->city));
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('venue', fn($v) => $v->where('name', 'like', "%{$search}%"));
            });
        }

        return $query->orderBy('start_datetime', 'asc')->paginate(12)->withQueryString();
    }

    private function getUserInteractionStats(): int
    {
        if (!Auth::check())
            return 0;
        // Hitung statistik interaksi (Host + Join), tapi yang statusnya aktif saja
        return Room::where('host_id', Auth::id())->count() +
            RoomParticipant::where('user_id', Auth::id())
                ->whereIn('status', ['confirmed', 'pending'])
                ->count();
    }

    private function hasScheduleConflict(string $startDatetime): bool
    {
        return Room::where('host_id', Auth::id())
            ->where('is_active', true)
            ->where('start_datetime', $startDatetime)
            ->exists();
    }

    private function authorizeRoomAccess(Room $room): void
    {
        if (!$this->isAdmin() && Auth::id() !== $room->host_id) {
            abort(403, 'Tindakan tidak diizinkan. Anda bukan Host room ini.');
        }
    }

    private function isAdmin(): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }
}