@extends('layouts.app')

{{-- 1. Judul Tab Browser --}}
@section('title', $room->title . ' | SportClub')

{{-- 2. Meta Tags untuk Preview Link (WA, IG, FB, Twitter) --}}
@push('meta-tags')
    @php
        // Hitung Slot & Biaya untuk Preview
        $confirmedCount = $room->participants->where('status', 'confirmed')->count();
        $sisaSlot = max(0, $room->max_participants - $confirmedCount);
        $biaya = $room->cost_per_person > 0 ? 'Rp ' . number_format($room->cost_per_person, 0, ',', '.') : 'GRATIS';

        $shareUrl = route('rooms.show', $room);
        $shareTitle = "Yuk Mabar {$room->sport->name}: {$room->title}";
        $shareDesc = "💰 {$biaya} | 📍 " . ($room->venue->name ?? 'Lokasi') . " | 🔥 Sisa {$sisaSlot} Slot.";
        
        // Gambar Placeholder Dinamis
        $textImg = urlencode($room->sport->name . " - " . $biaya);
        $shareImage = "https://placehold.co/600x315/065f46/ffffff.png?text={$textImg}";
    @endphp

    <meta name="title" content="{{ $shareTitle }}">
    <meta name="description" content="{{ $shareDesc }}">
    <meta property="og:url" content="{{ $shareUrl }}">
    <meta property="og:title" content="{{ $shareTitle }}">
    <meta property="og:description" content="{{ $shareDesc }}">
    <meta property="og:image" content="{{ $shareImage }}">
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ $shareUrl }}">
    <meta property="twitter:title" content="{{ $shareTitle }}">
    <meta property="twitter:description" content="{{ $shareDesc }}">
    <meta property="twitter:image" content="{{ $shareImage }}">
@endpush

