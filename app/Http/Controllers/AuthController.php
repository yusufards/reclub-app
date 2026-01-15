<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // 1. Cek apakah Email ada di database?
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // Jika email tidak ditemukan
            return back()->withInput()->withErrors([
                'email' => 'Akun dengan email ini belum terdaftar.'
            ]);
        }

        // 2. Cek apakah Password benar?
        if (!Hash::check($request->password, $user->password)) {
            // Jika password salah
            return back()->withInput()->withErrors([
                'password' => 'Password yang Anda masukkan salah.'
            ]);
        }

        // 3. Jika Email & Password Benar, Lakukan Login
        Auth::login($user, $request->filled('remember'));
        $request->session()->regenerate();
        
        ActivityLog::create([
            'actor_id' => Auth::id(),
            'action' => 'login_email',
        ]);

        return redirect()->intended(route('home'));
    }

    public function showRegister() {
        return view('auth.register');
    }

    public function register(Request $request) {
        // Custom Pesan Error Bahasa Indonesia
        $messages = [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar. Silakan login saja.',
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ];

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'phone' => 'required|string'
        ], $messages);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'role' => 'user'
        ]);

        Auth::login($user);

        ActivityLog::create([
            'actor_id' => $user->id,
            'action' => 'register_email',
        ]);

        return redirect()->route('home')->with('success', 'Pendaftaran berhasil! Selamat datang.');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}