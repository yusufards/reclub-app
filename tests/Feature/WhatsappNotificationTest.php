<?php

namespace Tests\Feature;

use App\Models\Room;
use App\Models\RoomParticipant;
use App\Models\User;
use App\Models\Sport;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WhatsappNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_host_receives_whatsapp_when_user_joins()
    {
        // 1. Mock External API (Fonnte)
        Http::fake([
            'api.fonnte.com/*' => Http::response(['status' => true], 200),
        ]);

        // 2. Setup Data
        $host = User::factory()->create(['phone' => '081234567890']);
        $joiner = User::factory()->create(['name' => 'Joiner User']);

        $sport = Sport::create(['name' => 'Futsal', 'created_by' => $host->id]);
        $venue = Venue::create([
            'name' => 'Venue Test',
            'city' => 'Jakarta',
            'address' => 'Jl. Test',
            'latitude' => 0,
            'longitude' => 0
        ]);

        $room = Room::create([
            'host_id' => $host->id,
            'sport_id' => $sport->id,
            'venue_id' => $venue->id,
            'title' => 'Mabar WA Test',
            'start_datetime' => now()->addDay(),
            'max_participants' => 5,
            'total_cost' => 50000,
            'cost_per_person' => 10000,
            'is_active' => true,
            'code' => 'TESTWA'
        ]);

        // 3. Act: Joiner joins the room
        $response = $this->actingAs($joiner)
            ->post(route('rooms.join', $room));

        // 4. Assert
        $response->assertRedirect();

        // Assert WhatsApp API was called
        Http::assertSent(function ($request) use ($host) {
            return $request->url() == 'https://api.fonnte.com/send' &&
                $request['target'] == '6281234567890' &&
                str_contains($request['message'], 'Halo Host!') &&
                str_contains($request['message'], 'Mabar WA Test');
        });
    }
}
