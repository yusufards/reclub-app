@extends('layouts.app')

@section('title', 'Dashboard Saya | SportClub')

@section('content')
    {{-- Memastikan Chart.js dimuat, meskipun idealnya di @stack('scripts') --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-green-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            {{-- HEADING --}}
            <div class="mb-8 animate-fade-in">
                <h1
                    class="text-4xl font-black text-gray-900 mb-2 bg-gradient-to-r from-gray-900 to-emerald-800 bg-clip-text text-transparent">
                    Dashboard {{ Auth::user()->isAdmin() ? 'Administrator' : 'Saya' }}
                </h1>
                <p class="text-gray-600 text-lg">Selamat datang kembali, <span
                        class="font-bold text-emerald-600">{{ Auth::user()->name }}</span>! 👋</p>
            </div>

            @if(Auth::user()->isAdmin())

                {{-- =============================================== --}}
                {{-- ADMIN DASHBOARD (Tools & Management) --}}
                {{-- =============================================== --}}
                <div class="mb-10 bg-gradient-to-br from-red-50 to-orange-50 border border-red-200 rounded-3xl p-8 shadow-lg">
                    <h2 class="text-2xl font-black text-red-800 mb-6 flex items-center gap-3">
                        <span class="bg-red-200 text-red-700 p-2 rounded-xl">⚙️</span>
                        Admin Tools
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- CARD: Manage Users --}}
                        <a href="{{ route('admin.users') }}"
                            class="group bg-white p-6 rounded-2xl shadow-md border border-red-100 hover:border-red-300 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center text-2xl">👥</div>
                                <h3 class="font-bold text-lg text-gray-800 group-hover:text-red-600 transition">Manage Users
                                </h3>
                            </div>
                            <p class="text-sm text-gray-600">Kelola data pengguna aplikasi.</p>
                        </a>

                        {{-- CARD: Manage Sports --}}
                        <a href="{{ route('admin.sports.index') }}"
                            class="group bg-white p-6 rounded-2xl shadow-md border border-red-100 hover:border-red-300 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center text-2xl">⚽</div>
                                <h3 class="font-bold text-lg text-gray-800 group-hover:text-red-600 transition">Manage Sports
                                </h3>
                            </div>
                            <p class="text-sm text-gray-600">Atur kategori olahraga.</p>
                        </a>

                        {{-- CARD: Activity Logs --}}
                        <a href="{{ route('admin.activity') }}"
                            class="group bg-white p-6 rounded-2xl shadow-md border border-red-100 hover:border-red-300 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center text-2xl">📊</div>
                                <h3 class="font-bold text-lg text-gray-800 group-hover:text-red-600 transition">Activity Logs
                                </h3>
                            </div>
                            <p class="text-sm text-gray-600">Audit aktivitas sistem.</p>
                        </a>
                    </div>
                </div>
            @else

                {{-- =============================================== --}}
                {{-- USER DASHBOARD (Stats & Quick Actions) --}}
                {{-- =============================================== --}}

                {{-- Statistik & Chart --}}
                <div
                    class="bg-white p-8 rounded-3xl shadow-xl border border-emerald-100 mb-8 flex flex-col md:flex-row gap-8 items-center relative overflow-hidden">
                    {{-- Gradient Blobs for aesthetic --}}
                    <div
                        class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-emerald-400/10 to-green-400/10 rounded-full blur-3xl">
                    </div>
                    <div
                        class="absolute bottom-0 left-0 w-48 h-48 bg-gradient-to-tr from-emerald-300/10 to-green-300/10 rounded-full blur-2xl">
                    </div>

                    {{-- STATISTIK SUMMARY --}}
                    <div class="w-full md:w-1/3 relative z-10">
                        <h2 class="text-2xl font-black text-gray-800 mb-4">Statistik Aktivitas</h2>
                        <div class="bg-gradient-to-br from-emerald-500 to-green-600 p-6 rounded-2xl shadow-lg">
                            <div class="text-white/90 font-bold text-base mb-2">Total Interaksi</div>
                            <div class="text-5xl font-black text-white">{{ $hostedCount + $joinedCount }}</div>
                            <div class="mt-4 pt-4 border-t border-white/20">
                                <div class="flex justify-between text-white/90 text-sm">
                                    <span>Hosted: <strong>{{ $hostedCount }}</strong></span>
                                    <span>Joined: <strong>{{ $joinedCount }}</strong></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- CHART --}}
                    <div class="w-full md:w-2/3 h-64 relative z-10">
                        <canvas id="activityChart"></canvas>
                    </div>
                </div>

                {{-- QUICK ACTION CARDS --}}
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-6 mb-8">
                    {{-- CARD: Buat Room --}}
                    <a href="{{ route('rooms.create') }}"
                        class="group bg-gradient-to-br from-emerald-500 to-green-600 p-8 rounded-3xl shadow-xl text-white hover:shadow-2xl transition-all duration-300 transform hover:scale-105 relative overflow-hidden">
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        </div>
                        <div class="relative z-10">
                            <div
                                class="bg-white/20 backdrop-blur-sm w-14 h-14 rounded-2xl flex items-center justify-center mb-4">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="font-black text-2xl mb-2">Buat Room</h3>
                            <p class="text-emerald-100 text-sm">Host aktivitas olahraga baru.</p>
                        </div>
                    </a>

                    {{-- CARD: Cari Room --}}
                    <a href="{{ route('cari.mabar') }}"
                        class="group bg-white p-8 rounded-3xl shadow-md border border-emerald-100 hover:shadow-2xl hover:border-emerald-300 transition-all duration-300 transform hover:scale-105">
                        <div
                            class="bg-gradient-to-br from-blue-500 to-blue-600 text-white w-14 h-14 rounded-2xl flex items-center justify-center mb-4 shadow-lg">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-black text-2xl text-gray-800 mb-2">Cari Room</h3>
                        <p class="text-gray-600 text-sm">Temukan lawan main di sekitar.</p>
                    </a>

                    {{-- CARD: Riwayat --}}
                    <a href="{{ route('history.index') }}"
                        class="group bg-white p-8 rounded-3xl shadow-md border border-purple-100 hover:shadow-2xl hover:border-purple-300 transition-all duration-300 transform hover:scale-105">
                        <div
                            class="bg-gradient-to-br from-purple-500 to-purple-600 text-white w-14 h-14 rounded-2xl flex items-center justify-center mb-4 shadow-lg text-2xl">
                            🕰️
                        </div>
                        <h3 class="font-black text-2xl text-gray-800 mb-2 group-hover:text-purple-700 transition">Riwayat</h3>
                        <p class="text-gray-600 text-sm">Lihat aktivitas lampau.</p>
                    </a>

                    {{-- CARD: Join Code --}}
                    <div
                        class="bg-white p-8 rounded-3xl shadow-md border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                        <div
                            class="bg-gradient-to-br from-orange-500 to-orange-600 w-14 h-14 rounded-2xl flex items-center justify-center mb-4 text-3xl shadow-lg text-white">
                            🔑
                        </div>
                        <h3 class="font-black text-2xl text-gray-800 mb-2">Join Room</h3>
                        <p class="text-gray-600 text-sm mb-4">Masuk via kode undangan.</p>

                        <form action="{{ route('rooms.join_code') }}" method="POST" class="flex flex-col gap-2">
                            @csrf
                            <input type="text" name="code" placeholder="X7K9LP"
                                class="w-full border-2 border-gray-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 rounded-xl px-4 py-3 text-sm uppercase font-bold transition-all duration-300 text-center tracking-widest placeholder-gray-300"
                                required>
                            <button
                                class="w-full bg-gradient-to-r from-gray-900 to-gray-800 hover:from-orange-600 hover:to-orange-500 text-white py-3 rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition-all duration-300 transform active:scale-95">
                                Masuk
                            </button>
                        </form>
                    </div>
                </div>

                {{-- ROOM YANG DIKELOLA (RESPONSIVE TABLE/CARD LIST) --}}
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-black text-gray-900 flex items-center gap-3">
                            <span class="w-1.5 h-8 bg-gradient-to-b from-emerald-500 to-green-600 rounded-full"></span>
                            Room yang Dikelola
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-sm font-bold">
                                {{ $myRooms->count() }}
                            </span>
                        </h3>
                    </div>

                    {{-- 1. TAMPILAN DESKTOP (TABLE) --}}
                    <div class="hidden md:block bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-gray-50 to-emerald-50">
                                    <tr>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">
                                            Judul</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">
                                            Kode</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">
                                            Waktu</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">
                                            Peserta</th>
                                        <th
                                            class="px-6 py-4 text-right text-xs font-black text-gray-700 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @forelse($myRooms as $room)
                                        <tr class="hover:bg-emerald-50/50 transition-colors duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl flex items-center justify-center text-white font-bold shadow-md text-lg flex-shrink-0">
                                                        @php $sportName = strtolower($room->sport->name); @endphp
                                                        @if(str_contains($sportName, 'futsal') || str_contains($sportName, 'soccer') || str_contains($sportName, 'football'))
                                                            ⚽
                                                        @elseif(str_contains($sportName, 'badminton') || str_contains($sportName, 'bulu'))
                                                            🏸
                                                        @elseif(str_contains($sportName, 'basket')) 🏀
                                                        @elseif(str_contains($sportName, 'tennis') || str_contains($sportName, 'tenis'))
                                                            🎾
                                                        @elseif(str_contains($sportName, 'volly') || str_contains($sportName, 'voli'))
                                                            🏐
                                                        @elseif(str_contains($sportName, 'run') || str_contains($sportName, 'lari'))
                                                            🏃
                                                        @elseif(str_contains($sportName, 'gym') || str_contains($sportName, 'fitness'))
                                                            🏋️
                                                        @else 🏅
                                                        @endif
                                                    </div>

                                                    <div>
                                                        <div class="text-sm font-bold text-gray-900">{{ $room->title }}</div>
                                                        <div class="text-xs text-gray-500 flex items-center gap-1">
                                                            {{ $room->sport->name }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="inline-flex items-center gap-2 bg-gray-100 text-gray-800 text-xs font-mono font-bold px-3 py-2 rounded-lg border border-gray-300 select-all hover:bg-gray-200 transition-colors cursor-pointer">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                    {{ $room->code }}
                                                </span>
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-semibold text-gray-900">
                                                    {{ $room->start_datetime->format('d M Y') }}</div>
                                                <div class="text-xs text-gray-500">{{ $room->start_datetime->format('H:i') }} WIB
                                                </div>
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center gap-2">
                                                    <div class="flex -space-x-2">
                                                        @foreach($room->participants->take(3) as $participant)
                                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($participant->user->name) }}&background=10b981&color=fff"
                                                                class="w-8 h-8 rounded-full border-2 border-white"
                                                                title="{{ $participant->user->name }}">
                                                        @endforeach
                                                    </div>
                                                    <span class="text-sm font-bold text-gray-700">
                                                        {{ $room->participants->count() }}/{{ $room->max_participants }}
                                                    </span>
                                                </div>
                                            </td>

                                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                                <div class="flex items-center justify-end gap-3">
                                                    <a href="{{ route('rooms.show', $room) }}"
                                                        class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-lg transition"
                                                        title="Lihat">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                    <a href="{{ route('rooms.edit', $room) }}"
                                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                                        title="Edit">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                    <form action="{{ route('rooms.destroy', $room) }}" method="POST" class="inline"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus room ini? Tindakan ini tidak dapat dibatalkan.');">
                                                        @csrf @method('DELETE')
                                                        <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                                                            title="Hapus">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-12 text-center">
                                                <div class="flex flex-col items-center justify-center">
                                                    <div
                                                        class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                    <p class="text-gray-500 font-semibold mb-2">Belum ada room yang dibuat</p>
                                                    <a href="{{ route('rooms.create') }}"
                                                        class="text-emerald-600 hover:text-emerald-700 font-bold text-sm">
                                                        Buat room pertama Anda +
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- 2. TAMPILAN MOBILE (CARD LIST) --}}
                    <div class="md:hidden space-y-4">
                        @forelse($myRooms as $room)
                            <div class="bg-white p-5 rounded-2xl shadow-lg border border-gray-100 transition hover:shadow-xl">
                                <div class="flex items-center justify-between mb-3 border-b pb-3 border-gray-100">
                                    <div class="flex items-center gap-3">
                                        {{-- Icon Olahraga --}}
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl flex items-center justify-center text-white font-bold shadow-md text-lg flex-shrink-0">
                                            @php $sportName = strtolower($room->sport->name); @endphp
                                            @if(str_contains($sportName, 'futsal') || str_contains($sportName, 'soccer') || str_contains($sportName, 'football'))
                                                ⚽
                                            @elseif(str_contains($sportName, 'badminton') || str_contains($sportName, 'bulu')) 🏸
                                            @elseif(str_contains($sportName, 'basket')) 🏀
                                            @elseif(str_contains($sportName, 'tennis') || str_contains($sportName, 'tenis')) 🎾
                                            @elseif(str_contains($sportName, 'volly') || str_contains($sportName, 'voli')) 🏐
                                            @elseif(str_contains($sportName, 'run') || str_contains($sportName, 'lari')) 🏃
                                            @elseif(str_contains($sportName, 'gym') || str_contains($sportName, 'fitness')) 🏋️
                                            @else 🏅
                                            @endif
                                        </div>
                                        {{-- Judul dan Sport --}}
                                        <div>
                                            <div class="text-base font-bold text-gray-900">{{ $room->title }}</div>
                                            <div class="text-xs text-gray-500">{{ $room->sport->name }}</div>
                                        </div>
                                    </div>
                                    {{-- Kode --}}
                                    <span
                                        class="bg-gray-100 text-gray-800 text-xs font-mono font-bold px-2 py-1 rounded-md border border-gray-300 select-all">
                                        {{ $room->code }}
                                    </span>
                                </div>

                                {{-- DETAIL WAKTU & PESERTA --}}
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <p class="text-xs text-gray-500 font-medium uppercase">Waktu Mulai</p>
                                        <p class="text-sm font-semibold text-gray-900">{{ $room->start_datetime->format('d M Y') }}
                                        </p>
                                        <p class="text-xs text-gray-500">{{ $room->start_datetime->format('H:i') }} WIB</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 font-medium uppercase">Peserta</p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <div class="flex -space-x-1">
                                                @foreach($room->participants->take(3) as $participant)
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($participant->user->name) }}&background=10b981&color=fff"
                                                        class="w-6 h-6 rounded-full border border-white"
                                                        title="{{ $participant->user->name }}">
                                                @endforeach
                                            </div>
                                            <span class="text-sm font-bold text-gray-700">
                                                {{ $room->participants->count() }}/{{ $room->max_participants }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- AKSI --}}
                                <div class="flex justify-end gap-2 border-t pt-3 border-gray-100">
                                    <a href="{{ route('rooms.show', $room) }}"
                                        class="flex-1 text-center py-2 text-sm font-bold text-emerald-600 border border-emerald-600 rounded-lg hover:bg-emerald-50 transition">
                                        Lihat
                                    </a>
                                    <a href="{{ route('rooms.edit', $room) }}"
                                        class="flex-1 text-center py-2 text-sm font-bold text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-50 transition">
                                        Edit
                                    </a>
                                    <form action="{{ route('rooms.destroy', $room) }}" method="POST" class="inline flex-1"
                                        onsubmit="return confirm('Hapus room ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="w-full py-2 text-sm font-bold text-red-600 border border-red-600 rounded-lg hover:bg-red-50 transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center p-10 bg-white rounded-3xl border border-gray-200">
                                <p class="text-gray-500">Belum ada room yang dibuat. Ayo buat room pertama Anda!</p>
                                <a href="{{ route('rooms.create') }}"
                                    class="mt-4 inline-block text-emerald-600 font-bold text-sm hover:text-emerald-700">
                                    Buat Room Baru +
                                </a>
                            </div>
                        @endforelse
                    </div>

                </div>

                {{-- SCRIPT CHART JS (Ditempatkan di bagian bawah content) --}}
                <script>
                    const ctx = document.getElementById('activityChart');
                    new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Hosted', 'Joined'],
                            datasets: [{
                                data: [{{ $hostedCount }}, {{ $joinedCount }}],
                                backgroundColor: [
                                    'rgb(16, 185, 129)', // Tailwind emerald-500/600
                                    'rgb(59, 130, 246)' // Tailwind blue-500
                                ],
                                borderWidth: 0,
                                hoverOffset: 10
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        padding: 20,
                                        font: {
                                            size: 14,
                                            weight: 'bold'
                                        }
                                    }
                                }
                            }
                        }
                    });
                </script>

            @endif
        </div>
    </div>

    {{-- CSS untuk Animasi (Bisa dipindahkan ke app.css jika ingin permanen) --}}
    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.6s ease-out;
        }
    </style>

@endsection