@section('content')

    {{-- TOAST NOTIFIKASI --}}
    <div id="toast" class="fixed top-24 right-5 bg-gray-900 text-white px-6 py-4 rounded-xl shadow-2xl transform translate-x-full transition-transform duration-300 z-50 flex items-center gap-3 border-l-4 border-emerald-500">
        <div class="bg-emerald-500 text-white p-1 rounded-full">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <div>
            <h4 class="font-bold text-sm">Berhasil!</h4>
            <p class="text-xs text-gray-300" id="toastMessage">Aksi berhasil dilakukan.</p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 py-8">

        {{-- BREADCRUMB & HEADER SECTION --}}
        <div class="mb-6">
            <a href="{{ route('cari.mabar') }}" class="text-emerald-600 hover:text-emerald-800 text-sm font-medium flex items-center gap-1 transition group">
                <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Cari Mabar
            </a>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-start mb-8 gap-6">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-3">
                    <span class="bg-emerald-100 text-emerald-700 text-xs font-black px-3 py-1 rounded-lg uppercase tracking-wide">
                        {{ $room->sport->name }}
                    </span>
                    @if(!$room->is_active)
                        <span class="bg-red-100 text-red-700 text-xs font-black px-3 py-1 rounded-lg uppercase">NONAKTIF</span>
                    @elseif(now() >= $room->start_datetime)
                        <span class="bg-gray-200 text-gray-700 text-xs font-black px-3 py-1 rounded-lg uppercase">SELESAI</span>
                    @endif
                </div>
                <h1 class="text-3xl md:text-4xl font-black text-gray-900 leading-tight mb-2">{{ $room->title }}</h1>
                <div class="flex items-center gap-2 text-gray-500 text-sm">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span class="font-medium">{{ $room->venue->name ?? 'Lokasi tidak diketahui' }}</span>
                    <span class="text-gray-300 mx-1">|</span>
                    <span>{{ $room->venue->city ?? 'Kota tidak diketahui' }}</span>
                </div>
            </div>

            {{-- KODE UNDANGAN (HANYA HOST) --}}
            @if(auth()->id() == $room->host_id)
                <div class="group relative w-full md:w-auto shrink-0">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-emerald-400 to-teal-500 rounded-2xl blur opacity-30 group-hover:opacity-60 transition duration-200"></div>
                    <div class="relative bg-white rounded-xl p-5 border border-gray-100 shadow-sm text-center">
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-2">KODE UNDANGAN</p>
                        <div class="flex items-center justify-center gap-2 bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 mb-3">
                            <span class="text-2xl font-mono font-black text-gray-800 tracking-widest select-all" id="roomCodeText">{{ $room->code }}</span>
                        </div>
                        <button onclick="copyCode()" class="w-full bg-gray-900 hover:bg-emerald-600 text-white text-xs font-bold py-2.5 rounded-lg transition flex items-center justify-center gap-2 shadow-md active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                            SALIN KODE
                        </button>
                    </div>
                </div>
            @endif
        </div>

        {{-- STATS GRID --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            {{-- JADWAL --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-start gap-4 hover:border-emerald-100 transition">
                <div class="bg-blue-50 text-blue-600 p-3 rounded-xl shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-bold uppercase mb-1">Jadwal Main</p>
                    <p class="font-bold text-gray-900 text-lg">{{ $room->start_datetime->format('d M Y') }}</p>
                    <p class="text-emerald-600 font-bold">{{ $room->start_datetime->format('H:i') }} WIB</p>
                </div>
            </div>

            {{-- BIAYA --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-start gap-4 hover:border-emerald-100 transition">
                <div class="bg-green-50 text-green-600 p-3 rounded-xl shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-bold uppercase mb-1">Patungan</p>
                    <p class="font-bold text-gray-900 text-2xl">
                        @if($room->cost_per_person > 0)
                            Rp {{ number_format($room->cost_per_person, 0, ',', '.') }}
                        @else
                            Gratis
                        @endif
                    </p>
                    <p class="text-xs text-gray-400">per orang</p>
                </div>
            </div>

            {{-- SLOT --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-center hover:border-emerald-100 transition">
                @php
                    $confirmedParticipants = $room->participants->where('status', 'confirmed')->count();
                    $slotPercentage = ($room->max_participants > 0) ? ($confirmedParticipants / $room->max_participants) * 100 : 0;
                    $remainingSlots = $room->max_participants - $confirmedParticipants;
                @endphp
                <div class="flex justify-between items-end mb-2">
                    <p class="text-xs text-gray-400 font-bold uppercase">Ketersediaan Slot</p>
                    <p class="text-sm font-bold text-gray-900">{{ $confirmedParticipants }} / {{ $room->max_participants }}</p>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                    <div class="bg-gradient-to-r from-emerald-400 to-green-600 h-full rounded-full transition-all duration-1000 ease-out" style="width: {{ $slotPercentage }}%"></div>
                </div>
                <p class="text-right text-[10px] text-emerald-600 font-bold mt-2">
                    {{ $remainingSlots }} tempat tersisa
                </p>
            </div>
        </div>

        {{-- LOKASI DAN PETA --}}
        <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100 mb-10">
            <h2 class="text-xl font-bold text-gray-900 mb-4 border-b pb-3">Lokasi Pertandingan</h2>

            <p class="text-sm font-bold text-gray-800 mb-2">{{ $room->venue->name ?? 'Venue tidak ditemukan' }}</p>
            <p class="text-sm text-gray-600 mb-4">{{ $room->venue->address ?? 'Alamat tidak tersedia' }}</p>

            {{-- MAP --}}
            @if($room->venue && $room->venue->latitude && $room->venue->longitude)
                <div id="mapid" class="w-full h-80 rounded-xl border border-gray-300 shadow-md relative z-0"></div>
                <a href="https://maps.google.com/?q={{ $room->venue->latitude }},{{ $room->venue->longitude }}" target="_blank" class="mt-4 inline-flex items-center text-sm font-bold text-blue-600 hover:text-blue-700 transition">
                    Lihat di Google Maps →
                </a>
            @else
                <p class="text-red-500 text-sm bg-red-50 p-4 rounded-xl border border-red-100 text-center">Koordinat lokasi tidak tersedia untuk peta.</p>
            @endif
        </div>

        {{-- CATATAN HOST --}}
        <div class="mb-12">
            <div class="bg-orange-50 border-l-4 border-orange-400 p-6 rounded-r-xl shadow-sm">
                <h3 class="text-lg font-bold text-orange-800 mb-2 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Catatan Host
                </h3>
                <p class="text-gray-700 leading-relaxed whitespace-pre-line text-sm">
                    {{ $room->description ?: 'Tidak ada catatan khusus dari host.' }}
                </p>
            </div>
        </div>

        {{-- LOGIKA TOMBOL GABUNG / STATUS (AKSI) --}}
        @php
            $isStarted = now() >= $room->start_datetime;
            $isFull = $confirmedCount >= $room->max_participants;
            $user = auth()->user();
            $myStatus = $user ? $room->participants->firstWhere('user_id', $user->id)?->status : null;
            $isHost = $user && $user->id == $room->host_id;
            $isAdmin = $user && $user->role === 'admin';
        @endphp

        <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100"> 

            {{-- 1. Tampilan Admin --}}
            @if($isAdmin)
                <div class="flex flex-col items-center justify-center gap-2 text-center py-4">
                    <div class="bg-gray-200 p-3 rounded-full text-gray-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </div>
                    <h3 class="font-bold text-gray-700">Admin View Mode</h3>
                    <p class="text-sm text-gray-500">Anda sedang melihat room ini sebagai Administrator.</p>
                </div>

            {{-- 2. Tampilan Room Closed --}}
            @elseif($isStarted)
                <div class="w-full bg-gray-100 text-gray-500 font-bold py-5 rounded-xl text-center border-2 border-gray-200 cursor-not-allowed shadow-inner flex flex-col items-center justify-center gap-2">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-lg">🚫 Room Closed (Waktu Habis)</span>
                    <span class="text-sm font-normal">Pertandingan sudah dimulai atau selesai.</span>
                </div>

            {{-- 3. Tampilan Sudah Join / Rejected + TOMBOL LEAVE/RE-JOIN --}}
            @elseif($myStatus)
                <div class="p-6 rounded-2xl border-2 {{ $myStatus == 'confirmed' ? 'bg-emerald-50 border-emerald-200' : ($myStatus == 'rejected' ? 'bg-red-50 border-red-200' : 'bg-yellow-50 border-yellow-200') }}">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="p-3 rounded-full {{ $myStatus == 'confirmed' ? 'bg-emerald-100 text-emerald-600' : ($myStatus == 'rejected' ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-600') }} shrink-0">
                                @if($myStatus == 'confirmed')
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                @elseif($myStatus == 'rejected')
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                @else
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                @endif
                            </div>
                            <div>
                                <h3 class="font-bold text-xl {{ $myStatus == 'confirmed' ? 'text-emerald-800' : ($myStatus == 'rejected' ? 'text-red-800' : 'text-yellow-800') }}">
                                    @if($myStatus == 'confirmed') Anda Terdaftar! @elseif($myStatus == 'rejected') Permintaan Ditolak @else Menunggu Konfirmasi @endif
                                </h3>
                                <p class="text-sm {{ $myStatus == 'confirmed' ? 'text-emerald-600' : ($myStatus == 'rejected' ? 'text-red-600' : 'text-yellow-600') }}">
                                    @if($myStatus == 'confirmed') Sampai jumpa di lapangan! 
                                    @elseif($myStatus == 'rejected') Host menolak. Anda bisa mencoba mengajukan lagi.
                                    @else Host sedang meninjau request Anda. 
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="shrink-0 flex gap-3 mt-4 md:mt-0">
                            {{-- LOGIKA RE-JOIN --}}
                            @if($myStatus == 'rejected')
                                <form action="{{ route('rooms.join', $room) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg shadow-md text-sm transition transform active:scale-95 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                        Ajukan Lagi
                                    </button>
                                </form>
                            @endif

                            {{-- LOGIKA LEAVE --}}
                            @if(!$isHost && $myStatus != 'rejected')
                                <form action="{{ route('rooms.leave', $room) }}" method="POST" onsubmit="return confirm('Yakin ingin keluar dari room ini?');">
                                    @csrf
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-bold text-sm underline decoration-red-300 hover:decoration-red-700 underline-offset-4 transition">
                                        Keluar Room
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

            {{-- 4. Tampilan Host (Edit Room) --}}
            @elseif($isHost)
                <a href="{{ route('rooms.edit', $room) }}" class="block w-full bg-gray-800 hover:bg-black text-white font-bold py-4 rounded-2xl text-center transition shadow-lg flex items-center justify-center gap-2 transform active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Edit Detail Room
                </a>

            {{-- 5. Tampilan Join (User & Guest) --}}
            @else
                @auth
                    <form action="{{ route('rooms.join', $room) }}" method="POST">
                        @csrf
                        @if($isFull)
                            <button disabled type="button" class="w-full bg-red-50 text-red-400 font-bold py-4 rounded-2xl cursor-not-allowed border-2 border-red-100">
                                Mohon Maaf, Slot Penuh
                            </button>
                        @else
                            <button class="group w-full bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-bold py-4 rounded-2xl shadow-xl transition-all duration-300 transform hover:-translate-y-1 flex justify-center items-center gap-3">
                                <span class="text-lg">Gabung Room Ini Sekarang</span>
                                <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </button>
                        @endif
                    </form>
                @else
                    @if($isFull)
                        <button disabled class="w-full bg-gray-100 text-gray-400 font-bold py-4 rounded-xl cursor-not-allowed">
                            Slot Penuh
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="block w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 rounded-2xl text-center shadow-xl transition flex justify-center items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                            <span>Login untuk Gabung</span>
                        </a>
                    @endif
                @endauth
            @endif
        </div> 

        {{-- DAFTAR PESERTA --}}
        <div class="mt-12">
            @php
                // FILTER: Hanya tampilkan Confirmed dan Pending. Rejected disembunyikan dari list publik.
                $activeParticipants = $room->participants->filter(function($participant) {
                    return in_array($participant->status, ['confirmed', 'pending']);
                });
            @endphp

            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-black text-gray-800">Daftar Peserta</h3>
                <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-bold">{{ $activeParticipants->count() }} Orang</span>
            </div>

            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden">
                @forelse($activeParticipants as $participant)
                    <div class="p-5 border-b border-gray-50 flex flex-col sm:flex-row items-center justify-between last:border-0 hover:bg-gray-50 transition gap-4">
                        <div class="flex items-center gap-4 w-full sm:w-auto">
                            <img src="{{ $participant->user->avatar ? asset('storage/' . $participant->user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($participant->user->name) . '&background=random&bold=true' }}" 
                                class="w-12 h-12 rounded-full border-2 border-white shadow-sm object-cover"
                                alt="{{ $participant->user->name }}">

                            <div>
                                <div class="flex items-center gap-2">
                                    <p class="font-bold text-gray-900">{{ $participant->user->name }}</p>
                                    @if($participant->user_id == $room->host_id)
                                        <span class="bg-yellow-100 text-yellow-700 text-[10px] font-bold px-2 py-0.5 rounded uppercase">Host</span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500">Bergabung {{ $participant->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        {{-- STATUS & ACTION BUTTONS (HANYA HOST) --}}
                        <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                            @if($participant->status == 'confirmed')
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-600 border border-emerald-100">Confirmed</span>
                            @elseif($participant->status == 'pending')
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-yellow-50 text-yellow-600 border border-yellow-100">Pending</span>
                            @endif

                            @if($isHost && $participant->user_id != $room->host_id)
                                <div class="flex items-center gap-2">
                                    @if($participant->status == 'pending')
                                        {{-- TOMBOL TOLAK --}}
                                        <form action="{{ route('participants.reject', ['room' => $room->id, 'participant' => $participant->id]) }}" method="POST">
                                            @csrf
                                            <button title="Tolak" class="bg-white border border-red-200 text-red-500 hover:bg-red-50 hover:text-red-700 p-2 rounded-lg transition shadow-sm transform active:scale-95">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </form>
                                        
                                        {{-- TOMBOL TERIMA --}}
                                        <form action="{{ route('participants.confirm', ['room' => $room->id, 'participant' => $participant->id]) }}" method="POST">
                                            @csrf
                                            <button class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs px-4 py-2 rounded-lg transition font-bold shadow-md transform active:scale-95 flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                Terima
                                            </button>
                                        </form>
                                    @elseif($participant->status == 'confirmed')
                                        {{-- TOMBOL KELUARKAN (KICK) --}}
                                        <form action="{{ route('participants.reject', ['room' => $room->id, 'participant' => $participant->id]) }}" method="POST" onsubmit="return confirm('Yakin ingin mengeluarkan {{ $participant->user->name }} dari room?');">
                                            @csrf
                                            <button title="Keluarkan" class="bg-white border border-red-400 text-red-600 hover:bg-red-100 p-2 rounded-lg transition shadow-sm transform active:scale-95">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM17 14h6m-3-3v6M6 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center text-gray-500">
                        <p>Belum ada peserta yang bergabung, atau peserta ditolak semua.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- SHARE LINK SECTION --}}
        <div class="mt-8 border-t border-gray-100 pt-8">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                Bagikan Room Ini
            </h3>

            <div class="bg-gray-50 p-4 rounded-2xl border border-gray-200">
                <p class="text-xs text-gray-500 mb-2">Salin link di bawah untuk share ke Instagram, WA, atau Grup:</p>
                <div class="flex flex-col sm:flex-row gap-2">
                    <input type="text" id="shareUrl" value="{{ route('rooms.show', $room) }}" readonly class="w-full bg-white border border-gray-300 text-gray-600 text-sm rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 select-all">
                    <button onclick="copyShareLink()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-3 rounded-xl transition shadow-lg active:scale-95 flex items-center justify-center gap-2 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                        Salin
                    </button>
                </div>
            </div>
        </div>

    </div> {{-- END MAX-W-5XL --}}

@endsection

@push('scripts')
    {{-- Leaflet JS & Map Init --}}
    @if($room->venue && $room->venue->latitude && $room->venue->longitude)
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var lat = {{ $room->venue->latitude }};
                var lng = {{ $room->venue->longitude }};
                var map = L.map('mapid').setView([lat, lng], 14);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);
                L.marker([lat, lng]).addTo(map)
                    .bindPopup("<b>{{ $room->venue->name }}</b><br>{{ $room->venue->address }}")
                    .openPopup();
            });
        </script>
    @endif

    {{-- Script Polling & Toast --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Polling Status Room (Auto Redirect jika dihapus)
            const roomId = "{{ $room->id }}";
            const checkUrl = "{{ route('rooms.check', ':id') }}".replace(':id', roomId);
            const homeUrl = "{{ route('home') }}";

            setInterval(function() {
                fetch(checkUrl, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'deleted') {
                        window.location.href = homeUrl;
                    }
                })
                .catch(err => console.error(err));
            }, 5000);
        });

        function copyCode() {
            var codeText = document.getElementById("roomCodeText").innerText;
            copyToClipboard(codeText, 'Kode Room Disalin!');
        }

        function copyShareLink() {
            var urlText = document.getElementById("shareUrl").value;
            copyToClipboard(urlText, 'Link Berhasil Disalin!');
        }

        function copyToClipboard(text, msg) {
            navigator.clipboard.writeText(text).then(function() {
                var toast = document.getElementById("toast");
                var msgEl = document.getElementById("toastMessage");
                if(msgEl) msgEl.innerText = msg;
                
                toast.classList.remove("translate-x-full");
                setTimeout(() => toast.classList.add("translate-x-full"), 3000);
            }, function(err) {
                alert('Gagal menyalin: ' + text);
            });
        }
    </script>
@endpush