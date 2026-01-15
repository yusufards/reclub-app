<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Sport;
use App\Models\Venue;
use App\Models\Room;

class RoomFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_room()
    {
        $user = User::factory()->create();
        $sport = Sport::create(['name' => 'Test Sport', 'created_by' => $user->id]);
        $venue = Venue::create(['name' => 'Venue', 'address' => 'A', 'city' => 'C', 'latitude' => 0, 'longitude' => 0]);

        $response = $this->actingAs($user)->post(route('rooms.store'), [
            'sport_id' => $sport->id,
            'venue_id' => $venue->id,
            'title' => 'My Game',
            'start_datetime' => now()->addDay(),
            'max_participants' => 10,
            'cost_per_person' => 50
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('rooms', ['title' => 'My Game']);
    }

    public function test_join_flow_dispatches_jobs()
    {
        $host = User::factory()->create();
        $joiner = User::factory()->create();
        $sport = Sport::create(['name' => 'S', 'created_by' => $host->id]);
        $venue = Venue::create(['name' => 'V', 'address' => 'A', 'city' => 'C', 'latitude' => 0, 'longitude' => 0]);
        
        $room = Room::create([
            'host_id' => $host->id, 'sport_id' => $sport->id, 'venue_id' => $venue->id,
            'title' => 'Game', 'start_datetime' => now()->addDay(), 
            'max_participants' => 5, 'cost_per_person' => 10, 'is_active' => true
        ]);

        // Join
        $this->actingAs($joiner)->post(route('rooms.join', $room));
        $this->assertDatabaseHas('room_participants', ['user_id' => $joiner->id, 'status' => 'requested']);

        // Confirm
        $participant = $room->participants()->first();
        $this->actingAs($host)->post(route('participants.confirm', [$room, $participant]));
        
        $this->assertDatabaseHas('room_participants', ['user_id' => $joiner->id, 'status' => 'confirmed']);
    }
}