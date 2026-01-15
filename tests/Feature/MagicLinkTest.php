<?php

namespace Tests\Feature;

use App\Models\Room;
use App\Models\RoomParticipant;
use App\Models\User;
use App\Models\Sport;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class MagicLinkTest extends TestCase
{
    use RefreshDatabase;

    public function test_host_can_confirm_participant_via_signed_email_link()
    {
        // 1. Setup Data
        $host = User::factory()->create();
        $participantUser = User::factory()->create();

        $sport = Sport::create(['name' => 'Futsal', 'created_by' => $host->id]);
        $venue = Venue::create([
            'name' => 'Venue A',
            'city' => 'Jakarta',
            'address' => 'Jl. Test No. 1',
            'latitude' => -6.200000,
            'longitude' => 106.816666
        ]);

        $room = Room::create([
            'host_id' => $host->id,
            'sport_id' => $sport->id,
            'venue_id' => $venue->id,
            'title' => 'Mabar Futsal',
            'start_datetime' => now()->addDay(),
            'max_participants' => 10,
            'total_cost' => 100000,
            'cost_per_person' => 10000,
            'is_active' => true,
            'code' => 'XYZ123'
        ]);

        $participant = RoomParticipant::create([
            'room_id' => $room->id,
            'user_id' => $participantUser->id,
            'status' => 'requested',
            'requested_at' => now(),
        ]);

        // 2. Generate Signed URL
        $url = URL::signedRoute('participants.confirm_email', ['participant' => $participant->id]);

        // 3. Visit URL
        $response = $this->get($url);

        // 4. Assert
        $response->assertStatus(200);
        $response->assertSee('Berhasil Diterima'); // Confirmation success message

        // Check DB
        $this->assertDatabaseHas('room_participants', [
            'id' => $participant->id,
            'status' => 'confirmed'
        ]);
    }

    public function test_host_can_reject_participant_via_signed_email_link()
    {
        // 1. Setup Data
        $host = User::factory()->create();
        $participantUser = User::factory()->create();

        $sport = Sport::create(['name' => 'Basket', 'created_by' => $host->id]);
        $venue = Venue::create([
            'name' => 'Venue B',
            'city' => 'Jakarta',
            'address' => 'Jl. Test No. 2',
            'latitude' => -6.200000,
            'longitude' => 106.816666
        ]);

        $room = Room::create([
            'host_id' => $host->id,
            'sport_id' => $sport->id,
            'venue_id' => $venue->id,
            'title' => 'Mabar Basket',
            'start_datetime' => now()->addDay(),
            'max_participants' => 10,
            'total_cost' => 100000,
            'cost_per_person' => 10000,
            'is_active' => true,
            'code' => 'ABC456'
        ]);

        $participant = RoomParticipant::create([
            'room_id' => $room->id,
            'user_id' => $participantUser->id,
            'status' => 'requested',
            'requested_at' => now(),
        ]);

        // 2. Generate Signed URL
        $url = URL::signedRoute('participants.reject_email', ['participant' => $participant->id]);

        // 3. Visit URL
        $response = $this->get($url);

        // 4. Assert
        $response->assertStatus(200);
        $response->assertSee('Berhasil Ditolak');

        // Check DB
        $this->assertDatabaseHas('room_participants', [
            'id' => $participant->id,
            'status' => 'rejected'
        ]);
    }
}
