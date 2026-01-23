<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Sport;
use App\Models\Venue;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin: create or update existing admin user (idempotent)
        $adminEmail = env('SEED_ADMIN_EMAIL', 'admin@example.com');
        $admin = User::updateOrCreate([
            'email' => $adminEmail,
        ], [
            'name' => 'Super Admin',
            'password' => Hash::make(env('SEED_ADMIN_PASSWORD', 'adminpassword')),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // 2. Sports (idempotent)
        $sports = [
            'Futsal',
            'Badminton',
            'Basketball',
            'Mini Soccer',
            'Tennis',
            'Volleyball',
            'Table Tennis',
            'Bowling',
            'Billiard',
            'Gym / Fitness',
            'Swimming',
            'Jogging',
            'Cycling',
            'Golf',
            'Yoga'
        ];
        foreach ($sports as $s) {
            Sport::updateOrCreate(
                ['name' => $s],
                ['created_by' => $admin->id]
            );
        }

        // 3. Venues (Jakarta/Bandung sample coords)
        // 3. Venues (Full Data from VenueSeeder)
        $this->call(VenueSeeder::class);

        // 4. Attach ALL Sports to ALL Venues (Initial Setup)
        // This ensures every venue supports every sport by default, 
        // preventing empty selections until specific data is managed.
        $allSports = Sport::all();
        $allVenues = Venue::all();

        foreach ($allVenues as $venue) {
            // syncWithoutDetaching avoids duplicates if run multiple times
            $venue->sports()->syncWithoutDetaching($allSports->pluck('id'));
        }

        /*
        foreach ($venues as $v) {
            Venue::create([
                'name' => $v['name'],
                'address' => 'Sample Address for ' . $v['name'],
                'city' => $v['city'],
                'latitude' => $v['lat'],
                'longitude' => $v['lng']
            ]);
        }
        */
    }
}