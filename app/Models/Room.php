<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable; // <-- Import Sluggable

/**
 * @property int $id
 * @property int $host_id
 * @property int $sport_id
 * @property int $venue_id
 * @property string $title
 * @property string $slug // <-- Tambahkan slug di sini
 * @property string|null $description
 * @property string $code // <-- Tambahkan code di sini
 * @property \Illuminate\Support\Carbon $start_datetime
 * @property \Illuminate\Support\Carbon|null $end_datetime
 * @property int $max_participants
 * @property float $cost_per_person
 * @property bool $is_active
 * @property array|null $booking_confirmation
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Venue $venue
 * @property-read \App\Models\Sport $sport
 * @property-read \App\Models\User $host
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\RoomParticipant[] $participants
 */
class Room extends Model
{
    use HasFactory, Sluggable; // <-- Tambahkan Sluggable

    protected $fillable = [
        'host_id',
        'sport_id',
        'venue_id',
        'title',
        'description',
        'start_datetime',
        'end_datetime',
        'max_participants',
        'cost_per_person',
        'is_active',
        'booking_confirmation',
        'code',
        // 'slug' tidak perlu di fillable jika dibuat otomatis oleh Sluggable
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'booking_confirmation' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Tentukan sumber data untuk membuat slug.
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title', // Slug dibuat dari kolom 'title'
                'onUpdate' => true, // Slug diperbarui jika title berubah
            ]
        ];
    }
    
    /**
     * Tentukan kunci model yang digunakan untuk Route Model Binding.
     * Menggunakan slug agar URL terlihat bersih.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // --- RELATIONS ---

    public function venue() { return $this->belongsTo(Venue::class); }
    public function sport() { return $this->belongsTo(Sport::class); }
    public function host() { return $this->belongsTo(User::class, 'host_id'); }
    public function participants() { return $this->hasMany(RoomParticipant::class); }

    // Haversine Scope
    public function scopeNearby($query, $lat, $lng)
    {
        return $query->join('venues', 'rooms.venue_id', '=', 'venues.id')
            ->select('rooms.*', 'venues.name as venue_name', 'venues.latitude', 'venues.longitude')
            ->selectRaw("
                (6371 * acos(
                    cos(radians(?)) * cos(radians(venues.latitude)) * cos(radians(venues.longitude) - radians(?)) +
                    sin(radians(?)) * sin(radians(venues.latitude))
                )) AS distance_km
            ", [$lat, $lng, $lat])
            ->orderBy('distance_km', 'ASC');
    }
}