<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Venue;
use App\Models\Sport;
use App\Models\Room;

class VenueController extends Controller
{
    /**
     * 1. SEARCH FOR VENUES (Digunakan saat Create Room)
     * Logic: Filter Kota, Keyword, dan Urutkan Jarak (Map).
     */
    public function search(Request $request)
    {
        $lat = $request->get('lat');
        $lng = $request->get('lng');
        $city = $request->get('city');
        $keyword = $request->get('keyword');

        $query = Venue::query()->select('venues.*');

        if ($request->filled('city')) {
            $query->where('city', $city);
        }

        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('address', 'like', "%{$keyword}%");
            });
        }

        if ($lat && $lng) {
            $haversine = "(6371 * acos(cos(radians(?)) * cos(radians(venues.latitude)) * cos(radians(venues.longitude) - radians(?)) + sin(radians(?)) * sin(radians(venues.latitude))))";
            $query->selectRaw("{$haversine} AS distance", [$lat, $lng, $lat])->orderBy('distance', 'asc');
        } else {
            $query->orderBy('city', 'asc')->orderBy('name', 'asc');
        }

        $venues = $query->get();
        $cities = Venue::select('city')->distinct()->orderBy('city')->pluck('city');
        $sports = Sport::all();

        return view('venues.map_search', compact('venues', 'cities', 'sports'));
    }

    /**
     * 2. PENCARIAN ROOM UNTUK JOINER (Fitur Cari Mabar)
     * Menampilkan daftar Venue yang MEMILIKI Room Aktif (Masa Depan).
     */
    public function searchForJoin(Request $request)
    {
        // A. Filter Venue
        $lat = $request->get('lat');
        $lng = $request->get('lng');
        $sportId = $request->get('sport_id');
        $city = $request->get('city');

        $venueQuery = Venue::query()->select('venues.*');

        // Filter: Hanya Venue yang punya Room Aktif (Upcoming)
        // Menggunakan relasi 'activeRooms' yang sudah didefinisikan di Model Venue
        $venueQuery->whereHas('activeRooms', function ($q) use ($sportId) {
            if ($sportId) {
                $q->where('sport_id', $sportId);
            }
        });

        if ($city) {
            $venueQuery->where('city', $city);
        }

        // Sorting Jarak Venue
        if ($lat && $lng) {
            $haversine = "(6371 * acos(cos(radians(?)) * cos(radians(venues.latitude)) * cos(radians(venues.longitude) - radians(?)) + sin(radians(?)) * sin(radians(venues.latitude))))";
            $venueQuery->selectRaw("{$haversine} AS distance", [$lat, $lng, $lat])->orderBy('distance', 'asc');
        } else {
            $venueQuery->inRandomOrder();
        }

        // Ambil data venues beserta activeRooms (untuk preview jadwal di view)
        $venues = $venueQuery->with(['activeRooms.sport'])
            ->get();

        // B. Ambil Data Rooms (Untuk Mode List Biasa / Jika User Tidak Memilih Peta)
        // Filter room aktif dan masa depan
        $venueIds = $venues->pluck('id');

        $rooms = Room::whereIn('venue_id', $venueIds)
            ->where('is_active', true)
            ->where('start_datetime', '>=', now()) // Hanya masa depan
            ->when($sportId, function ($q) use ($sportId) {
                return $q->where('sport_id', $sportId);
            })
            ->with(['host', 'sport', 'venue', 'participants'])
            ->withCount('participants')
            ->orderBy('start_datetime', 'asc')
            ->paginate(12);

        // Data Pendukung
        $sports = Sport::all();
        $cities = Venue::select('city')->distinct()->pluck('city');
        $stat_interaksi = 0;

        return view('rooms.index', compact('venues', 'rooms', 'sports', 'cities', 'stat_interaksi'));
    }

    /**
     * 3. SHOW SPECIFIC VENUE & ITS ROOMS
     * Route: /venues/{id}/rooms
     */
    public function rooms($id)
    {
        // 1. Cari Venue
        $venue = Venue::findOrFail($id);

        // 2. Ambil Room di Venue tersebut (Hanya Masa Depan)
        $rooms = Room::where('venue_id', $id)
            ->where('is_active', true)
            ->where('start_datetime', '>=', now()) // Filter Masa Depan
            ->with(['host', 'sport', 'participants.user'])
            ->withCount('participants')
            ->orderBy('start_datetime', 'asc')
            ->paginate(12);

        // 3. Return view detail venue
        return view('venues.browse_rooms', compact('venue', 'rooms'));
    }

    /**
     * 4. Legacy/Alias Methods
     */
    public function searchMap(Request $request)
    {
        return $this->search($request);
    }

    public function showVenueRooms(Venue $venue)
    {
        return $this->rooms($venue->id);
    }
}