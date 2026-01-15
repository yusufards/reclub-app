@extends('layouts.guest') {{-- KOREKSI: Menggunakan layout tamu yang benar --}}

@section('title', 'Lupa Password | SportClub')

@section('content')

    {{-- CONTAINER UTAMA --}}
    {{-- min-h-screen agar form berada di tengah vertikal --}}
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-50 md:bg-transparent">

        {{-- CARD FORM: Light Mode, menggunakan background putih --}}
        <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-xl shadow-gray-200 border border-gray-100">

            <div class="text-center">
                {{-- Tambahkan logo untuk branding --}}
                <div class="flex items-center justify-center mb-4">
                    <span
                        class="bg-emerald-500 w-8 h-8 rounded-lg flex items-center justify-center text-sm shadow-lg text-white font-black">S</span>
                    <span class="font-black text-2xl ml-2 text-gray-900">SportClub</span>
                </div>

                <h2 class="text-3xl font-black text-gray-900">Lupa Password?</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Masukkan email Anda, kami akan mengirimkan link untuk mereset password.
                </p>
            </div>

            {{-- STATUS SESSION (Success Message) --}}
            @if (session('status'))
                <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded-lg relative text-sm font-medium"
                    role="alert">
                    <span class="block sm:inline">{{ session('status') }}</span>
                </div>
            @endif

            {{-- FORM --}}
            <form class="mt-8 space-y-6" action="{{ route('password.email') }}" method="POST">
                @csrf
                <div>
                    <label for="email" class="sr-only">Email Address</label>
                    <div class="relative">
                        <input id="email" name="email" type="email" required
                            class="appearance-none rounded-xl relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 focus:z-10 sm:text-sm"
                            placeholder="Masukkan Email Anda" value="{{ old('email') }}">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                    </div>

                    @error('email')
                        <p class="text-red-600 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button type="submit"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition shadow-lg">
                        Kirim Link Reset
                    </button>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="font-medium text-emerald-600 hover:text-emerald-500 transition">
                        &larr; Kembali ke Login
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection