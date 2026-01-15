@extends('layouts.app')

@section('title', 'Riwayat Aktivitas | SportClub')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Riwayat Aktivitas</h1>
                <p class="text-gray-500 mt-1">Jejak keringat dan kenangan mabar Anda yang telah berlalu.</p>
            </div>
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center gap-2 text-sm font-bold text-emerald-600 bg-emerald-50 hover:bg-emerald-100 px-4 py-2.5 rounded-xl transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Kembali ke Dashboard
            </a>
        </div>

        @if($histories->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($histories as $room)
                    <div
                        class="group bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col h-full opacity-90 hover:opacity-100">

                        {{-- CARD HEADER (Status Selesai) --}}
                        <div class="bg-gray-100 p-5 flex justify-between items-center relative overflow-hidden">
                            {{-- Dekorasi background --}}
                            <div class="absolute top-0 right-0 w-20 h-20 bg-gray-200 rounded-full blur-2xl -mr-10 -mt-10"></div>

                            <div class="relative z-10 flex items-center gap-2">
                                <span
                                    class="bg-white text-gray-600 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider border border-gray-200 shadow-sm">
                                    {{ $room->sport->name }}
                                </span>
                            </div>
                            <span class="relative z-10 text-xs font-bold text-gray-500 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                {{ $room->start_datetime->format('d M Y') }}
                            </span>
                        </div>

                        {{-- CARD BODY --}}
                        <div class="p-6 flex flex-col flex-grow">
                            <h3
                                class="font-bold text-gray-800 text-lg mb-2 line-clamp-2 leading-tight group-hover:text-emerald-700 transition">
                                {{ $room->title }}
                            </h3>

                            <div class="flex items-start gap-1.5 text-xs text-gray-500 mb-6">
                                <svg class="w-4 h-4 text-gray-400 mt-0.5 shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="line-clamp-1">{{ $room->venue->name }}</span>
                            </div>

                            <div class="mt-auto pt-5 border-t border-gray-50 flex items-center justify-between">
                                {{-- Host Info --}}
                                <div class="flex items-center gap-3">
                                    <div class="relative">
                                        <img src="{{ $room->host->avatar ? asset('storage/' . $room->host->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($room->host->name) . '&background=f3f4f6&color=6b7280' }}"
                                            class="w-9 h-9 rounded-full border border-gray-200 object-cover grayscale group-hover:grayscale-0 transition duration-500">
                                        @if($room->host_id == auth()->id())
                                            <div
                                                class="absolute -bottom-1 -right-1 bg-emerald-500 text-white text-[8px] font-bold px-1.5 py-0.5 rounded border border-white">
                                                YOU</div>
                                        @endif
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[10px] uppercase text-gray-400 font-bold tracking-wider">Host</span>
                                        <span class="text-xs font-bold text-gray-700">
                                            {{ $room->host_id == auth()->id() ? 'Anda Sendiri' : Str::limit($room->host->name, 12) }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Action Button --}}
                                <a href="{{ route('rooms.show', $room) }}"
                                    class="text-xs font-bold text-gray-400 hover:text-emerald-600 bg-gray-50 hover:bg-emerald-50 px-4 py-2 rounded-lg transition group/btn flex items-center gap-1">
                                    Review
                                    <svg class="w-3 h-3 group-hover/btn:translate-x-0.5 transition-transform" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                        </path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-10">
                {{ $histories->links() }}
            </div>
        @else
            {{-- EMPTY STATE --}}
            <div
                class="flex flex-col items-center justify-center py-24 bg-white rounded-3xl border border-dashed border-gray-200 text-center">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-6 shadow-inner">
                    <span class="text-4xl grayscale opacity-50">🕰️</span>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Belum ada riwayat</h3>
                <p class="text-gray-500 max-w-sm mx-auto mb-8">Aktivitas mabar yang sudah selesai atau kadaluarsa akan muncul di
                    halaman ini sebagai kenangan.</p>
                <a href="{{ route('cari.mabar') }}"
                    class="bg-gray-900 hover:bg-black text-white font-bold py-3 px-6 rounded-xl transition shadow-lg text-sm">
                    Cari Mabar Baru
                </a>
            </div>
        @endif

    </div>
@endsection