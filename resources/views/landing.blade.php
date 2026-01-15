@extends('layouts.guest')

@section('title', 'Cari Lawan, Mabar Seru! - SportClub')

@section('content')

    {{-- HERO SECTION --}}
    <div class="relative w-full min-h-screen hero-bg flex flex-col justify-center pt-32 pb-12">
        
        <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/70 to-transparent"></div>

        <div class="relative z-10 px-6 md:px-12 lg:px-24 w-full max-w-7xl mx-auto">
            
            <span class="text-emerald-400 font-bold tracking-widest uppercase text-xs md:text-sm mb-3 block">Komunitas Olahraga No.1</span>
            
            <h1 class="text-4xl md:text-5xl lg:text-7xl font-black text-white leading-tight mb-6 drop-shadow-lg">
                Cari Lawan,<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-300">Mabar Seru!</span>
            </h1>
            
            <p class="text-gray-300 text-sm md:text-base lg:text-xl mb-8 md:mb-10 max-w-lg leading-relaxed">
                Jangan biarkan lapangan kosong. Temukan teman sparring futsal, basket, badminton, dan lainnya di sekitarmu sekarang juga.
            </p>
            
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('home') }}" class="px-6 py-3 md:px-8 md:py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-bold text-sm md:text-lg transition transform hover:-translate-y-1 shadow-emerald-500/30 shadow-lg flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Cari Lapangan
                </a>
                <a href="{{ route('register') }}" class="px-6 py-3 md:px-8 md:py-4 bg-white/10 backdrop-blur-md border border-white/20 hover:bg-white/20 text-white rounded-2xl font-bold text-sm md:text-lg transition flex items-center justify-center gap-2">
                    Buat Room
                </a>
            </div>

            <div class="mt-10 md:mt-16 flex flex-wrap gap-8 md:gap-12 border-t border-white/10 pt-8">
                <div>
                    <h4 class="text-2xl md:text-4xl font-black text-white">100+</h4>
                    <p class="text-gray-400 text-xs md:text-sm uppercase tracking-wide">Venue Tersedia</p>
                </div>
                <div>
                    <h4 class="text-2xl md:text-4xl font-black text-white">500+</h4>
                    <p class="text-gray-400 text-xs md:text-sm uppercase tracking-wide">Mabar Terlaksana</p>
                </div>
                <div>
                    <h4 class="text-2xl md:text-4xl font-black text-white">24/7</h4>
                    <p class="text-gray-400 text-xs md:text-sm uppercase tracking-wide">Sistem Booking</p>
                </div>
            </div>
        </div>
    </div>

    {{-- POPULAR SPORTS SECTION --}}
    <div class="bg-black py-10 overflow-hidden border-t border-white/10">
        <div class="px-6 md:px-12 lg:px-24 mb-6 flex justify-between items-end max-w-7xl mx-auto">
            <h3 class="text-white font-bold text-xl md:text-2xl">Olahraga Populer</h3>
            <a href="{{ route('home') }}" class="text-emerald-500 text-sm font-bold cursor-pointer hover:text-emerald-400">Lihat Semua -></a>
        </div>
        
        <div class="flex gap-6 overflow-x-auto pb-8 px-6 md:px-12 lg:px-24 no-scrollbar snap-x max-w-7xl mx-auto">
            
            {{-- Card Futsal --}}
            <div class="min-w-[240px] md:min-w-[280px] h-[320px] md:h-[350px] rounded-3xl overflow-hidden relative group cursor-pointer snap-center">
                <img src="https://images.unsplash.com/photo-1529900748604-07564a03e7a6?auto=format&fit=crop&q=80&w=800" class="w-full h-full object-cover group-hover:scale-110 transition duration-700" alt="Futsal">
                <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-90"></div>
                <div class="absolute bottom-6 left-6">
                    <span class="bg-emerald-600 text-white text-xs font-bold px-2 py-1 rounded-md mb-2 inline-block">Trending</span>
                    <h4 class="text-white font-black text-xl md:text-2xl">Futsal</h4>
                </div>
            </div>

            {{-- Card Badminton --}}
            <div class="min-w-[240px] md:min-w-[280px] h-[320px] md:h-[350px] rounded-3xl overflow-hidden relative group cursor-pointer snap-center border border-white/10">
                <img src="https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover group-hover:scale-110 transition duration-700" alt="Badminton">
                <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-90"></div>
                <div class="absolute bottom-6 left-6">
                    <h4 class="text-white font-black text-xl md:text-2xl">Badminton</h4>
                </div>
            </div>

            {{-- Card Basket --}}
            <div class="min-w-[240px] md:min-w-[280px] h-[320px] md:h-[350px] rounded-3xl overflow-hidden relative group cursor-pointer snap-center">
                <img src="https://images.unsplash.com/photo-1546519638-68e109498ffc?auto=format&fit=crop&q=80&w=800" class="w-full h-full object-cover group-hover:scale-110 transition duration-700" alt="Basket">
                <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-90"></div>
                <div class="absolute bottom-6 left-6">
                    <h4 class="text-white font-black text-xl md:text-2xl">Basket</h4>
                </div>
            </div>

            {{-- Card Tennis --}}
            <div class="min-w-[240px] md:min-w-[280px] h-[320px] md:h-[350px] rounded-3xl overflow-hidden relative group cursor-pointer snap-center">
                <img src="https://images.unsplash.com/photo-1595435934249-5df7ed86e1c0?auto=format&fit=crop&q=80&w=800" class="w-full h-full object-cover group-hover:scale-110 transition duration-700" alt="Tennis">
                <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-90"></div>
                <div class="absolute bottom-6 left-6">
                    <h4 class="text-white font-black text-xl md:text-2xl">Tennis</h4>
                </div>
            </div>
            
        </div>
    </div>
    
    {{-- FOOTER (Tambahkan di sini jika footer hanya ada di landing) --}}
    <footer class="bg-black text-gray-400 py-10 text-center text-sm border-t border-white/10">
        &copy; {{ date('Y') }} SportClub. All rights reserved. Made with in Indonesia.
    </footer>

@endsection