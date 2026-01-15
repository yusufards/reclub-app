<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sports', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('venues', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->string('city')->index();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->timestamps();
        });

        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('host_id')->constrained('users');
            $table->foreignId('sport_id')->constrained('sports');
            $table->foreignId('venue_id')->constrained('venues');
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime')->nullable();
            $table->integer('max_participants');
            $table->decimal('cost_per_person', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->json('booking_confirmation')->nullable();
            $table->timestamps();
        });

        Schema::create('room_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['requested', 'confirmed', 'rejected'])->default('requested');
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();
            
            $table->unique(['room_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_participants');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('venues');
        Schema::dropIfExists('sports');
    }
};