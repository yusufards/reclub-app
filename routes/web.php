<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SportController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HistoryController;
// Note: RoomParticipantController tidak lagi dibutuhkan untuk route utama karena logika sudah pindah ke RoomController

// =================================================================
// 1. PUBLIC ROUTES (Accessible to everyone)
// =================================================================

// Landing Page
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Main App: Explore (Room List)
Route::get('/explore', [RoomController::class, 'index'])->name('home');

// --- VENUE & SEARCH SYSTEM ---

// 1. Venue Search (For Creating Rooms)
Route::get('/venues/search', [VenueController::class, 'search'])->name('venues.search');
Route::get('/cari-lapangan', [VenueController::class, 'searchMap'])->name('cari.lapangan'); // Alias

// 2. Searching Rooms to Join (Cari Mabar Page)
Route::get('/cari-mabar', [VenueController::class, 'searchForJoin'])->name('cari.mabar');

// 3. Browse Rooms in Specific Venue (Venue Detail)
Route::get('/venues/{id}/rooms', [VenueController::class, 'rooms'])->name('venues.rooms');

// [NEW] Route for Polling Room Status (Real-time check)
Route::get('/rooms/{id}/check-status', [RoomController::class, 'checkStatus'])->name('rooms.check');


// =================================================================
// 2. GUEST AUTH ROUTES (Only for non-logged-in users)
// =================================================================
Route::middleware('guest')->group(function () {

    // Login & Register
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Google Socialite
    Route::get('/auth/google/redirect', [SocialAuthController::class, 'redirect'])->name('google.redirect');
    Route::get('/auth/google/callback', [SocialAuthController::class, 'callback']);

    // Forgot Password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');
});


// =================================================================
// 3. AUTHENTICATED ROUTES (Login Required)
// =================================================================
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // --- PROFILE & SETTINGS ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- USER DASHBOARD ---
    Route::get('/dashboard', function () {
        $user = Auth::user();
        $hostedCount = \App\Models\Room::where('host_id', $user->id)->count();
        $joinedCount = \App\Models\RoomParticipant::where('user_id', $user->id)->count();

        // Hosted Rooms (Active & Recently Passed)
        $myRooms = \App\Models\Room::where('host_id', $user->id)
            ->where('start_datetime', '>=', now()->subHours(2))
            ->with(['sport', 'venue', 'participants.user'])
            ->orderBy('start_datetime', 'asc')->get();

        // Joined Rooms
        $joinedRooms = \App\Models\RoomParticipant::where('user_id', $user->id)
            ->whereHas('room', function ($q) {
                $q->where('start_datetime', '>=', now()->subHours(2));
            })
            ->with(['room.sport', 'room.venue', 'room.host'])
            ->latest()->get();

        return view('user.dashboard', compact('hostedCount', 'joinedCount', 'myRooms', 'joinedRooms'));
    })->name('dashboard');

    // History
    Route::get('/history', [HistoryController::class, 'index'])->name('history.index');

    // --- ROOM MANAGEMENT (CRUD) ---
    // [IMPORTANT] Create Route MUST be BEFORE wildcard {room}
    Route::get('/rooms/create', [RoomController::class, 'create'])->name('rooms.create');
    Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');

    Route::get('/rooms/{room}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
    Route::put('/rooms/{room}', [RoomController::class, 'update'])->name('rooms.update');
    Route::delete('/rooms/{room}', [RoomController::class, 'destroy'])->name('rooms.destroy');

    // --- JOIN & PARTICIPANTS SYSTEM ---

    // 1. Join Room (From Button)
    Route::post('/rooms/{room}/join', [RoomController::class, 'join'])->name('rooms.join');

    // 2. Join via Code (Form Input)
    Route::post('/rooms/join-code', [RoomController::class, 'joinByCode'])->name('rooms.join_code');

    // 3. Leave Room
    Route::post('/rooms/{room}/leave', [RoomController::class, 'leave'])->name('rooms.leave');

    // 4. Host Actions (Confirm/Reject via Dashboard)
    Route::post('/rooms/{room}/participants/{participant}/confirm', [RoomController::class, 'confirm'])
        ->name('participants.confirm');

    Route::post('/rooms/{room}/participants/{participant}/reject', [RoomController::class, 'reject'])
        ->name('participants.reject');

    // --- ADMIN AREA ---
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/activity', [AdminController::class, 'activityLogs'])->name('activity');
        Route::resource('sports', SportController::class);
    });
});


// =================================================================
// 4. DETAIL ROOM (PUBLIC) - MUST BE AT THE BOTTOM
// =================================================================
// Wildcard {room} catches all /rooms/... URLs, so place it last.
Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('rooms.show');


// =================================================================
// 5. MAGIC LINK ROUTES (Email Actions)
// =================================================================
// [UPDATED] Mengarah ke RoomController karena logika email action ada di sana
Route::get('/participants/{participant}/confirm-email', [RoomController::class, 'confirmFromEmail'])
    ->name('participants.confirm_email')->middleware('signed');

Route::get('/participants/{participant}/reject-email', [RoomController::class, 'rejectFromEmail'])
    ->name('participants.reject_email')->middleware('signed');