@extends('layouts.app')

@section('title', 'Buat Room Mabar Baru | SportClub')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
        
        {{-- HEADER CARD --}}
        <div class="px-8 py-10 bg-gradient-to-br from-emerald-600 to-teal-700 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-emerald-400 opacity-10 rounded-full blur-2xl"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-black tracking-tight">Buat Room Baru</h2>
                    <p class="text-emerald-100 mt-1 text-sm font-medium">Isi detail di bawah untuk mulai mabar seru!</p>
                </div>
                
                {{-- TOMBOL CARI LAPANGAN (Deep Linking) --}}
                <button type="button" onclick="cariLapanganDenganData()" class="group flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 px-5 py-2.5 rounded-2xl font-bold text-sm hover:bg-white/20 transition shadow-sm cursor-pointer">
                    <div class="bg-white/20 p-1.5 rounded-lg group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0121 18.382V7.618a1 1 0 00-.553-.894L15 7m0 13V7"></path></svg>
                    </div>
                    Cari Lapangan
                </button>
            </div>
        </div>

        <div class="p-8 lg:p-12">
            <form action="{{ route('rooms.store') }}" method="POST">
                @csrf
                
                {{-- BAGIAN 1: INFORMASI AKTIVITAS --}}
                <div class="mb-10">
                    <h3 class="text-emerald-900 font-bold text-lg mb-6 flex items-center gap-2">
                        <span class="bg-emerald-100 text-emerald-600 w-8 h-8 rounded-full flex items-center justify-center text-sm font-black">1</span>
                        Informasi Aktivitas
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        
                        {{-- Judul Room --}}
                        <div class="md:col-span-2">
                            <label for="title" class="block text-sm font-bold text-gray-700 mb-2">Judul Room <span class="text-red-500">*</span></label>
                            <input type="text" id="title" name="title" 
                                value="{{ old('title', request('title')) }}" 
                                class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 block px-4 py-3.5 transition placeholder-gray-400 font-medium" 
                                placeholder="Contoh: Mabar Futsal Santai Jumat Malam" required>
                            @error('title') <p class="text-red-500 text-xs mt-1 font-semibold ml-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Olahraga --}}
                        <div>
                            <label for="sport_id" class="block text-sm font-bold text-gray-700 mb-2">Cabang Olahraga <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select id="sport_id" name="sport_id" class="appearance-none w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 block px-4 py-3.5 transition cursor-pointer font-medium">
                                    <option value="" disabled {{ (!old('sport_id', request('sport_id'))) ? 'selected' : '' }} class="text-gray-400">Pilih Olahraga</option>
                                    @foreach($sports as $s) 
                                        <option value="{{ $s->id }}" {{ (old('sport_id', request('sport_id')) == $s->id) ? 'selected' : '' }}>{{ $s->name }}</option> 
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                            @error('sport_id') <p class="text-red-500 text-xs mt-1 font-semibold ml-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Lokasi Lapangan --}}
                        <div>
                            <label for="venue_id" class="block text-sm font-bold text-gray-700 mb-2">Lokasi Lapangan <span class="text-red-500">*</span></label>
                            
                            @if(old('venue_id') || request('venue_id'))
                                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-2 rounded-xl mb-2 text-xs font-bold flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    Lokasi dari peta telah dipilih!
                                </div>
                            @endif

                            <div class="relative">
                                <select id="venue_id" name="venue_id" required class="appearance-none w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 block px-4 py-3.5 transition cursor-pointer font-medium">
                                    <option value="" disabled {{ (!old('venue_id') && !request('venue_id')) ? 'selected' : '' }} class="text-gray-400">Pilih Lokasi</option>
                                    {{-- ASUMSI: $venues dilewatkan dari Controller --}}
                                    @foreach($venues as $venue)
                                        <option value="{{ $venue->id }}" {{ (old('venue_id', request('venue_id')) == $venue->id) ? 'selected' : '' }}>
                                            {{ $venue->name }} - {{ $venue->city }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                            @error('venue_id') <p class="text-red-500 text-xs mt-1 font-semibold ml-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Catatan Tambahan --}}
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-bold text-gray-700 mb-2">Catatan Tambahan <span class="text-gray-400 font-normal">(Opsional)</span></label>
                            <textarea id="description" name="description" rows="3" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 block px-4 py-3 transition placeholder-gray-400 font-medium" placeholder="Tulis info penting: bawa raket sendiri, patungan di lokasi, level pemula welcome, dll.">{{ old('description', request('description')) }}</textarea>
                            @error('description') <p class="text-red-500 text-xs mt-1 font-semibold ml-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <hr class="border-gray-100 mb-10">

                {{-- BAGIAN 2: JADWAL & BIAYA --}}
                <div class="mb-10">
                    <h3 class="text-emerald-900 font-bold text-lg mb-6 flex items-center gap-2">
                        <span class="bg-emerald-100 text-emerald-600 w-8 h-8 rounded-full flex items-center justify-center text-sm font-black">2</span>
                        Jadwal & Biaya
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        
                        {{-- Waktu Mulai --}}
                        <div>
                            <label for="start_datetime" class="block text-sm font-bold text-gray-700 mb-2">Waktu Mulai <span class="text-red-500">*</span></label>
                            <input type="datetime-local" id="start_datetime" name="start_datetime" 
                                value="{{ old('start_datetime', request('start_datetime')) }}" 
                                class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 block px-4 py-3.5 transition font-medium" required>
                            @error('start_datetime') <p class="text-red-500 text-xs mt-1 font-semibold ml-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Maks. Peserta --}}
                        <div>
                            <label for="max_participants" class="block text-sm font-bold text-gray-700 mb-2">Maks. Peserta <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="number" id="max_participants" name="max_participants" 
                                    value="{{ old('max_participants', request('max_participants', 10)) }}" 
                                    class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 block px-4 py-3.5 transition font-bold text-center">
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400 text-sm font-bold bg-gray-100 rounded-r-xl border-l border-gray-200">
                                    Orang
                                </div>
                            </div>
                            @error('max_participants') <p class="text-red-500 text-xs mt-1 font-semibold ml-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Total Biaya Lapangan --}}
                        <div>
                            <label for="total_cost" class="block text-sm font-bold text-gray-700 mb-2">Total Biaya Lapangan</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-4 text-emerald-600 font-black text-sm">
                                    Rp
                                </div>
                                <input type="number" step="1000" id="total_cost" name="total_cost" 
                                    value="{{ old('total_cost', request('total_cost', 0)) }}" 
                                    class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 block pl-12 pr-4 py-3.5 transition font-bold" placeholder="0">
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1 ml-1">*Biaya ini akan dibagi rata antar peserta.</p>
                            @error('total_cost') <p class="text-red-500 text-xs mt-1 font-semibold ml-1">{{ $message }}</p> @enderror
                        </div>
                        
                    </div>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold py-4 rounded-xl shadow-lg hover:shadow-emerald-200/50 transition transform active:scale-[0.99] flex justify-center items-center gap-2 text-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Luncurkan Room Sekarang
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    console.log("Venue-Sport Script Loaded!");
    
    // Data Venues & Sports dari Backend
    const venuesData = @json($venues);
    console.log("All Venues Data:", venuesData); // Debug di console

    // DOM Elements
    const venueSelect = document.getElementById('venue_id');
    const sportSelect = document.getElementById('sport_id');
    
    function filterSports(selectedVenueId) {
        // 1. Jika tidak ada venue yang dipilih atau Venue Kosong -> Disable Sport Select
        if (!selectedVenueId) {
            sportSelect.disabled = true;
            sportSelect.value = ""; // Reset ke default
            return;
        }

        const venue = venuesData.find(v => v.id === selectedVenueId);
        
        if (venue && venue.sports) {
            sportSelect.disabled = false; // Enable kembali jika venue valid
            
            const supportedSportIds = venue.sports.map(s => s.id);
            
            Array.from(sportSelect.options).forEach(opt => {
                if (opt.value === "") return;
                
                const sportId = parseInt(opt.value);
                const isSupported = supportedSportIds.includes(sportId);
                
                if (isSupported) {
                    opt.style.display = "";
                    opt.disabled = false;
                    opt.innerText = opt.innerText.replace(' (Tidak Tersedia)', '');
                } else {
                    opt.style.display = "none";
                    opt.disabled = true;
                    if (!opt.innerText.includes('(Tidak Tersedia)')) {
                        opt.innerText += ' (Tidak Tersedia)';
                    }
                }
            });

            // 2. Logic Reset: Jika sport yang sedang dipilih TIDAK ada di daftar support -> Reset
            if (sportSelect.value && !supportedSportIds.includes(parseInt(sportSelect.value))) {
                sportSelect.value = "";
            }
        } else {
             // Fallback jika data venue tidak ketemu di JS object
             sportSelect.disabled = true;
        }
    }

    // Event Listener: Saat Venue Dipilih
    venueSelect.addEventListener('change', function() {
        // ParseInt akan return NaN jika string kosong, yang falsy
        filterSports(parseInt(this.value));
    });

    // Event Listener: Saat Load
    document.addEventListener('DOMContentLoaded', function() {
        // Cek state awal
        if (venueSelect.value) {
            filterSports(parseInt(venueSelect.value));
        } else {
            // Default: Disable sport jika belum ada venue
            sportSelect.disabled = true;
        }
    });

    // ... (rest of code)

    sportSelect.addEventListener('change', function() {
        // ... (Keep existing logic or simplify if needed)
    });

    // Fungsi Cari Lapangan (Original)
    function cariLapanganDenganData() {
        // ... (Keep existing logic)
        const title = document.getElementById('title')?.value || '';
        const sportId = document.getElementById('sport_id')?.value || '';
        const desc = document.getElementById('description')?.value || '';
        const startDate = document.getElementById('start_datetime')?.value || '';
        const maxPart = document.getElementById('max_participants')?.value || '';
        const totalCost = document.getElementById('total_cost')?.value || ''; 
        const venueId = document.getElementById('venue_id')?.value || '';

        let baseUrl = "{{ route('venues.search') }}"; 
        const params = new URLSearchParams();
        
        if (title) params.set('title', title);
        if (sportId) params.set('sport_id', sportId);
        if (desc) params.set('description', desc);
        if (startDate) params.set('start_datetime', startDate);
        if (maxPart) params.set('max_participants', maxPart);
        if (totalCost) params.set('total_cost', totalCost);
        if (venueId) params.set('venue_id', venueId);
        
        window.location.href = `${baseUrl}?${params.toString()}`;
    }
</script>
@endsection