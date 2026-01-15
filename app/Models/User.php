<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'avatar',
        'bio',
        'google_id',
        'has_password', 
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // =================================================================
    // RELASI DATABASE (PENTING UNTUK CASCADE DELETE & FITUR UTAMA)
    // =================================================================

    /**
     * Relasi ke Room yang DI-HOST oleh user ini.
     * Digunakan untuk: $user->rooms (Melihat room sendiri) atau $user->rooms()->delete() (Hapus akun)
     */
    public function rooms()
    {
        return $this->hasMany(Room::class, 'host_id');
    }

    /**
     * Relasi ke Room yang DI-IKUTI oleh user ini (sebagai peserta).
     * Digunakan untuk: History mabar yang diikuti.
     */
    public function participants()
    {
        return $this->hasMany(RoomParticipant::class, 'user_id');
    }

    // =================================================================
    // HELPER METHODS
    // =================================================================

    public function isAdmin() {
        return $this->role === 'admin';
    }
}