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
                $user->update([
                    'google_id' => $googleUser->getId(),
                ]);
                Auth::login($user);
                return redirect()->route('home');
            } else {
                // === SKENARIO 2: USER BARU (REGISTER) ===
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => null,
                    'role' => 'user',
                    'email_verified_at' => now(),
                ]);
                Auth::login($user);
                return redirect()->route('preferences.edit')->with('success', 'Selamat datang! Silakan atur preferensi olahraga Anda.');
            }

        } catch (\Exception $e) {
            Log::error('Google Login Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Login Gagal. Silakan coba lagi.');
        }
    }
}