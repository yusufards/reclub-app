<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SportClub - Cari Teman Mabar')</title>

    {{-- Integrasi Tailwind + Vite (Standar Laravel 11) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .hero-bg {
            /* Pastikan gambar ini ada di public atau gunakan aset lokal jika memungkinkan */
            background-image: url('https://images.unsplash.com/photo-1517649763962-0c623066013b?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
{{-- PERBAIKAN: body menggunakan bg-white untuk Light Mode (Login/Register) --}}

<body class="antialiased text-gray-800 bg-white">

    {{-- NAVIGASI UTAMA (Diperbaiki Kontras Warna) --}}
    {{-- Halaman Login/Register TIDAK menggunakan absolute top-0, jadi kita ganti ke fixed atau default --}}
    {{-- Kita asumsikan landing.blade yang akan menambahkan class 'absolute top-0' jika diperlukan. --}}
    {{-- Untuk layout ini, kita berikan background putih standar agar kontras dengan teks --}}
    <nav
        class="w-full z-50 px-4 py-4 md:px-6 md:py-6 flex justify-between items-center bg-white border-b border-gray-100 shadow-sm">

        {{-- LOGO SportClub --}}
        <a href="{{ route('landing') }}"
            class="text-xl md:text-2xl font-black text-gray-900 tracking-tighter flex items-center gap-2">
            <span
                class="bg-emerald-500 w-7 h-7 md:w-8 md:h-8 rounded-lg flex items-center justify-center text-sm shadow-lg text-white">S</span>
            SportClub
        </a>

        {{-- TOMBOL MASUK / DAFTAR --}}
        <div class="flex items-center gap-2 md:space-x-6">
            @auth
                {{-- Tombol Dashboard untuk User Login --}}
                <a href="{{ route('dashboard') }}"
                    class="text-gray-900 font-bold hover:text-emerald-600 transition text-xs md:text-base">Dashboard</a>
            @else
                {{-- PERBAIKAN: Text color diubah ke gray-700 --}}
                <a href="{{ route('login') }}"
                    class="text-gray-700 font-medium hover:text-emerald-600 transition text-xs md:text-base">Masuk</a>

                {{-- PERBAIKAN: Tombol Daftar diubah menjadi background hijau, teks putih --}}
                <a href="{{ route('register') }}"
                    class="bg-emerald-600 text-white px-3 py-1.5 md:px-4 md:py-2 rounded-full font-bold hover:bg-emerald-700 transition shadow-lg text-xs md:text-sm">
                    Daftar
                </a>
            @endauth
        </div>
    </nav>

    {{-- TEMPAT KONTEN DINAMIS DARI HALAMAN LAIN --}}
    <main>
        @yield('content')
    </main>

    {{-- FOOTER (Opsional, di sini tidak dicantumkan karena ada di landing.blade) --}}

</body>

</html>