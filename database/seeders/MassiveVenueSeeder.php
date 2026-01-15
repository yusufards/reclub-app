<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Venue;
use App\Models\Room;
use App\Models\Sport;
use App\Models\User;

class MassiveVenueSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Setup User & Sport
        $host = User::first() ?? User::factory()->create(['name' => 'Juragan Lapang']);
        
        $futsal = Sport::firstOrCreate(['name' => 'Futsal'], ['created_by' => $host->id]);
        $badminton = Sport::firstOrCreate(['name' => 'Badminton'], ['created_by' => $host->id]);
        $minisoccer = Sport::firstOrCreate(['name' => 'Mini Soccer'], ['created_by' => $host->id]);

        // 2. Daftar Lokasi Dummy (Kota Besar)
        $locations = [
            // JAKARTA PUSAT (Monas area)
            [
                'city' => 'Jakarta',
                'lat_center' => -6.1754, 
                'lng_center' => 106.8272,
                'names' => ['Monas Futsal', 'GBK Arena', 'Jakarta Sport Center', 'Kemayoran Field', 'Senayan Badminton']
            ],
            // BANDUNG (Gedung Sate area)
            [
                'city' => 'Bandung',
                'lat_center' => -6.9025, 
                'lng_center' => 107.6188,
                'names' => ['Siliwangi Futsal', 'Dago Dream Park Sport', 'Tubagus Ismail Arena', 'Antapani Soccer', 'Buah Batu Gym']
            ],
            // SURABAYA (Tunjungan area)
            [
                'city' => 'Surabaya',
                'lat_center' => -7.2575, 
                'lng_center' => 112.7521,
                'names' => ['Surabaya Futsal', 'Tunjungan Plaza Sport', 'Gubeng Arena', 'Kenjeran Field', 'Wiyung Soccer']
            ]
        ];

        // 3. Loop Create Venues
        foreach ($locations as $loc) {
            foreach ($loc['names'] as $index => $name) {
                
                // Geser koordinat sedikit biar tidak numpuk (Random spread)
                $lat = $loc['lat_center'] + (rand(-50, 50) / 10000); 
                $lng = $loc['lng_center'] + (rand(-50, 50) / 10000);

                $venue = Venue::create([
                    'name' => $name,
                    'address' => "Jl. Raya " . $loc['city'] . " No. " . rand(1, 100),
                    'city' => $loc['city'],
                    'latitude' => $lat,
                    'longitude' => $lng,
                    'price_per_hour' => rand(50, 300) * 1000, // 50rb - 300rb
                    'rating' => rand(30, 50) / 10, // 3.0 - 5.0
                    'image_url' => null
                ]);

                // 4. Create Room untuk Venue ini (Agar muncul di filter)
                // Rotasi olahraga: Futsal -> Badminton -> Mini Soccer
                if ($index % 3 == 0) $sport = $futsal;
                elseif ($index % 3 == 1) $sport = $badminton;
                else $sport = $minisoccer;

                Room::create([
                    'host_id' => $host->id,
                    'sport_id' => $sport->id,
                    'venue_id' => $venue->id,
                    'title' => "Mabar " . $sport->name . " di " . $venue->city,
                    'description' => 'Yuk join, kurang 2 orang nih!',
                    'start_datetime' => now()->addDays(rand(1, 7))->setHour(rand(16, 22))->setMinute(0),
                    'max_participants' => 10,
                    'cost_per_person' => rand(10, 50) * 1000,
                    'is_active' => true,
                ]);
            }
        }

        $this->command->info('Sukses! Data lapangan di Jakarta, Bandung, Surabaya berhasil dibuat.');
    }
}