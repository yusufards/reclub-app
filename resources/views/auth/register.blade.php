@extends('layouts.guest')

@section('title', 'Daftar Akun Baru | SportClub')

@section('content')

{{-- CONTAINER UTAMA: Overrides background hitam dari guest layout dengan container putih --}}
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-50 md:bg-transparent">
    
    {{-- CARD FORM: Light Mode, menggunakan background putih --}}
    <div class="max-w-md w-full space-y-8 p-8 md:p-10 bg-white rounded-xl shadow-2xl shadow-gray-200 border border-gray-100">
        
        {{-- HEADER FORM (Teks Gelap) --}}
        <div class="text-center">
            <div class="flex items-center justify-center mb-4">
                <span class="bg-emerald-500 w-8 h-8 rounded-lg flex items-center justify-center text-sm shadow-lg text-white font-black">S</span>
                <span class="font-black text-2xl ml-2 text-gray-900">SportClub</span>
            </div>
            <h2 class="mt-4 text-3xl font-extrabold text-gray-900">
                Daftar Akun Baru
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Bergabung dengan komunitas olahraga sekarang.
            </p>
        </div>

        {{-- FORM REGISTER --}}
        <form class="space-y-5" action="{{ route('register') }}" method="POST">
            @csrf
            
            {{-- Name Input (Border dan Teks Gelap) --}}
            <div>
                <label for="name" class="sr-only">Nama Lengkap</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required 
                       class="focus:ring-emerald-500 focus:border-emerald-500 block w-full px-4 py-3 text-gray-900 bg-white border-gray-300 rounded-lg placeholder-gray-500 text-sm @error('name') border-red-500 @enderror" 
                       placeholder="Nama Lengkap">
                @error('name') 
                    <p class="text-red-600 text-xs mt-1 font-medium">{{ $message }}</p> 
                @enderror
            </div>

            {{-- Email Input (Border dan Teks Gelap) --}}
            <div>
                <label for="email" class="sr-only">Alamat Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required 
                       class="focus:ring-emerald-500 focus:border-emerald-500 block w-full px-4 py-3 text-gray-900 bg-white border-gray-300 rounded-lg placeholder-gray-500 text-sm @error('email') border-red-500 @enderror" 
                       placeholder="Alamat Email">
                @error('email') 
                    <p class="text-red-600 text-xs mt-1 font-medium">{{ $message }}</p> 
                @enderror
            </div>

            {{-- WhatsApp Input (Border dan Teks Gelap) --}}
            <div>
                <label for="phone" class="sr-only">Nomor WhatsApp</label>
                <input id="phone" type="text" name="phone" value="{{ old('phone') }}" placeholder="Nomor WhatsApp (Cth: 08123456789)" 
                       class="focus:ring-emerald-500 focus:border-emerald-500 block w-full px-4 py-3 text-gray-900 bg-white border-gray-300 rounded-lg placeholder-gray-500 text-sm @error('phone') border-red-500 @enderror">
                @error('phone') 
                    <p class="text-red-600 text-xs mt-1 font-medium">{{ $message }}</p> 
                @enderror
            </div>

            {{-- Password Input (Border dan Teks Gelap) --}}
            <div>
                <label for="password" class="sr-only">Password</label>
                <input id="password" type="password" name="password" required 
                       class="focus:ring-emerald-500 focus:border-emerald-500 block w-full px-4 py-3 text-gray-900 bg-white border-gray-300 rounded-lg placeholder-gray-500 text-sm @error('password') border-red-500 @enderror"
                       placeholder="Password (Min. 8 Karakter)">
                @error('password') 
                    <p class="text-red-600 text-xs mt-1 font-medium">{{ $message }}</p> 
                @enderror
            </div>

            {{-- Konfirmasi Password Input (Border dan Teks Gelap) --}}
            <div>
                <label for="password_confirmation" class="sr-only">Konfirmasi Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required 
                       class="focus:ring-emerald-500 focus:border-emerald-500 block w-full px-4 py-3 text-gray-900 bg-white border-gray-300 rounded-lg placeholder-gray-500 text-sm @error('password') border-red-500 @enderror"
                       placeholder="Konfirmasi Password">
                @error('password_confirmation') 
                    @if($message == 'Konfirmasi password tidak sesuai.')
                        <p class="text-red-600 text-xs mt-1 font-medium">{{ $message }}</p>
                    @endif 
                @enderror
            </div>

            {{-- TOMBOL SUBMIT --}}
            <div class="pt-2">
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-lg text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition transform active:scale-98">
                    Daftar Akun Sekarang
                </button>
            </div>
        </form>

        {{-- PEMISAH & LINK LOGIN --}}
        <div class="mt-6">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500"> Sudah punya akun? </span>
                </div>
            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="font-bold text-emerald-600 hover:text-emerald-500 hover:underline">
                    Masuk di sini
                </a>
            </div>
        </div>
        
    </div>
</div>

@endsection