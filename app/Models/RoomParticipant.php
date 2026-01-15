<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id', 
        'user_id', 
        'status', // requested, confirmed, rejected
        'requested_at', 
        'responded_at'
    ];

    /**
     * PENTING: Casting
     * Mengubah kolom tanggal menjadi objek Carbon secara otomatis.
     * Jadi Anda bisa langsung pakai ->format('d M Y') di blade.
     */
    protected $casts = [
        'requested_at' => 'datetime',
        'responded_at' => 'datetime',
    ];

    // --- RELASI ---

    // Peserta ini siapa?
    public function user() 
    { 
        return $this->belongsTo(User::class); 
    }

    // Peserta ini join di room mana?
    public function room() 
    { 
        return $this->belongsTo(Room::class); 
    }

    // --- HELPER / SCOPES (Opsional tapi Berguna) ---

    // Gunanya biar di Controller bisa panggil: $room->participants()->confirmed()->get()
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'requested');
    }
}