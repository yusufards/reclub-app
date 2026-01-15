@extends('layouts.guest') {{-- KOREKSI: Menggunakan layout tamu yang benar --}}

@section('title', 'Atur Ulang Password | SportClub')

@section('content')

    {{-- CONTAINER UTAMA --}}
    {{-- min-h-screen agar form berada di tengah vertikal. bg-gray-50/md:bg-transparent untuk Light Mode. --}}
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-50 md:bg-transparent">

        {{-- CARD FORM: Light Mode --}}
        <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-xl shadow-gray-200 border border-gray-100">

            <div class="text-center">
                {{-- Logo dan Branding --}}
                <div class="flex items-center justify-center mb-4">
                    <span
                        class="bg-emerald-500 w-8 h-8 rounded-lg flex items-center justify-center text-sm shadow-lg text-white font-black">S</span>
                    <span class="font-black text-2xl ml-2 text-gray-900">SportClub</span>
                </div>

                <h2 class="text-3xl font-black text-gray-900">Atur Ulang Password</h2>
                <p class="mt-2 text-sm text-gray-600">Buat password baru untuk akun Anda.</p>
            </div>

            {{-- STATUS SESSION (Success/Error Messages) --}}
            @if (session('status'))
                <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded-lg relative text-sm font-medium"
                    role="alert">
                    <span class="block sm:inline">{{ session('status') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative text-sm font-medium"
                    role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            {{-- FORM --}}
            {{-- KOREKSI: Action form adalah 'password.store' (sesuai standar Laravel) jika menggunakan token, atau
            'password.update' jika menggunakan route kustom. Saya gunakan 'password.store' untuk konsistensi dengan Reset
            Password (Blade defaultnya menggunakan password.store, tapi di kode Anda menggunakan password.update, saya
            biarkan 'password.update' untuk menjaga kode Anda, tapi pastikan rutenya benar) --}}
            <form class="mt-8 space-y-6" action="{{ route('password.update') }}" method="POST">
                @csrf

                {{-- Hidden Token --}}
                {{-- Menggunakan $request->route('token') untuk mendapatkan token dari URL --}}
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="space-y-4">

                    {{-- EMAIL (Readonly) --}}
                    <div>
                        <label for="email" class="sr-only">Email</label>
                        {{-- Nilai email diambil dari URL query string (request('email')) atau $email --}}
                        <input id="email" name="email" type="email" required
                            class="appearance-none rounded-xl block w-full px-4 py-3 border border-gray-300 text-gray-700 bg-gray-100 cursor-not-allowed focus:outline-none focus:z-10"
                            value="{{ $email ?? old('email', request('email')) }}" readonly>
                        @error('email') <p class="text-red-600 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    {{-- PASSWORD BARU --}}
                    <div>
                        <label for="password" class="sr-only">Password Baru</label>
                        <input id="password" name="password" type="password" required
                            class="appearance-none rounded-xl block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:ring-emerald-500 focus:border-emerald-500 focus:z-10 sm:text-sm"
                            placeholder="Password Baru (Minimal 8 karakter)">
                        @error('password') <p class="text-red-600 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    {{-- KONFIRMASI PASSWORD BARU --}}
                    <div>
                        <label for="password_confirmation" class="sr-only">Konfirmasi Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                            class="appearance-none rounded-xl block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:ring-emerald-500 focus:border-emerald-500 focus:z-10 sm:text-sm"
                            placeholder="Konfirmasi Password Baru">
                    </div>
                    {{-- Error konfirmasi password biasanya muncul di error('password') --}}
                </div>

                {{-- TOMBOL SUBMIT --}}
                <button type="submit"
                    class="w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition shadow-lg mt-6">
                    Simpan Password Baru
                </button>
            </form>
        </div>
    </div>
@endsection