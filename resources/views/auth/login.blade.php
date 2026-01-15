@extends('layouts.guest')

@section('title', 'Masuk ke Akun Anda | SportClub')

@section('content')

{{-- CONTAINER UTAMA: Overrides background hitam dari guest layout dengan container putih --}}
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-50 md:bg-transparent">
    
    {{-- CARD FORM: Light Mode, menggunakan background putih --}}
    <div class="max-w-md w-full space-y-8 p-8 md:p-10 bg-white rounded-xl shadow-2xl shadow-gray-200 border border-gray-100">
        
        {{-- HEADER FORM --}}
        <div class="text-center">
            <div class="flex items-center justify-center mb-4">
                {{-- PERBAIKAN: Teks SportClub diubah menjadi text-gray-900 --}}
                <span class="bg-emerald-500 w-8 h-8 rounded-lg flex items-center justify-center text-sm shadow-lg text-white font-black">S</span>
                <span class="font-black text-2xl ml-2 text-gray-900">SportClub</span>
            </div>
            {{-- PERBAIKAN: Teks "Masuk ke Akun Anda" diubah menjadi text-gray-900 --}}
            <h2 class="mt-4 text-3xl font-extrabold text-gray-900">
                Masuk ke Akun Anda
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-medium text-emerald-600 hover:text-emerald-500 transition">
                    Daftar Sekarang
                </a>
            </p>
        </div>

        {{-- ALERT ERROR --}}
        @if(session('error'))
            <div class="bg-red-50 text-red-700 text-sm p-3 rounded-lg mb-4 border border-red-200">
                {{ session('error') }}
            </div>
        @endif

        {{-- FORM LOGIN --}}
        <form class="space-y-6" action="{{ route('login') }}" method="POST">
            @csrf
            
            {{-- EMAIL INPUT --}}
            <div>
                <label for="email" class="sr-only">Email</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg>
                    </div>
                    {{-- PERBAIKAN: bg-white, border-gray-300, dan text-gray-900 --}}
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required 
                           class="focus:ring-emerald-500 focus:border-emerald-500 block w-full pl-10 text-gray-900 bg-white border-gray-300 rounded-lg py-3 placeholder-gray-500 text-sm @error('email') border-red-500 @enderror" 
                           placeholder="nama@email.com">
                </div>
                @error('email')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- PASSWORD INPUT --}}
            <div>
                <label for="password" class="sr-only">Password</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    </div>
                    {{-- PERBAIKAN: bg-white, border-gray-300, dan text-gray-900 --}}
                    <input id="password" name="password" type="password" required 
                           class="focus:ring-emerald-500 focus:border-emerald-500 block w-full pl-10 text-gray-900 bg-white border-gray-300 rounded-lg py-3 placeholder-gray-500 text-sm @error('password') border-red-500 @enderror" 
                           placeholder="••••••••">
                </div>
                @error('password')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- REMEMBER ME & LUPA PASSWORD --}}
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-900"> Ingat saya </label>
                </div>
                <div class="text-sm">
                    <a href="{{ route('password.request') }}" class="font-medium text-emerald-600 hover:text-emerald-500"> Lupa password? </a>
                </div>
            </div>

            {{-- TOMBOL SUBMIT --}}
            <div>
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-lg text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition transform active:scale-98">
                    Masuk Sekarang
                </button>
            </div>
        </form>

        {{-- PEMISAH & GOOGLE LOGIN --}}
        <div class="mt-6">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500"> Atau lanjutkan dengan </span>
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('google.redirect') }}" class="w-full flex items-center justify-center gap-3 px-4 py-3 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 group">
                    {{-- Google Icon SVG --}}
                    <svg class="h-5 w-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    <span class="group-hover:text-gray-900">Sign in with Google</span>
                </a>
            </div>
        </div>
        
    </div>
</div>

@endsection