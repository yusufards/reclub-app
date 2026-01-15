<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomParticipant;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Mail\RoomJoinRequest;
use App\Mail\RoomJoinConfirmed;

class RoomParticipantController extends Controller
{
    // =================================================================
    // 1. PROSES USER MINTA JOIN (DARI TOMBOL GABUNG)
    // =================================================================
    public function join(Request $request, Room $room)
    {
        $user = Auth::user();

        // 1. Cek Login
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login untuk bergabung.');
        }

        // 2. Cek Waktu (Room Closed)
        if (now() >= $room->start_datetime) {
            return back()->with('error', 'Maaf, aktivitas ini sudah dimulai/berakhir. Room Closed!');
        }

        // 3. Cek Role Admin
        if ($user->role === 'admin') {
            return back()->with('error', 'Admin hanya memantau, tidak diperbolehkan ikut bermain.');
        }

        // 4. Cek Host
        if ($room->host_id === $user->id) {
            return back()->with('info', 'Anda adalah Host room ini.');
        }

        // 5. Cek Slot Penuh (Hanya hitung yang confirmed)
        $confirmedCount = $room->participants()->where('status', 'confirmed')->count();
        if ($confirmedCount >= $room->max_participants) {
            return back()->with('error', 'Mohon maaf, kuota peserta sudah penuh.');
        }

        // 6. Cek Status Peserta (LOGIKA RE-JOIN)
        $existing = $room->participants()->where('user_id', $user->id)->first();
        $participant = null;

        if ($existing) {
            // Jika Rejected, izinkan join lagi (Reset jadi Pending)
            if ($existing->status == 'rejected') {
                $existing->update([
                    'status' => 'pending',
                    'requested_at' => now(),
                    'responded_at' => null // Reset waktu respon
                ]);
                $participant = $existing;
            } else {
                // Status pending atau confirmed
                return back()->with('info', 'Anda sudah terdaftar atau menunggu konfirmasi di room ini.');
            }
        } else {
            // Buat Baru
            $participant = $room->participants()->create([
                'user_id' => $user->id,
                'status' => 'pending',
                'requested_at' => now()
            ]);
        }

        // 7. Kirim Notifikasi Email ke Host
        if ($participant && $room->host->email) {
            try {
                Mail::to($room->host->email)->send(new RoomJoinRequest($participant));
            } catch (\Exception $e) {
                Log::error('Gagal kirim email join request: ' . $e->getMessage());
            }
        }

        // 8. Log Aktivitas
        ActivityLog::create([
            'actor_id' => $user->id,
            'action' => 'join_requested',
            'subject_type' => Room::class,
            'subject_id' => $room->id
        ]);

