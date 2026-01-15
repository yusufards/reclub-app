@extends('layouts.app')

@section('title', 'Room di ' . $venue->name . ' | SportClub')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- 1. BREADCRUMB (Navigasi Balik) --}}
        <div class="mb-6">
            <a href="{{ route('cari.mabar') }}"
                class="text-emerald-600 hover:text-emerald-800 text-sm font-medium flex items-center gap-1 transition group">
                <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Kembali ke Peta Pencarian
            </a>
        </div>

        {{-- 2. HEADER VENUE INFO (Detail Lapangan) --}}
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden mb-10">
            {{-- Bagian Foto & Nama --}}
            <div class="relative h-48 md:h-64 bg-gray-200 group">
                {{-- Foto Venue --}}
                <img src="{{ $venue->image ? asset('storage/' . $venue->image) : 'https://placehold.co/1200x400/065f46/ffffff?text=' . urlencode($venue->name) }}"
                    class="w-full h-full object-cover brightness-75 group-hover:brightness-90 transition duration-500"
                    alt="{{ $venue->name }}">

                {{-- Overlay Text --}}
                <div
                    class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent flex flex-col justify-end p-6 md:p-10">
                    <h1 class="text-3xl md:text-4xl font-black text-white mb-2 shadow-sm">{{ $venue->name }}</h1>
                    <p class="text-white/90 text-sm md:text-base flex items-center gap-2 font-medium">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ $venue->address }}, {{ $venue->city }}
                    </p>
                </div>
            </div>

            {{-- Bagian Statistik Venue --}}
            <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-gray-100 border-b border-gray-100">
                {{-- Stat 1: Jumlah Room --}}
                <div class="p-4 text-center hover:bg-gray-50 transition cursor-default">
                    <span class="block text-2xl font-bold text-gray-800">{{ $rooms->total() }}</span>
                    <span class="text-xs text-gray-500 uppercase font-bold tracking-wider">Room Aktif</span>
                </div>

                {{-- Stat 2: Jenis Olahraga --}}
                <div class="p-4 text-center hover:bg-gray-50 transition cursor-default">
                    <span class="block text-2xl font-bold text-gray-800">{{ $rooms->unique('sport_id')->count() }}</span>
                    <span class="text-xs text-gray-500 uppercase font-bold tracking-wider">Jenis Olahraga</span>
                </div>

                {{-- Stat 3: Rating (Static Placeholder) --}}
                <div class="p-4 text-center hover:bg-gray-50 transition cursor-default">
                    <span class="block text-2xl font-bold text-gray-800 flex items-center justify-center gap-1">
                        4.8 <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </span>
                    <span class="text-xs text-gray-500 uppercase font-bold tracking-wider">Rating Venue</span>
                </div>

                {{-- Stat 4: Tombol Peta --}}
                <div class="p-4 text-center hover:bg-blue-50 transition bg-gray-50">
                    <a href="https://www.google.com/maps/search/?api=1&query={{ $venue->latitude }},{{ $venue->longitude }}"
                        target="_blank"
                        class="inline-flex flex-col items-center justify-center h-full w-full text-blue-600 hover:text-blue-800 transition group">
                        <svg class="w-6 h-6 mb-1 group-hover:scale-110 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0121 18.382V7.618a1 1 0 00-.553-.894L15 7m0 13V7">
                            </path>
                        </svg>
                        <span class="text-xs font-bold">Buka di Maps</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- 3. DAFTAR ROOM (GRID) --}}
        <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
            <span class="w-1.5 h-8 bg-emerald-500 rounded-full"></span>
            Room Tersedia di Sini
        </h2>

        @if($rooms->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($rooms as $room)
                    <div
                        class="group bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col h-full">

                        {{-- CARD HEADER (Gradient & Badge) --}}
                        <div
                            class="h-28 bg-gradient-to-br from-emerald-500 to-teal-700 relative p-5 flex flex-col justify-between overflow-hidden">
                            {{-- Dekorasi Bulat Transparan --}}
                            <div
                                class="absolute top-0 right-0 w-20 h-20 bg-white opacity-10 rounded-full blur-2xl -mr-10 -mt-10 pointer-events-none">
                            </div>

                            <div class="flex justify-between items-start relative z-10">
                                {{-- Badge Olahraga --}}
                                <span
                                    class="bg-white/20 backdrop-blur text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider border border-white/10 shadow-sm">
                                    {{ $room->sport->name }}
                                </span>

                                {{-- Badge Status/Waktu --}}
                                @if($room->participants_count >= $room->max_participants)
                                    <span
                                        class="bg-red-500/80 text-white text-[10px] font-bold px-2 py-1 rounded backdrop-blur-sm shadow-sm">PENUH</span>
                                @else
                                    <span
                                        class="text-white text-xs font-bold bg-black/20 px-2 py-1 rounded-lg backdrop-blur-sm border border-white/10">
                                        {{ $room->start_datetime->format('H:i') }} WIB
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- CARD BODY --}}
                        <div class="p-6 pt-8 relative flex-grow flex flex-col">
                            {{-- Host Avatar (Floating) --}}
                            <div class="absolute -top-8 left-6">
                                <img src="{{ $room->host->avatar ? asset('storage/' . $room->host->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($room->host->name) . '&background=ffffff&color=10b981&bold=true' }}"
                                    class="w-16 h-16 rounded-2xl border-4 border-white shadow-md bg-white object-cover group-hover:scale-105 transition duration-300"
                                    alt="{{ $room->host->name }}">
                            </div>

                            {{-- Nama Host --}}
                            <div class="mb-1">
                                <p class="text-xs text-gray-400 font-bold uppercase mb-1 ml-16 pl-2">Host:
                                    {{ Str::limit($room->host->name, 15) }}
                                </p>
                            </div>

                            {{-- Judul Room --}}
                            <h3
                                class="text-lg font-bold text-gray-900 leading-snug mb-4 group-hover:text-emerald-600 transition line-clamp-2 min-h-[3.5rem]">
                                {{ $room->title }}
                            </h3>

                            {{-- Grid Tanggal & Biaya --}}
                            <div class="grid grid-cols-2 gap-3 mb-5">
                                <div
                                    class="bg-gray-50 p-2.5 rounded-xl border border-gray-100 flex flex-col justify-center text-center">
                                    <span class="text-[10px] text-gray-400 uppercase font-bold">Tanggal</span>
                                    <div class="text-sm font-bold text-gray-800">{{ $room->start_datetime->format('d M') }}</div>
                                </div>
                                <div
                                    class="bg-gray-50 p-2.5 rounded-xl border border-gray-100 flex flex-col justify-center text-center">
                                    <span class="text-[10px] text-gray-400 uppercase font-bold">Biaya</span>
                                    <div class="text-sm font-bold text-gray-800">
                                        @if($room->cost_per_person > 0)
                                            Rp {{ number_format($room->cost_per_person / 1000, 0) }}rb
                                        @else
                                            Gratis
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Footer Card --}}
                            <div class="mt-auto pt-4 border-t border-gray-100 flex justify-between items-center">
                                {{-- Jumlah Peserta --}}
                                <div class="flex items-center gap-1.5 text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                    <span class="text-xs font-bold">
                                        {{ $room->participants_count ?? $room->participants->count() }} /
                                        {{ $room->max_participants }}
                                    </span>
                                </div>

                                {{-- Link Detail --}}
                                <a href="{{ route('rooms.show', $room) }}"
                                    class="text-sm font-bold text-emerald-600 hover:text-emerald-800 transition flex items-center gap-1 group/link">
                                    Detail
                                    <span aria-hidden="true" class="group-hover/link:translate-x-1 transition-transform">→</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- 4. PAGINATION --}}
            <div class="mt-10">
                {{ $rooms->links() }}
            </div>

        @else
            {{-- 5. EMPTY STATE (Jika tidak ada room) --}}
            <div class="text-center py-20 bg-white rounded-3xl border-2 border-dashed border-gray-200">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">📭</div>
                <h3 class="text-lg font-bold text-gray-700">Belum ada Room</h3>
                <p class="text-gray-500 text-sm mb-6 max-w-sm mx-auto">Belum ada jadwal main yang dibuat di lapangan ini untuk
                    saat ini.</p>

                {{-- Tombol Buat Room --}}
                <a href="{{ route('rooms.create', ['venue_id' => $venue->id]) }}"
                    class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-6 rounded-xl transition shadow-lg text-sm transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Buat Room di Sini
                </a>
            </div>
        @endif

    </div>
@endsection