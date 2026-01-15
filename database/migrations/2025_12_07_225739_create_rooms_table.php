<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cek dulu biar tidak error "Table already exists" kalau migrasi dobel
        if (!Schema::hasTable('rooms')) {
            Schema::create('rooms', function (Blueprint $table) {
                $table->id();
                
                // --- INI PERBAIKAN PENTING (CASCADE DELETE) ---
                // Jika User/Sport/Venue dihapus, Room buatannya ikut terhapus otomatis
                $table->foreignId('host_id')->constrained('users')->onDelete('cascade');
                
                // Relasi ke tabel sports & venues
                $table->foreignId('sport_id')->constrained()->onDelete('cascade');
                $table->foreignId('venue_id')->constrained()->onDelete('cascade');
                
                $table->string('title');
                $table->text('description')->nullable();
                $table->dateTime('start_datetime');
                $table->dateTime('end_datetime'); // Field ini hanya diperlukan jika Anda melacak durasi
                $table->integer('cost_per_person');
                $table->integer('max_participants');
                $table->enum('status', ['open', 'closed', 'finished'])->default('open');
                
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};