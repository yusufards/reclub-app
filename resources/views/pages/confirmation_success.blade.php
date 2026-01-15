<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Status Konfirmasi' }} | SportClub</title>

    {{-- STANDAR LARAVEL 11: Mengganti CDN dengan Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <style>
        body { font-family: 'Montserrat', sans-serif; }
    </style>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">

    <div class="bg-white max-w-md w-full rounded-3xl shadow-2xl overflow-hidden transform transition-all hover:scale-[1.01]">

        {{-- LOGIKA HEADER DINAMIS (HIJAU vs MERAH) --}}
        @php
            $isSuccess = isset($status) && $status === 'success';
            $bgGradient = $isSuccess ? 'from-emerald-500 to-teal-600' : 'from-red-500 to-rose-600';
            $iconBg = $isSuccess ? 'bg-emerald-300' : 'bg-red-300';
            $iconColor = $isSuccess ? 'text-emerald-500' : 'text-red-500';
            $statusBadgeBg = $isSuccess ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-red-50 text-red-700 border-red-200';
            $statusText = $isSuccess ? '✅ BERHASIL DITERIMA' : '⛔ TELAH DITOLAK';
        @endphp

        <div class="bg-gradient-to-br {{ $bgGradient }} p-10 text-center relative overflow-hidden">
            {{-- Dekorasi background --}}
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full blur-3xl -mr-10 -mt-10"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 {{ $iconBg }} opacity-20 rounded-full blur-2xl -ml-5 -mb-5"></div>

            <div class="relative z-10">
                <div class="bg-white w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 shadow-xl animate-bounce">
                    @if($isSuccess)
                        {{-- Ikon Centang --}}
                        <svg class="w-10 h-10 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                        </svg>
                    @else
                        {{-- Ikon Silang --}}
                        <svg class="w-10 h-10 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    @endif
                </div>
                <h1 class="text-white text-2xl font-black tracking-tight mb-1 uppercase">{{ $title ?? 'Status Aksi' }}</h1>
                <p class="text-white text-opacity-90 text-sm font-medium px-4 leading-relaxed">
                    {{ $message ?? 'Proses telah selesai dilakukan.' }}
                </p>
            </div>
        </div>

        <div class="p-8">
            {{-- STATUS BADGE --}}
            <div class="text-center mb-8">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">STATUS PESERTA SAAT INI</p>
                <span class="{{ $statusBadgeBg }} px-6 py-2 rounded-full text-xs font-black border shadow-sm inline-block uppercase tracking-wide">
                    {{ $statusText }}
                </span>
            </div>

            {{-- DETAIL TICKET (HANYA MUNCUL JIKA ADA DATA PARTICIPANT) --}}
            @if(isset($participant))
            <div class="space-y-5 border-t border-gray-100 pt-6">

                {{-- Nama Peserta --}}
                <div class="flex justify-between items-center group">
                    <div class="flex items-center gap-2">
                        <span class="bg-gray-100 p-1.5 rounded-lg text-gray-500 group-hover:bg-gray-200 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </span>
                        <span class="text-gray-500 text-sm font-medium">Nama Peserta</span>
                    </div>
                    <span class="font-bold text-gray-800 text-sm">{{ $participant->user->name }}</span>
                </div>

                {{-- Judul Room --}}
                <div class="flex justify-between items-center group">
                    <div class="flex items-center gap-2">
                        <span class="bg-gray-100 p-1.5 rounded-lg text-gray-500 group-hover:bg-gray-200 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </span>
                        <span class="text-gray-500 text-sm font-medium">Room Mabar</span>
                    </div>
                    <span class="font-bold text-gray-800 text-right truncate w-40 text-sm" title="{{ $participant->room->title }}">
                        {{ $participant->room->title }}
                    </span>
                </div>

                {{-- Jadwal --}}
                <div class="flex justify-between items-start group">
                    <div class="flex items-center gap-2 mt-1">
                        <span class="bg-gray-100 p-1.5 rounded-lg text-gray-500 group-hover:bg-gray-200 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </span>
                        <span class="text-gray-500 text-sm font-medium">Jadwal Main</span>
                    </div>
                    <div class="text-right">
                        <span class="font-bold text-gray-800 block text-sm">{{ $participant->room->start_datetime->format('d M Y') }}</span>
                        <span class="text-xs font-bold text-gray-500 block mt-0.5">Pukul {{ $participant->room->start_datetime->format('H:i') }} WIB</span>
                    </div>
                </div>
            </div>
            @endif

            {{-- ACTION BUTTONS --}}
            <div class="mt-8 space-y-3">
                @if(isset($room_url))
                    <a href="{{ $room_url }}" class="block w-full bg-gray-900 text-white text-center py-3.5 rounded-xl font-bold shadow-lg hover:bg-black transition transform active:scale-95 text-sm flex justify-center items-center gap-2">
                        <span>Lihat Detail Room</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                @endif
                
                <a href="{{ route('home') }}" class="block w-full bg-white border border-gray-200 text-gray-600 text-center py-3.5 rounded-xl font-bold hover:bg-gray-50 transition text-sm">
                    Kembali ke Beranda
                </a>
            </div>
        </div>

        {{-- FOOTER KECIL --}}
        <div class="bg-gray-50 p-4 text-center border-t border-gray-100">
            <p class="text-[10px] text-gray-400 font-medium">
                Sistem Konfirmasi Otomatis • SportClub • {{ now()->format('d/m/Y H:i') }}
            </p>
        </div>
    </div>

</body>
</html>