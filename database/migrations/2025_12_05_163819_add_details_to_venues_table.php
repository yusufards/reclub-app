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
            $table->decimal('price_per_hour', 10, 2)->default(0)->after('city');
            $table->decimal('rating', 3, 2)->default(0)->after('price_per_hour');
            $table->string('image_url')->nullable()->after('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('venues', function (Blueprint $table) {
            $table->dropColumn(['price_per_hour', 'rating', 'image_url']);
        });
    }
};