@extends('layouts.app')
@section('content')

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    @php
        // Menyimpan data input sebelumnya agar tidak hilang saat ganti venue
        $dataTitipan = request()->only([
            'title',
            'description',
            'start_datetime',
            'max_participants',
            'cost_per_person',
            'sport_id'
        ]);
    @endphp

    <div class="container mx-auto px-4 py-6">

        {{-- SEARCH CARD --}}
        <div class="bg-white p-4 md:p-6 rounded-3xl shadow-lg mb-6 border border-gray-100">
            <h2 class="text-lg md:text-xl font-bold mb-4 flex items-center text-gray-800">
                <svg class="w-6 h-6 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Cari Lokasi Lapangan
            </h2>

            <form id="searchForm" method="GET" action="{{ route('venues.search') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3 md:gap-4">

                {{-- Inject Data Titipan (Hidden) --}}
                @foreach($dataTitipan as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach

                <input type="hidden" name="lat" id="lat" value="{{ request('lat') }}">
                <input type="hidden" name="lng" id="lng" value="{{ request('lng') }}">

                {{-- Filter Kota --}}
                <div class="md:col-span-3">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Pilih Kota</label>
                    <div class="relative">
                        <select name="city" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-emerald-500 focus:border-emerald-500 appearance-none font-bold text-gray-700 cursor-pointer">
                            <option value="">Semua Kota</option>
                            @foreach($cities as $city)
                                <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                    {{ $city }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                {{-- Input Keyword --}}
                <div class="md:col-span-5">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama / Alamat</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="keyword" value="{{ request('keyword') }}" 
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-9 pr-4 py-2.5 text-sm focus:ring-emerald-500 focus:border-emerald-500 font-medium" 
                            placeholder="Contoh: Saparua, Futsal 35...">
                    </div>
                </div>

                {{-- Tombol Actions --}}
                <div class="md:col-span-2 flex items-end">
                    <button type="submit" class="w-full bg-gray-800 text-white py-2.5 rounded-xl hover:bg-black font-bold shadow-md transition text-sm flex justify-center items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        Cari
                    </button>
                </div>

                <div class="md:col-span-2 flex items-end">
                    <button type="button" onclick="getLocationAndSubmit()" class="w-full bg-emerald-600 text-white py-2.5 rounded-xl hover:bg-emerald-700 font-bold shadow-md transition text-sm flex justify-center items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Terdekat
                    </button>
                </div>
            </form>
        </div>

        {{-- MAIN CONTENT AREA (MAP & LIST) --}}
        <div class="flex flex-col lg:grid lg:grid-cols-3 gap-6 lg:h-[600px]">

            {{-- MAP CONTAINER --}}
            <div class="h-[350px] lg:h-full lg:col-span-2 bg-gray-100 rounded-3xl shadow-md overflow-hidden relative border border-gray-200 order-1">
                <div id="map" class="w-full h-full z-10"></div>
                <div id="loadingMap" class="absolute inset-0 bg-white/80 backdrop-blur-sm flex flex-col items-center justify-center z-20 hidden">
                    <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-emerald-600 mb-3"></div>
                    <span class="text-emerald-800 font-bold text-sm">Mendeteksi Lokasi...</span>
                </div>
            </div>

            {{-- LIST CONTAINER --}}
            <div class="h-[450px] lg:h-full bg-white rounded-3xl shadow-md overflow-hidden flex flex-col border border-gray-200 order-2">
                <div class="p-4 bg-gray-50 border-b flex justify-between items-center shrink-0">
                    <h3 class="font-bold text-gray-800">Daftar Lapangan</h3>
                    <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-3 py-1 rounded-full">{{ $venues->count() }} Lokasi</span>
                </div>

                <div class="overflow-y-auto p-4 space-y-4 flex-1">
                    @forelse($venues as $venue)
                        <div class="border border-gray-100 rounded-2xl p-4 hover:shadow-md hover:border-emerald-200 transition cursor-pointer bg-white group" 
                             onclick="focusOnMap({{ $venue->latitude }}, {{ $venue->longitude }})">

                            <div class="flex justify-between items-start">
                                <div class="w-full">
                                    <h4 class="font-bold text-gray-900 group-hover:text-emerald-600 transition text-sm md:text-base">{{ $venue->name }}</h4>
                                    <p class="text-xs text-gray-500 mb-2 truncate">{{ $venue->city }}</p>
                                </div>

                                @if(isset($venue->distance))
                                    <div class="shrink-0 ml-2 bg-emerald-50 text-emerald-700 text-[10px] px-2 py-1 rounded-lg font-bold border border-emerald-100 whitespace-nowrap">
                                        {{ number_format($venue->distance, 1) }} km
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center space-x-3 mt-1">
                                <div class="flex items-center bg-yellow-50 px-2 py-0.5 rounded-md border border-yellow-100">
                                    <span class="text-xs font-bold text-gray-700">★ {{ $venue->rating }}</span>
                                </div>
                                <div class="text-xs font-bold text-gray-600">
                                    Rp {{ number_format($venue->price_per_hour, 0, ',', '.') }}/jam
                                </div>
                            </div>

                            <p class="text-xs text-gray-400 mt-2 truncate">{{ $venue->address }}</p>

                            <button type="button" 
                                    onclick="selectVenue(event, {{ $venue->id }})"
                                    class="block mt-3 w-full bg-emerald-600 text-white hover:bg-emerald-700 py-2 rounded-xl text-xs md:text-sm font-bold transition shadow-sm">
                                Pilih Lapangan Ini
                            </button>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center h-full text-gray-400">
                            <svg class="w-12 h-12 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-sm">Tidak ada lapangan ditemukan.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        // --- 1. INITIAL DATA SETUP ---
        let defaultLat = -6.9175;
        let defaultLng = 107.6191;

        let map = L.map('map').setView([defaultLat, defaultLng], 13);
        const venues = @json($venues);
        const savedInputs = @json($dataTitipan); 
        const createRoomBaseUrl = "{{ route('rooms.create') }}"; 

        const userLat = "{{ request('lat') }}";
        const userLng = "{{ request('lng') }}";

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        // Custom Icons (Marker Warna Biru & Merah)
        const venueIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
        });
        const userIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
        });

        // --- 2. URL CONSTRUCTION ---
        function getFinalUrl(venueId) {
            const params = new URLSearchParams(savedInputs);
            params.set('venue_id', venueId);
            return `${createRoomBaseUrl}?${params.toString()}`;
        }

        function selectVenue(e, venueId) {
            if(e) e.stopPropagation(); 
            window.location.href = getFinalUrl(venueId);
        }

        // --- 3. RENDER MARKERS ---
        if (venues.length > 0) {
            let bounds = L.latLngBounds();
            venues.forEach(venue => {
                if(venue.latitude && venue.longitude) {
                    const finalUrl = getFinalUrl(venue.id);
                    let popupContent = `
                        <div class="p-1 min-w-[150px] font-sans text-center">
                            <h3 class="font-bold text-sm text-gray-900 mb-1">${venue.name}</h3>
                            <p class="text-xs text-gray-600 mb-2">Rp ${new Intl.NumberFormat('id-ID').format(venue.price_per_hour)}/jam</p>
                            <a href="${finalUrl}" class="inline-block bg-emerald-600 text-white text-[10px] font-bold py-1.5 px-3 rounded hover:bg-emerald-700 transition no-underline">
                                Pilih Ini
                            </a>
                        </div>
                    `;

                    let marker = L.marker([venue.latitude, venue.longitude], {icon: venueIcon})
                        .addTo(map)
                        .bindPopup(popupContent);

                    bounds.extend([venue.latitude, venue.longitude]);
                }
            });
            map.fitBounds(bounds, {padding: [50, 50]});
        }

        // --- 4. USER LOCATION LOGIC ---
        if(userLat && userLng) {
            L.marker([userLat, userLng], {icon: userIcon}).addTo(map).bindPopup("<b>Lokasi Anda</b>").openPopup();
        }

        function focusOnMap(lat, lng) {
            map.flyTo([lat, lng], 16);
            if(window.innerWidth < 1024) {
                document.getElementById('map').scrollIntoView({behavior: 'smooth'});
            }
        }

        function getLocationAndSubmit() {
            const loading = document.getElementById('loadingMap');
            loading.classList.remove('hidden');
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (p) => {
                        document.getElementById('lat').value = p.coords.latitude;
                        document.getElementById('lng').value = p.coords.longitude;
                        document.getElementById('searchForm').submit();
                    },
                    (e) => {
                        loading.classList.add('hidden');
                        alert("Gagal ambil lokasi. Pastikan GPS aktif.");
                    }
                );
            } else {
                loading.classList.add('hidden');
                alert("Browser tidak support GPS.");
            }
        }
    </script>
@endsection