<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'SportClub'))</title>

    {{-- STANDAR LARAVEL 11: Mengganti CDN dengan Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        body { font-family: 'Montserrat', sans-serif; }
    </style>
    @stack('meta-tags')
</head>
<body class="bg-gray-50 text-gray-700 flex flex-col min-h-screen relative">

    {{-- NAVIGASI UTAMA --}}
    <nav class="sticky top-0 z-50 border-b border-gray-100 bg-white/90 backdrop-blur-md shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                
                {{-- LOGO & MENU DESKTOP (KIRI) --}}
                <div class="flex items-center gap-6 md:gap-10">
                    <a href="{{ route('home') }}" class="text-xl md:text-2xl font-black tracking-tight text-gray-800 flex items-center gap-2 group">
                        <span class="bg-emerald-600 w-8 h-8 rounded-lg flex items-center justify-center text-white font-black shadow-md shadow-emerald-200">S</span>
                        Sport<span class="text-emerald-600">Club</span>
                    </a>
                </div>
                
                {{-- MENU DESKTOP (DI TENGAH) --}}
                <div class="hidden sm:flex flex-1 justify-center">
                    <div class="flex space-x-4 md:space-x-8">
                        <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
                            Beranda
                        </x-nav-link>

                        @auth
                            @if(!auth()->user()->isAdmin()) 
                                <x-nav-link href="{{ route('cari.mabar') }}" :active="request()->routeIs('cari.mabar')">
                                    Cari Mabar
                                </x-nav-link>
                                <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                                    Dashboard Saya
                                </x-nav-link>
                            @else
                                <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                                    Admin Panel
                                </x-nav-link>
                            @endif
                        @endauth
                    </div>
                </div>

                {{-- USER MENU & HAMBURGER (KANAN) --}}
                <div class="flex items-center gap-4">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <div class="hidden md:block text-xs bg-red-100 text-red-600 px-3 py-1 rounded-full font-bold uppercase tracking-wide">Admin</div>
                        @endif
                        
                        {{-- DROPDOWN PROFIL (DESKTOP) --}}
                        <div class="hidden sm:block relative">
                            <button id="user-menu-button" class="flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-emerald-600 transition p-2 rounded-lg hover:bg-gray-100 focus:outline-none">
                                <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=10b981&color=fff' }}" 
                                        class="w-9 h-9 rounded-full border-2 border-emerald-300 object-cover shadow-sm pointer-events-none">
                                
                                <span class="hidden lg:inline text-gray-700 font-bold pointer-events-none">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4 text-gray-500 pointer-events-none transition-transform duration-200" id="user-menu-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            
                            <div id="user-menu-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-2xl border border-gray-100 z-50 overflow-hidden origin-top-right transform transition-all duration-200">
                                <a href="{{ route('dashboard') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 font-medium border-b border-gray-50/50">
                                    📊 {{ Auth::user()->isAdmin() ? 'Admin Dashboard' : 'Dashboard Saya' }}
                                </a>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 font-medium">
                                    👤 Pengaturan Akun
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 font-medium flex items-center gap-2 border-t border-gray-50/50">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-500 hover:text-emerald-600 font-medium text-sm hidden sm:block">Masuk</a>
                        <a href="{{ route('register') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-full text-sm font-bold shadow-lg shadow-emerald-200 transition transform hover:-translate-y-0.5 hidden sm:block">
                            Daftar
                        </a>
                    @endauth
                    
                    {{-- MOBILE MENU BUTTON --}}
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button id="mobile-menu-button" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-emerald-500">
                            <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- MOBILE MENU (DROPDOWN) - ABSOLUTE POSITION FIX --}}
        <div class="sm:hidden hidden absolute top-16 left-0 w-full bg-white border-b border-gray-200 shadow-xl z-40" id="mobile-menu">
            <div class="pt-2 pb-3 space-y-1">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'bg-emerald-50 text-emerald-700' : 'text-gray-600 hover:bg-gray-50' }} block pl-4 pr-4 py-3 border-l-4 border-transparent text-base font-medium transition">Beranda</a>
                
                @auth
                    @if(!auth()->user()->isAdmin())
                        <a href="{{ route('cari.mabar') }}" class="{{ request()->routeIs('cari.mabar') ? 'bg-emerald-50 text-emerald-700' : 'text-gray-600 hover:bg-gray-50' }} block pl-4 pr-4 py-3 border-l-4 border-transparent text-base font-medium transition">Cari Mabar</a>
                        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-emerald-50 text-emerald-700' : 'text-gray-600 hover:bg-gray-50' }} block pl-4 pr-4 py-3 border-l-4 border-transparent text-base font-medium transition">Dashboard Saya</a>
                    @else
                         <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-emerald-50 text-emerald-700' : 'text-gray-600 hover:bg-gray-50' }} block pl-4 pr-4 py-3 border-l-4 border-transparent text-base font-medium transition">Admin Panel</a>
                    @endif
                    
                    <div class="pt-4 mt-2 border-t border-gray-200">
                        <a href="{{ route('profile.edit') }}" class="text-gray-600 hover:bg-gray-50 block pl-4 pr-4 py-3 text-base font-medium transition flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.82 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.82 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.82-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.82-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Pengaturan Akun
                        </a>
                        
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit" class="w-full text-left text-red-600 hover:bg-red-50 block pl-4 pr-4 py-3 text-base font-medium flex items-center gap-2">
                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-gray-600 hover:bg-gray-50 block pl-4 pr-4 py-3 border-l-4 border-transparent text-base font-medium transition flex items-center gap-2">Masuk</a>
                    <a href="{{ route('register') }}" class="bg-emerald-600 text-white block text-center mx-4 py-3 rounded-lg font-bold shadow-md transition transform active:scale-95">Daftar Akun</a>
                @endauth
            </div>
        </div>
    </nav>
    
    <main class="flex-grow relative z-0">
        {{-- Flash Messages --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mt-6 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-r shadow-sm flex justify-between">
                    <p>{{ session('success') }}</p>
                    <span onclick="this.parentElement.remove()" class="cursor-pointer font-bold">&times;</span>
                </div>
            @endif
            @if(session('error'))
                <div class="mt-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-r shadow-sm flex justify-between">
                    <p>{{ session('error') }}</p>
                    <span onclick="this.parentElement.remove()" class="cursor-pointer font-bold">&times;</span>
                </div>
            @endif
        </div>
        
        @yield('content')
    </main>

    <footer class="bg-white border-t border-gray-100 mt-12">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-center text-gray-500 text-sm">
            <span class="font-bold text-gray-800">SportClub &copy; {{ date('Y') }}</span>
        </div>
    </footer>
    
    {{-- SCRIPT INTERAKTIF (VANILLA JS) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // 1. Logic Mobile Menu
            const mobileBtn = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            if(mobileBtn && mobileMenu) {
                mobileBtn.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }

            // 2. Logic Dropdown Profil Desktop
            const userBtn = document.getElementById('user-menu-button');
            const userDropdown = document.getElementById('user-menu-dropdown');
            const userArrow = document.getElementById('user-menu-arrow');

            if(userBtn && userDropdown) {
                userBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    userDropdown.classList.toggle('hidden');
                    if(userArrow) userArrow.classList.toggle('rotate-180');
                });

                document.addEventListener('click', function(e) {
                    if (!userBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                        userDropdown.classList.add('hidden');
                        if(userArrow) userArrow.classList.remove('rotate-180');
                    }
                });
                
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        userDropdown.classList.add('hidden');
                        if(userArrow) userArrow.classList.remove('rotate-180');
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>