<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sport;
use Illuminate\Support\Facades\Auth;

class UserPreferenceController extends Controller
{
    public function edit()
    {
        $sports = Sport::all();
        $user = Auth::user();
        $userSports = $user->sports->pluck('id')->toArray();

        return view('auth.favorites', compact('sports', 'user', 'userSports'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'sports' => 'required|array|min:3',
            'sports.*' => 'exists:sports,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ], [
            'sports.min' => 'Pilih minimal 3 olahraga favorit.',
            'latitude.required' => 'Lokasi wajib diaktifkan.',
        ]);

        $user = Auth::user();

        // Sync sports
        $user->sports()->sync($request->sports);

        // Update location
        $user->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->route('home')->with('success', 'Preferensi berhasil disimpan!');
    }
}
