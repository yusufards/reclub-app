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
        Schema::table('venues', function (Blueprint $table) {
            // Cek dulu biar tidak error kalau sudah ada
            if (!Schema::hasColumn('venues', 'price_per_hour')) {
                $table->decimal('price_per_hour', 10, 2)->default(0);
                $table->decimal('rating', 3, 2)->default(0); // 0.00 - 5.00
                $table->string('image_url')->nullable(); 
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('venues', function (Blueprint $table) {
            //
        });
    }
};
