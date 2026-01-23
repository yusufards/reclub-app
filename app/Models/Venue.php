<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara massal.
     * Sesuaikan 'image' agar cocok dengan view ($venue->image).
     */
    protected $fillable = [
        'name',
        'address',
        'city',
        'latitude',
        'longitude',
        'price_per_hour',
        'rating',
        'image', // Diganti dari 'image_url' agar sesuai dengan controller/view
        'description',
        'phone'
    ];

    /**
     * Casting tipe data agar latitude/longitude terbaca sebagai angka (float)
     */
    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'price_per_hour' => 'integer',
    ];

    // =================================================================
    // RELATIONS
    // =================================================================

    /**
     * 1. Relasi ke SEMUA Room
     * (Termasuk room yang sudah lewat/history).
     */
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    /**
     * 2. Relasi KHUSUS Room Aktif & Masa Depan
     * (Solusi agar jadwal lama tidak muncul di Pencarian).
     */
    public function activeRooms()
    {
        return $this->hasMany(Room::class)
            ->where('is_active', true)                 // Hanya yang status aktif
            ->where('start_datetime', '>=', now())     // Hanya yang belum lewat (masa depan)
            ->orderBy('start_datetime', 'asc');        // Urutkan dari yang terdekat
    }

    /**
     * 3. Relasi ke Sports (Many-to-Many)
     */
    public function sports()
    {
        return $this->belongsToMany(Sport::class, 'venue_sport');
    }
}