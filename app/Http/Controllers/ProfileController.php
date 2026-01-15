<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User; // Pastikan Model User di-import

class ProfileController extends Controller
{
    /**
     * 1. Tampilkan Halaman Edit Profil
     */
    public function edit()
    {
        return view('profile.edit');
    }

    /**
     * 2. Proses Update Data Diri (Foto, Bio, Nama, HP)
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Validasi Input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20', 
            'bio' => 'nullable|string|max:100', 
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ], [
            'avatar.image' => 'File harus berupa gambar.',
            'avatar.max' => 'Ukuran foto maksimal 2MB.',
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
        ]);

        // Cek jika user meng-upload foto baru
        if ($request->hasFile('avatar')) {
            // Hapus foto lama jika ada
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Simpan foto baru
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        // Simpan perubahan ke database
        $user->update($validated); 

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * 3. Proses Ganti Password
     */
    public function updatePassword(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Aturan Validasi Dasar
        $rules = [
            'password' => 'required|string|min:8|confirmed',
        ];

        // Logika Ganti Password: Jika TIDAK NULL (User Manual), wajib current_password
        if (!is_null($user->password)) {
            $rules['current_password'] = 'required|current_password';
        }

        // Jalankan Validasi
        $request->validate($rules, [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'current_password.current_password' => 'Password saat ini salah.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Update Password
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password berhasil diperbarui! Silakan ingat password baru Anda.');
    }

    /**
     * 4. Hapus Akun Sendiri (Danger Zone)
     */
    public function destroy(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. PROTEKSI ADMIN
        if ($user->role === 'admin') {
            return back()->with('error', 'Demi keamanan sistem, akun ADMIN tidak dapat dihapus melalui halaman ini.');
        }

        // 2. HAPUS DATA KETERGANTUNGAN SECARA MANUAL (Fix Constraint Violation 1451)
        // Jika CASCADE di DB tidak berfungsi, kita harus menghapus Room dan Participant-nya dulu.
        $user->rooms()->delete();       // Hapus semua Room yang dibuat user ini
        $user->participants()->delete(); // Hapus semua keikutsertaan user ini (termasuk chat jika relasi RoomParticipant sudah benar)

        // 3. Logout user
        Auth::logout();

        // 4. Hapus foto profil dari storage
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // 5. Hapus data user dari database (Sekarang pasti berhasil karena data anak sudah bersih)
        $user->delete();

        // 6. Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Akun Anda berhasil dihapus. Sampai jumpa lagi!');
    }
}