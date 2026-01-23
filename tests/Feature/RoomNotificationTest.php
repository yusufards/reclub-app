<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Sport;
use App\Models\Venue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\RoomCreatedNotification;

class RoomNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_room_created_notification_sent_to_nearby_interested_users()
    {
        Notification::fake();

        // 0. Create Admin for Sport creation
        $admin = User::factory()->create();

        // 1. Create Sports & Venues
        $sport = Sport::create(['cardio' => 1, 'name' => 'Futsal', 'created_by' => $admin->id]);

        $venue = Venue::create([
            'name' => 'Venue A',
            'city' => 'Jakarta',
            'address' => 'Jalan A',
            'latitude' => -6.2000000,
            'longitude' => 106.8166660,
        ]);

        // 2. Create Host
        $host = User::factory()->create();

        // 3. Create Target User (Interested & Nearby)
        $targetUser = User::factory()->create([
            'latitude' => -6.2010000, // Very close
            'longitude' => 106.8166660,
        ]);
        $targetUser->sports()->attach($sport->id);

        // 4. Create Ignored User (Interested but Far away)
        $farUser = User::factory()->create([
            'latitude' => -7.2000000, // Surabaya?
            'longitude' => 112.7000000,
        ]);
        $farUser->sports()->attach($sport->id);

        // 5. Create Ignored User (Nearby but Not Interested)
        $uninterestedUser = User::factory()->create([
            'latitude' => -6.2010000,
            'longitude' => 106.8166660,
        ]);

        // 6. Act: Host Creates Room
        $this->actingAs($host)->post(route('rooms.store'), [
            'sport_id' => $sport->id,
            'venue_id' => $venue->id,
            'title' => 'Mabar Futsal Seru',
            'start_datetime' => now()->addDay(),
            'max_participants' => 10,
            'total_cost' => 100000,
        ]);

        // 7. Assert: Notification Sent ONLY to targetUser
        Notification::assertSentTo(
            [$targetUser],
            RoomCreatedNotification::class
        );

        Notification::assertNotSentTo(
            [$farUser, $uninterestedUser, $host],
            RoomCreatedNotification::class
        );
    }
}
