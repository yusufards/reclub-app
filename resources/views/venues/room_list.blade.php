@extends('layouts.app')
@section('title', 'Jadwal Main di ' . $venue->name)

@section('content')

    {{-- HEADER VENUE (GAMBAR & INFO) --}}
    <div class="relative bg-gray-900 text-white overflow-hidden shadow-xl mb-10">
        {{-- Background Image dengan Overlay --}}
        <div class="absolute inset-0">
            <img src="{{ $venue->image ? asset('storage/' . $venue->image) : 'https://placehold.co/1200x400/065f46/ffffff?text=' . urlencode($venue->name) }}"
                class="w-full h-full object-cover opacity-40">
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/60 to-transparent"></div>
        </div>

        <div
            class="relative max-w-7xl mx-auto px-6 py-16 md:py-24 flex flex-col md:flex-row items-end justify-between gap-6">
            <div class="w-full md:w-2/3">
                <a href="{{ route('cari.mabar') }}"
                    class="inline-flex items-center text-emerald-300 hover:text-white text-sm font-bold mb-4 transition gap-1 group">
                    <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Peta
                </a>
                <h1 class="text-3xl md:text-5xl font-black tracking-tight mb-3 leading-tight">{{ $venue->name }}</h1>
                <div class="flex flex-wrap items-center gap-4 text-gray-300 text-sm md:text-base">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ $venue->city }}
                    </span>
                    <span class="hidden md:inline text-gray-600">•</span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                        Rp {{ number_format($venue->price_per_hour, 0, ',', '.') }}/jam
                    </span>
                </div>
                <p class="mt-4 text-gray-400 text-sm max-w-2xl leading-relaxed">{{ $venue->address }}</p>
            </div>

            {{-- Stat Box --}}
            <div class="flex gap-4">
                <div
                    class="bg-white/10 backdrop-blur-md px-5 py-3 rounded-2xl border border-white/10 text-center min-w-[100px]">
                    <span class="block text-[10px] text-gray-400 uppercase tracking-widest font-bold">Room Aktif</span>
                    <span class="text-3xl font-black text-emerald-400">{{ $rooms->count() }}</span>
                </div>
                <div
                    class="bg-white/10 backdrop-blur-md px-5 py-3 rounded-2xl border border-white/10 text-center min-w-[100px]">
                    <span class="block text-[10px] text-gray-400 uppercase tracking-widest font-bold">Rating</span>
                    <span class="text-3xl font-black text-yellow-400 flex justify-center items-center gap-1">
                        {{ $venue->rating }} <span class="text-sm text-yellow-400/50">★</span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">

        @if($rooms->count() > 0)
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                    <span class="w-1.5 h-8 bg-emerald-500 rounded-full"></span>
                    Jadwal Main Tersedia
                </h2>

                {{-- Tombol Buat Room (Floating di Mobile) --}}
                <a href="{{ route('rooms.create', ['venue_id' => $venue->id]) }}"
                    class="hidden md:flex items-center gap-2 bg-gray-900 hover:bg-emerald-600 text-white px-5 py-2.5 rounded-xl font-bold transition shadow-lg text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Buat Room Baru
                </a>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($rooms as $room)
                <div
                    class="group bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col h-full">

                    {{-- HEADER KARTU (Gradient) --}}
                    <div class="h-28 bg-gradient-to-br from-slate-800 to-gray-900 relative p-5">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-white opacity-5 rounded-full blur-2xl -mr-10 -mt-10">
                        </div>

                        <div class="flex justify-between items-start relative z-10">
                            <span
                                class="bg-white/10 backdrop-blur-md text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider border border-white/10">
                                {{ $room->sport->name }}
                            </span>
                            <span
                                class="text-emerald-400 text-xs font-bold bg-emerald-400/10 px-2 py-1 rounded-lg border border-emerald-400/20">
                                {{ $room->start_datetime->format('H:i') }} WIB
                            </span>
                        </div>
                    </div>

                    {{-- BODY KARTU --}}
                    <div class="p-6 pt-8 relative flex-grow flex flex-col">
                        {{-- Avatar Host --}}
                        <div class="absolute -top-8 left-6">
                            <img src="{{ $room->host->avatar ? asset('storage/' . $room->host->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($room->host->name) . '&background=10b981&color=fff' }}"
                                class="w-16 h-16 rounded-2xl border-4 border-white shadow-md bg-white object-cover group-hover:scale-105 transition duration-300"
                                title="Host: {{ $room->host->name }}">
                        </div>

                        <div class="mb-1 ml-16 pl-2">
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Host:
                                {{ Str::limit($room->host->name, 15) }}
                            </p>
                        </div>

                        <h3
                            class="text-lg font-bold text-gray-900 leading-snug mb-4 group-hover:text-emerald-600 transition min-h-[3.5rem] line-clamp-2">
                            {{ $room->title }}
                        </h3>

                        {{-- Info Grid --}}
                        <div class="grid grid-cols-2 gap-3 mb-6">
                            <div
                                class="bg-gray-50 p-3 rounded-xl border border-gray-100 flex flex-col justify-center text-center group-hover:border-emerald-100 transition">
                                <span class="text-[10px] text-gray-400 uppercase font-bold">Tanggal</span>
                                <div class="text-sm font-bold text-gray-800">{{ $room->start_datetime->format('d M') }}</div>
                            </div>
                            <div
                                class="bg-gray-50 p-3 rounded-xl border border-gray-100 flex flex-col justify-center text-center group-hover:border-emerald-100 transition">
                                <span class="text-[10px] text-gray-400 uppercase font-bold">Biaya/Org</span>
                                <div class="text-sm font-bold text-gray-800">
                                    {{ $room->cost_per_person > 0 ? 'Rp ' . number_format($room->cost_per_person / 1000, 0) . 'rb' : 'Gratis' }}
                                </div>
                            </div>
                        </div>

                        {{-- Progress Bar Peserta --}}
                        <div class="mb-6">
                            <div class="flex justify-between text-xs mb-1.5 font-bold">
                                <span
                                    class="{{ $room->participants_count >= $room->max_participants ? 'text-red-500' : 'text-emerald-600' }}">
                                    {{ $room->participants_count }} Terisi
                                </span>
                                <span class="text-gray-400">Max {{ $room->max_participants }}</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-500 {{ $room->participants_count >= $room->max_participants ? 'bg-red-500' : 'bg-emerald-500' }}"
                                    style="width: {{ ($room->participants_count / $room->max_participants) * 100 }}%">
                                </div>
                            </div>
                        </div>

                        {{-- Action Button --}}
                        <div class="mt-auto">
                            @if($room->participants_count >= $room->max_participants)
                                <button disabled
                                    class="w-full bg-gray-100 text-gray-400 font-bold py-3 rounded-xl cursor-not-allowed border border-gray-200 text-sm">
                                    Slot Penuh
                                </button>
                            @else
                                <a href="{{ route('rooms.show', $room) }}"
                                    class="flex items-center justify-center gap-2 w-full bg-gray-900 text-white hover:bg-emerald-600 font-bold py-3 rounded-xl transition shadow-lg transform active:scale-[0.98] text-sm group/btn">
                                    Gabung Sekarang
                                    <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                {{-- EMPTY STATE --}}
                <div
                    class="col-span-full flex flex-col items-center justify-center py-20 bg-white rounded-3xl border-2 border-dashed border-gray-200 text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Room Aktif</h3>
                    <p class="text-gray-500 text-sm max-w-md mx-auto mb-8">Saat ini belum ada jadwal main yang dibuat di
                        lapangan ini. Jadilah yang pertama!</p>

                    <a href="{{ route('rooms.create', ['venue_id' => $venue->id]) }}"
                        class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 px-8 rounded-full transition shadow-lg hover:shadow-emerald-200 transform hover:-translate-y-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Buat Room di Sini
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($rooms->hasPages())
            <div class="mt-12">
                {{ $rooms->links() }}
            </div>
        @endif

    </div>

    {{-- Floating Action Button (Mobile Only) --}}
    <a href="{{ route('rooms.create', ['venue_id' => $venue->id]) }}"
        class="md:hidden fixed bottom-6 right-6 bg-emerald-600 text-white w-14 h-14 rounded-full shadow-2xl flex items-center justify-center z-50 hover:bg-emerald-700 active:scale-90 transition">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
    </a>

@endsection