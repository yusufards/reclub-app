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
        $sports = ['Futsal', 'Badminton', 'Basketball', 'Mini Soccer', 'Tennis'];
        foreach ($sports as $s) {
            Sport::updateOrCreate(
                ['name' => $s],
                ['created_by' => $admin->id]
            );
        }

        // 3. Venues (Jakarta/Bandung sample coords)
        $venues = [
            ['name' => 'Gor C-Tra Arena', 'city' => 'Bandung', 'lat' => -6.8906, 'lng' => 107.6335],
            ['name' => 'Progresif Football', 'city' => 'Bandung', 'lat' => -6.9360, 'lng' => 107.6710],
            ['name' => 'GBK Arena', 'city' => 'Jakarta', 'lat' => -6.2183, 'lng' => 106.8023],
            ['name' => 'Pameungpeuk Futsal', 'city' => 'Bandung', 'lat' => -6.9922, 'lng' => 107.6000],
            ['name' => 'Taman Menteng', 'city' => 'Jakarta', 'lat' => -6.1965, 'lng' => 106.8294],
        ];

        foreach ($venues as $v) {
            Venue::create([
                'name' => $v['name'],
                'address' => 'Sample Address for ' . $v['name'],
                'city' => $v['city'],
                'latitude' => $v['lat'],
                'longitude' => $v['lng']
            ]);
        }
    }
}