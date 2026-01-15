<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Venue;
use App\Models\Sport;
use App\Models\Room;

class CompleteDataSeeder extends Seeder
{
    public function run(): void
    {
        $host = User::first() ?? User::factory()->create(['name' => 'Host Default']);

        $futsal = Sport::where('name', 'like', '%Futsal%')->first();
        if (!$futsal) $futsal = Sport::create(['name' => 'Futsal', 'created_by' => $host->id]);

        $badminton = Sport::where('name', 'like', '%Badminton%')->first();
        if (!$badminton) $badminton = Sport::create(['name' => 'Badminton', 'created_by' => $host->id]);

        $venues = Venue::all();
        if ($venues->count() == 0) {
            $this->command->error('Venue kosong! Jalankan php artisan db:seed --class=VenueSeeder dulu.');
            return;
        }

        foreach ($venues as $index => $venue) {
            $sport = ($index % 2 == 0) ? $futsal : $badminton;

            Room::create([
                'host_id' => $host->id,
                'sport_id' => $sport->id,
                'venue_id' => $venue->id,
                'title' => 'Main Bareng di ' . $venue->name,
                'description' => 'Latihan santai sore hari.',
                'start_datetime' => now()->addDays(rand(1, 5))->setHour(19)->setMinute(0),
                'max_participants' => 10,
                'cost_per_person' => 50000,
                'is_active' => true,
            ]);
        }
        
        $this->command->info('Berhasil membuat Room! Sekarang Venue punya Sport.');
    }
}