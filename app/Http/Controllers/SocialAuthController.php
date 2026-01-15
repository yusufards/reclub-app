<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Contracts\Factory as SocialiteFactory;

class SocialAuthController extends Controller
{
    private SocialiteFactory $socialite;

    public function __construct(SocialiteFactory $socialite)
    {
        $this->socialite = $socialite;
    }
    public function redirect()
    {
        // stateless() disarankan untuk menghindari error 'Invalid State' di beberapa server/localhost
        $driver = $this->socialite->driver('google');
        /** @var \Laravel\Socialite\Two\AbstractProvider $driver */
        return $driver->stateless()
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    public function callback()
    {
        try {
            // Tambahkan stateless() di sini juga
            $driver = $this->socialite->driver('google');
            /** @var \Laravel\Socialite\Two\AbstractProvider $driver */
            $googleUser = $driver->stateless()->user();
            
            // 1. Cek apakah user dengan email ini sudah ada?
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // === SKENARIO 1: USER LAMA ===
                // Jika user sudah ada, kita CUKUP update Google ID-nya saja.
                // JANGAN update password atau role agar akun Admin tidak rusak.
                $user->update([
                    'google_id' => $googleUser->getId(),
                    // Opsional: jika ingin nama selalu sinkron dengan Google, uncomment baris bawah:
                    // 'name' => $googleUser->getName(), 
                ]);
            } else {
                // === SKENARIO 2: USER BARU (REGISTER) ===
                // Buat user baru dengan settingan default
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => null, // Password kosong karena via Google
                    'role' => 'user',   // User baru selalu jadi 'user' biasa
                    'email_verified_at' => now(), // Auto verify
                ]);
            }
    
            // 2. Login User
            Auth::login($user);
    
            // 3. Redirect ke Home
            return redirect()->route('home');
            
        } catch (\Exception $e) {
            Log::error('Google Login Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Login Gagal. Silakan coba lagi.');
        }
    }
}