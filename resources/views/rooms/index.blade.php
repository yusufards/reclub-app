@extends('layouts.app')
@section('title', request()->routeIs('cari.mabar') ? 'Cari Lokasi Mabar | SportClub' : 'Explore | SportClub')

@push('meta-tags')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .leaflet-popup-content-wrapper { border-radius: 12px; padding: 0; overflow: hidden; }
        .leaflet-popup-content { margin: 0; width: 220px !important; }
    </style>
@endpush

@section('content')

    {{-- =======================================================================
         1. HERO SECTION (HANYA DI HOME / EXPLORE)
         ======================================================================= --}}
    @if(!request()->routeIs('cari.mabar'))
    <div class="relative bg-emerald-900 overflow-hidden mb-10">
        <div class="absolute inset-0 opacity-20 pointer-events-none">
            <svg class="h-full w-full" width="100%" height="100%" viewBox="0 0 800 800">
                <circle cx="400" cy="400" fill="none" r="200" stroke-width="50" stroke="#10b981" opacity="0.5"></circle>
                <circle cx="400" cy="400" fill="none" r="350" stroke-width="30" stroke="#34d399" opacity="0.3"></circle>
            </svg>
        </div>
        <div class="relative max-w-7xl mx-auto py-20 px-4 sm:px-6 lg:px-8 text-center md:text-left">
            <h1 class="text-4xl tracking-tight font-extrabold text-white sm:text-5xl md:text-6xl mb-4">
                Cari Lawan Main?
            </h1>
            <p class="mt-2 max-w-2xl text-base text-emerald-100 sm:text-lg mb-8 mx-auto md:mx-0">
                Temukan komunitas olahraga, booking lapangan, dan main bareng sekarang.
            </p>
            @if(!auth()->check() || (auth()->check() && !auth()->user()->isAdmin()))
                <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                    <a href="{{ route('rooms.create') }}" class="px-8 py-3 bg-white text-emerald-800 font-bold rounded-full shadow-lg hover:bg-emerald-50 transition transform hover:-translate-y-1 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Buat Room Baru
                    </a>
                </div>
            @endif
        </div>
    </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 {{ request()->routeIs('cari.mabar') ? 'py-8' : '-mt-20 relative z-10' }}">

        {{-- =======================================================================
             2. FITUR CARI ROOM VIA KODE (HANYA DI HALAMAN CARI MABAR)
             ======================================================================= --}}
        @if(request()->routeIs('cari.mabar'))
        <div class="bg-gradient-to-r from-emerald-600 to-teal-500 rounded-3xl shadow-lg p-6 mb-8 text-white flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 -mr-10 -mt-10 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl pointer-events-none"></div>
            
            <div class="relative z-10">
                <h2 class="text-xl font-bold flex items-center gap-2">
                    <svg class="w-6 h-6 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                    Punya Kode Undangan?
                </h2>
                <p class="text-emerald-100 text-sm mt-1">Masukan 6 digit kode unik dari temanmu untuk langsung bergabung.</p>
            </div>

            <form action="{{ route('rooms.join_code') }}" method="POST" class="flex w-full md:w-auto gap-2 relative z-10">
                @csrf
                <input type="text" name="code" placeholder="CONTOH: X7K9LP" class="w-full md:w-48 px-4 py-3 rounded-xl text-gray-800 font-bold uppercase tracking-widest focus:outline-none focus:ring-4 focus:ring-emerald-300 shadow-md placeholder-gray-400" required maxlength="6">
                <button class="bg-gray-900 hover:bg-black text-white font-bold px-6 py-3 rounded-xl shadow-md transition transform active:scale-95 flex items-center gap-2">
                    GABUNG
                </button>
            </form>
        </div>
        @endif

        {{-- =======================================================================
             3. SEARCH BAR & FILTER
             ======================================================================= --}}
        <div class="bg-white p-4 rounded-3xl shadow-xl border border-gray-100 mb-8">
            <form action="{{ request()->routeIs('cari.mabar') ? route('cari.mabar') : route('home') }}" method="GET" id="searchForm" class="flex flex-col md:flex-row gap-4 items-center w-full">
                <input type="hidden" name="lat" id="lat" value="{{ request('lat') }}">
                <input type="hidden" name="lng" id="lng" value="{{ request('lng') }}">

                {{-- Search Input --}}
                <div class="w-full relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400 group-focus-within:text-emerald-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" placeholder="Cari lokasi atau nama lapangan..." value="{{ request('search') }}"
                        class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition bg-gray-50 focus:bg-white">
                </div>

                {{-- Sport Filter --}}
                {{-- Custom Sport Filter (Alpine JS) --}}
                <div class="w-full md:w-1/4 relative min-w-0" x-data="{ open: false, selected: '{{ request('sport_id') }}', label: '{{ $sports->firstWhere('id', request('sport_id'))->name ?? 'Semua Olahraga' }}' }">
                    <input type="hidden" name="sport_id" :value="selected">
                    
                    <button type="button" @click="open = !open" @click.outside="open = false" 
                        class="w-full pl-4 pr-10 py-3 rounded-xl border border-gray-200 bg-gray-50 text-left focus:outline-none focus:ring-2 focus:ring-emerald-200 transition flex items-center justify-between truncate">
                        <span x-text="label" class="block truncate text-gray-700"></span>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </button>

                    <div x-show="open" x-transition 
                         class="absolute z-50 mt-1 w-full bg-white rounded-xl shadow-2xl max-h-60 overflow-auto border border-gray-100 p-1">
                        <div @click="selected = ''; label = 'Semua Olahraga'; open = false" 
                             class="cursor-pointer px-4 py-2 hover:bg-emerald-50 hover:text-emerald-700 rounded-lg text-sm text-gray-600 transition">
                            Semua Olahraga
                        </div>
                        @foreach($sports as $s)
                            <div @click="selected = '{{ $s->id }}'; label = '{{ $s->name }}'; open = false" 
                                 class="cursor-pointer px-4 py-2 hover:bg-emerald-50 hover:text-emerald-700 rounded-lg text-sm text-gray-600 transition truncate">
                                {{ $s->name }}
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- City Filter --}}
                {{-- Custom City Filter (Alpine JS) --}}
                <div class="w-full md:w-1/4 relative min-w-0" x-data="{ open: false, selected: '{{ request('city') }}', label: '{{ request('city') ?: 'Semua Kota' }}' }">
                    <input type="hidden" name="city" :value="selected">
                    
                    <button type="button" @click="open = !open" @click.outside="open = false" 
                        class="w-full pl-4 pr-10 py-3 rounded-xl border border-gray-200 bg-gray-50 text-left focus:outline-none focus:ring-2 focus:ring-emerald-200 transition flex items-center justify-between truncate">
                        <span x-text="label" class="block truncate text-gray-700"></span>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </button>

                    <div x-show="open" x-transition 
                         class="absolute z-50 mt-1 w-full bg-white rounded-xl shadow-2xl max-h-60 overflow-auto border border-gray-100 p-1">
                        <div @click="selected = ''; label = 'Semua Kota'; open = false" 
                             class="cursor-pointer px-4 py-2 hover:bg-emerald-50 hover:text-emerald-700 rounded-lg text-sm text-gray-600 transition">
                            Semua Kota
                        </div>
                        @foreach($cities as $c)
                            <div @click="selected = '{{ $c }}'; label = '{{ $c }}'; open = false" 
                                 class="cursor-pointer px-4 py-2 hover:bg-emerald-50 hover:text-emerald-700 rounded-lg text-sm text-gray-600 transition truncate">
                                {{ $c }}
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex gap-2 w-full md:w-auto">
                    <button type="button" onclick="getLocation()" class="bg-emerald-50 text-emerald-600 hover:bg-emerald-100 font-bold py-3 px-4 rounded-xl transition border border-emerald-100" title="Cari Sekitar Saya">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </button>
                    <button type="submit" class="flex-1 bg-gray-900 hover:bg-emerald-600 text-white font-bold py-3 px-8 rounded-xl transition shadow-lg">Cari</button>
                </div>
            </form>
        </div>

        {{-- =======================================================================
             4. TAMPILAN KHUSUS "CARI MABAR" (PETA & DAFTAR VENUE)
             ======================================================================= --}}
        @if(request()->routeIs('cari.mabar') && isset($venues))
            
            {{-- PETA INTERAKTIF --}}
            <div class="mb-10 bg-white p-2 rounded-3xl shadow-lg border border-gray-200 animate-fade-in-down">
                <div id="map" class="w-full h-[450px] rounded-2xl z-0"></div>
                <div class="p-3 flex justify-between items-center text-sm text-gray-500 px-4">
                    <span>Menampilkan <b>{{ $venues->count() }}</b> lokasi lapangan dengan room aktif.</span>
                    <span class="text-xs bg-gray-100 px-2 py-1 rounded">Klik marker untuk detail</span>
                </div>
            </div>

            {{-- LIST VENUE (YANG PUNYA ROOM AKTIF) --}}
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                    <span class="w-1.5 h-8 bg-emerald-500 rounded-full"></span>
                    Lokasi Tersedia
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                @forelse($venues as $venue)
                    <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col md:flex-row h-full">
                        {{-- Gambar Venue (DIPERJELAS & TANPA OVERLAY GELAP) --}}
                        <div class="w-full md:w-1/3 h-56 md:h-auto relative bg-gray-100">
                            {{-- Placeholder jika tidak ada gambar --}}
                            <img src="{{ $venue->image ? asset('storage/' . $venue->image) : 'https://placehold.co/400x400/065f46/ffffff?text=' . urlencode($venue->name) }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                 alt="{{ $venue->name }}">
                        </div>
                        
                        {{-- Info Venue --}}
                        <div class="p-6 flex flex-col justify-between w-full md:w-2/3">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-emerald-600 transition">{{ $venue->name }}</h3>
                                
                                {{-- LOKASI (KLIK KE MAPS) --}}
                                <a href="https://maps.google.com/?q={{ $venue->latitude }},{{ $venue->longitude }}" target="_blank" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-blue-600 hover:underline transition mb-4 group/map">
                                    <svg class="w-4 h-4 text-emerald-500 group-hover/map:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ $venue->city }} 
                                    @if(isset($venue->distance)) 
                                        <span class="font-bold text-emerald-600 group-hover/map:text-blue-600">({{ number_format($venue->distance, 1) }} km)</span> 
                                    @endif
                                    <svg class="w-3 h-3 text-gray-400 group-hover/map:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                </a>
                                
                                {{-- Info Ringkas --}}
                                <p class="text-xs text-gray-400 leading-relaxed mb-4">
                                    Tersedia <strong>{{ $venue->activeRooms->count() }}</strong> jadwal mabar aktif (mendatang) di lokasi ini. 
                                    Klik tombol di bawah untuk memilih jadwal.
                                </p>
                            </div>

                            <a href="{{ route('venues.rooms', $venue->id) }}" class="w-full bg-emerald-50 border border-emerald-200 text-emerald-700 hover:bg-emerald-600 hover:text-white font-bold py-3 rounded-xl transition text-center text-sm flex items-center justify-center gap-2 group/btn">
                                Lihat Semua Jadwal
                                <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-20 bg-white rounded-3xl border-2 border-dashed border-gray-200">
                        <h3 class="text-lg font-bold text-gray-700">Tidak ada lokasi ditemukan</h3>
                        <p class="text-gray-500 text-sm">Coba ubah filter atau lokasi pencarian Anda.</p>
                    </div>
                @endforelse
            </div>

        {{-- =======================================================================
             5. TAMPILAN STANDAR HOME (GRID ROOM LANGSUNG)
             ======================================================================= --}}
        @else
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                    <span class="w-1.5 h-8 bg-emerald-500 rounded-full"></span>
                    Jadwal Main Terbaru
                    <span class="text-sm font-normal text-gray-500 ml-2 bg-gray-100 px-2 py-1 rounded-lg">{{ $rooms->total() }} ditemukan</span>
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                @forelse($rooms as $room)
                    <div class="group bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col h-full">
                        {{-- Card Header Gradient --}}
                        <div class="h-28 bg-gradient-to-br from-emerald-500 to-teal-700 relative p-5 flex flex-col justify-between overflow-hidden">
                            <div class="absolute top-0 right-0 w-20 h-20 bg-white opacity-10 rounded-full blur-2xl -mr-10 -mt-10 pointer-events-none"></div>
                            <div class="flex justify-between items-start relative z-10">
                                <span class="bg-white/20 backdrop-blur text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider border border-white/10 shadow-sm">
                                    {{ $room->sport->name }}
                                </span>
                                <span class="text-white text-xs font-bold bg-black/20 px-2 py-1 rounded-lg backdrop-blur-sm border border-white/10">
                                    {{ $room->start_datetime->format('H:i') }} WIB
                                </span>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="p-6 pt-8 relative flex-grow flex flex-col">
                            <div class="absolute -top-8 left-6">
                                <img src="{{ $room->host->avatar ? asset('storage/' . $room->host->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($room->host->name) . '&background=ffffff&color=10b981&bold=true' }}" 
                                     class="w-16 h-16 rounded-2xl border-4 border-white shadow-md bg-white object-cover group-hover:scale-105 transition duration-300">
                            </div>
                            <div class="mb-1 ml-16 pl-2">
                                <p class="text-xs text-gray-400 font-bold uppercase">Host: {{ Str::limit($room->host->name, 15) }}</p>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 leading-snug mb-2 group-hover:text-emerald-600 transition line-clamp-2 min-h-[3.5rem]">
                                {{ $room->title }}
                            </h3>
                            <p class="text-xs text-gray-500 mb-5 flex items-center gap-1">
                                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ Str::limit($room->venue->name, 35) }}
                            </p>
                            
                            {{-- Footer Card --}}
                            <div class="mt-auto pt-4 border-t border-gray-100 flex justify-between items-center">
                                <div class="flex items-center gap-1.5 text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    <span class="text-xs font-bold">{{ $room->participants_count ?? $room->participants->count() }} / {{ $room->max_participants }}</span>
                                </div>
                                <a href="{{ route('rooms.show', $room) }}" class="text-sm font-bold text-emerald-600 hover:text-emerald-800 transition flex items-center gap-1 group/link">
                                    Detail <span aria-hidden="true" class="group-hover/link:translate-x-1 transition-transform">&rarr;</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-20 bg-white rounded-3xl border-2 border-dashed border-gray-200">
                        <h3 class="text-lg font-bold text-gray-700">Belum ada Room</h3>
                        <p class="text-gray-500 text-sm">Tidak ada jadwal yang sesuai filter Anda.</p>
                    </div>
                @endforelse
            </div>
            
            {{-- Pagination --}}
            @if($rooms->hasPages())
                <div class="mb-12">{{ $rooms->links() }}</div>
            @endif
        @endif

    </div>

    {{-- =======================================================================
         6. SCRIPT LEAFLET & GEOLOCATION (HANYA DI CARI MABAR)
         ======================================================================= --}}
    @if(request()->routeIs('cari.mabar') && isset($venues) && $venues->count() > 0)
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var map = L.map('map').setView([-6.200000, 106.816666], 10);
                
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap'
                }).addTo(map);

                var venues = @json($venues);
                var bounds = L.latLngBounds();

                venues.forEach(function(venue) {
                    if(venue.latitude && venue.longitude) {
                        var marker = L.marker([venue.latitude, venue.longitude]).addTo(map);
                        var popupContent = `
                            <div class="text-center font-sans p-3">
                                <b class="block text-sm text-gray-800 mb-1">${venue.name}</b>
                                <span class="text-xs text-gray-500 block mb-2">${venue.city}</span>
                                <a href="/venues/${venue.id}/rooms" class="inline-block bg-emerald-600 text-white text-[10px] font-bold px-3 py-1.5 rounded hover:bg-emerald-700 transition no-underline">
                                    Lihat Jadwal
                                </a>
                            </div>
                        `;
                        marker.bindPopup(popupContent);
                        bounds.extend([venue.latitude, venue.longitude]);
                    }
                });

                if(venues.length > 0) {
                    map.fitBounds(bounds, {padding: [50, 50]});
                }
            });
        </script>
    @endif

    <script>
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    document.getElementById('lat').value = position.coords.latitude;
                    document.getElementById('lng').value = position.coords.longitude;
                    document.getElementById('searchForm').submit();
                }, function(error) {
                    alert("Gagal mengambil lokasi. Pastikan GPS aktif.");
                });
            } else {
                alert("Browser tidak mendukung GPS.");
            }
        }
    </script>
@endsection