        return back()->with('success', 'Permintaan bergabung berhasil dikirim! Menunggu konfirmasi Host.');
    }

    // =================================================================
    // 2. KONFIRMASI VIA MAGIC LINK EMAIL (TANPA LOGIN)
    // =================================================================
    public function confirmFromEmail(RoomParticipant $participant)
    {
        if ($participant->status === 'confirmed') {
            return view('pages.confirmation_success', compact('participant'));
        }

        // Cek Slot sebelum update
        if ($participant->room->participants()->where('status', 'confirmed')->count() >= $participant->room->max_participants) {
            return view('pages.confirmation_failed', ['message' => 'Gagal konfirmasi. Slot room sudah penuh.']);
        }


        $participant->update([
            'status' => 'confirmed',
            'responded_at' => now()
        ]);

        $this->notifyParticipant($participant, 'accepted');

        ActivityLog::create([
            'actor_id' => $participant->room->host_id,
            'action' => 'join_confirmed_via_email',
            'subject_type' => Room::class,
            'subject_id' => $participant->room->id,
            'meta' => json_encode(['participant_user_id' => $participant->user_id])
        ]);

        return view('pages.confirmation_success', compact('participant'));
    }

    // =================================================================
    // 3. KONFIRMASI VIA DASHBOARD (TERIMA PESERTA)
    // =================================================================
    public function confirm(Request $request, Room $room, RoomParticipant $participant)
    {
        if (Auth::id() !== $room->host_id) {
            abort(403, 'Anda bukan host room ini.');
        }

        // Cek Slot lagi sebelum konfirmasi
        $confirmedCount = $room->participants()->where('status', 'confirmed')->count();
        if ($confirmedCount >= $room->max_participants) {
            return back()->with('error', 'Gagal konfirmasi. Slot room sudah penuh!');
        }

        $participant->update([
            'status' => 'confirmed',
            'responded_at' => now()
        ]);

        $this->notifyParticipant($participant, 'accepted');

        ActivityLog::create([
            'actor_id' => Auth::id(),
            'action' => 'join_confirmed',
            'subject_type' => Room::class,
            'subject_id' => $room->id,
            'meta' => json_encode(['participant_user_id' => $participant->user_id])
        ]);

        return back()->with('success', 'Peserta berhasil diterima.');
    }

    // =================================================================
    // 4. TOLAK PESERTA (REJECT)
    // =================================================================
    public function reject(Request $request, Room $room, RoomParticipant $participant)
    {
        if (Auth::id() !== $room->host_id) {
            abort(403, 'Anda bukan host room ini.');
        }

        $participant->update([
            'status' => 'rejected',
            'responded_at' => now()
        ]);

        // Kirim Notifikasi Penolakan
        $this->notifyParticipant($participant, 'rejected');

        ActivityLog::create([
            'actor_id' => Auth::id(),
            'action' => 'join_rejected',
            'subject_type' => Room::class,
            'subject_id' => $room->id,
            'meta' => json_encode(['participant_user_id' => $participant->user_id])
        ]);

        return back()->with('success', 'Permintaan bergabung ditolak.');
    }

    // =================================================================
    // 5. LEAVE ROOM (KELUAR)
    // =================================================================
    public function leave(Room $room)
    {
        $user = Auth::user();

        // 1. Host tidak bisa keluar
        if ($room->host_id === $user->id) {
            return back()->with('error', 'Host tidak bisa keluar. Silakan hapus room jika ingin membatalkan.');
        }

        // 2. Cari data peserta yang mau dihapus
        $participant = $room->participants()->where('user_id', $user->id)->first();

        if ($participant) {
            // 3. Hapus dari database
            $participant->delete();

            // 4. Notif WA ke Host kalau ada yang keluar
            if ($room->host->phone) {
                $pesanHost = "Halo Host! 👋\n\n" .
                    "Peserta *{$user->name}* baru saja KELUAR (Leave) dari room:\n" .
                    "🏆 *{$room->title}*\n\n" .
                    "Slot kosong bertambah 1.";
                $this->sendWhatsapp($room->host->phone, $pesanHost);
            }

            // 5. Log & Redirect
            ActivityLog::create([
                'actor_id' => $user->id,
                'action' => 'left_room',
                'subject_type' => Room::class,
                'subject_id' => $room->id
            ]);
            return back()->with('success', 'Anda berhasil keluar dari room.');
        }

        return back()->with('error', 'Anda tidak terdaftar di room ini.');
    }


    // =================================================================
    // 6. PRIVATE HELPER: KIRIM NOTIFIKASI (WA & EMAIL)
    // =================================================================
    private function notifyParticipant(RoomParticipant $participant, $type = 'accepted')
    {
        $userPeserta = $participant->user;
        $room = $participant->room;

        // A. Notifikasi DITERIMA
        if ($type == 'accepted') {
            // Email
            if ($userPeserta->email) {
                try {
                    Mail::to($userPeserta->email)->send(new RoomJoinConfirmed($participant));
                } catch (\Exception $e) {
                    Log::error('Gagal kirim email konfirmasi: ' . $e->getMessage());
                }
            }
            // WhatsApp
            if ($userPeserta->phone) {
                $pesan = "Halo *{$userPeserta->name}*! 👋\n\n" .
                    "Hore! Permintaan join kamu di room:\n" .
                    "🏆 *{$room->title}*\n\n" .
                    "Telah *DITERIMA* oleh Host ✅.\n\n" .
                    "📅 Waktu: {$room->start_datetime->format('d M Y, H:i')} WIB\n" .
                    "📍 Lokasi: {$room->venue->name}\n\n" .
                    "Jangan terlambat ya! Sampai jumpa di lapangan. ⚽🏀";

                $this->sendWhatsapp($userPeserta->phone, $pesan);
            }
        }
        // B. Notifikasi DITOLAK
        else if ($type == 'rejected') {
            // WhatsApp Reject
            if ($userPeserta->phone) {
                $pesan = "Halo *{$userPeserta->name}*,\n\n" .
                    "Mohon maaf, permintaan join kamu di room:\n" .
                    "🚫 *{$room->title}*\n\n" .
                    "Belum dapat diterima oleh Host saat ini. 😔\n" .
                    "Silakan cari room olahraga lainnya di SportClub!";

                $this->sendWhatsapp($userPeserta->phone, $pesan);
            }
        }
    }

    // =================================================================
    // 7. PRIVATE HELPER: API FONNTE (WHATSAPP GATEWAY)
    // =================================================================
    private function sendWhatsapp($nomor, $pesan)
    {
        try {
            // Format nomor 08xx jadi 628xx
            $nomor = preg_replace('/^0/', '62', $nomor);
            $nomor = preg_replace('/[^0-9]/', '', $nomor);

            $token = env('FONNTE_TOKEN', '');

            if (!$token)
                return;

            Http::withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                        'target' => $nomor,
                        'message' => $pesan,
                        'countryCode' => '62',
                    ]);

        } catch (\Exception $e) {
            Log::error('Gagal kirim WhatsApp: ' . $e->getMessage());
        }
    }
}