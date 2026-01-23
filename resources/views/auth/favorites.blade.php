@extends('layouts.app')

@section('content')
    <div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-50/50">
        <div class="max-w-2xl w-full space-y-8">

            {{-- HEADER --}}
            <div class="text-center">
                <div
                    class="mx-auto h-16 w-16 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-200 mb-6 transform rotate-3">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                        </path>
                    </svg>
                </div>
                <h2 class="mt-2 text-3xl font-black text-gray-900 tracking-tight">
                    Sesuaikan Minatmu
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Pilih olahraga favorit dan lokasi mainmu agar kami bisa memberi rekomendasi mabar terbaik di sekitarmu.
                </p>
            </div>

            {{-- CARD CONTENT --}}
            <div
                class="bg-white py-8 px-4 shadow-xl border border-gray-100 sm:rounded-3xl sm:px-10 relative overflow-hidden">
                {{-- Decorative bg element --}}
                <div
                    class="absolute top-0 right-0 -mr-16 -mt-16 w-48 h-48 rounded-full bg-emerald-50 blur-3xl opacity-50 pointer-events-none">
                </div>

                @if(session('success'))
                    <div
                        class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center gap-3 text-sm font-medium">
                        <svg class="w-5 h-5 flex-shrink-0 text-emerald-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm font-medium">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('preferences.update') }}" method="POST" id="preferencesForm"
                    class="space-y-8 relative">
                    @csrf

                    {{-- 1. OLAHRAGA --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-4 flex justify-between items-center">
                            <span>Olahraga Favorit <span class="text-red-500">*</span></span>
                            <span class="text-xs font-normal text-emerald-600 bg-emerald-50 px-2 py-1 rounded-md">Pilih Min.
                                3</span>
                        </label>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach($sports as $sport)
                                <div class="relative">
                                    <input type="checkbox" name="sports[]" id="sport_{{ $sport->id }}" value="{{ $sport->id }}"
                                        class="peer sr-only" {{ in_array($sport->id, $userSports ?? []) ? 'checked' : '' }}>

                                    <label for="sport_{{ $sport->id }}"
                                        class="flex items-center justify-center w-full px-4 py-3 text-sm font-bold text-gray-500 bg-white border-2 border-gray-100 rounded-xl cursor-pointer hover:border-emerald-200 hover:bg-emerald-50/50 transition-all duration-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-700 shadow-sm">
                                        {{ $sport->name }}
                                    </label>

                                    {{-- Checkmark Icon --}}
                                    <div
                                        class="absolute top-0 right-0 -mt-2 -mr-2 w-6 h-6 bg-emerald-500 rounded-full text-white flex items-center justify-center opacity-0 transform scale-0 peer-checked:opacity-100 peer-checked:scale-100 transition-all duration-200 shadow-md">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- 2. LOKASI --}}
                    <div class="pt-6 border-t border-gray-100">
                        <label class="block text-sm font-bold text-gray-800 mb-2">Lokasi Anda <span
                                class="text-red-500">*</span></label>
                        <p class="text-xs text-gray-500 mb-4">Kami mendeteksi lokasi untuk mencarikan teman mabar terdekat.
                        </p>

                        <div class="flex gap-3">
                            <button type="button" onclick="getLocation()"
                                class="flex-shrink-0 bg-gray-800 hover:bg-gray-900 text-white px-4 py-3 rounded-xl font-bold text-sm transition shadow-md flex items-center gap-2 active:scale-95">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Deteksi Lokasi
                            </button>
                            <div class="flex-grow relative">
                                <input type="text" id="locationStatus" readonly
                                    class="w-full bg-gray-50 border border-gray-200 text-gray-600 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 block w-full p-3 pl-10"
                                    placeholder="Belum ada lokasi..."
                                    value="{{ $user->latitude ? $user->latitude . ', ' . $user->longitude : '' }}">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 20l-5.447-2.724A1 1 0 014 16.297V6.883a1 1 0 01.382-.832l6.09-4.06a1 1 0 011.056 0l6.09 4.06a1 1 0 01.382.832v9.414a1 1 0 01-1.553.704L15 17.294m6-2.147L13.882 18.9m1.118-3.753v9.06m-6.14-11.234L3.618 3.882m16.764 0L15 7.152M9.118 7.152L3.618 10.904">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="latitude" id="latitude" value="{{ $user->latitude }}">
                        <input type="hidden" name="longitude" id="longitude" value="{{ $user->longitude }}">
                    </div>

                    {{-- SUBMIT --}}
                    <div class="pt-4">
                        <button type="submit"
                            class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 shadow-lg shadow-emerald-200/50 transition transform active:scale-[0.98]">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-emerald-200 group-hover:text-emerald-100 transition ease-in-out duration-150"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </span>
                            Simpan & Lanjutkan
                        </button>
                        <p class="mt-4 text-center text-xs text-gray-400">
                            Anda bisa mengubah pengaturan ini kapan saja di menu Profil.
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function getLocation() {
            const status = document.getElementById('locationStatus');
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');

            if (!navigator.geolocation) {
                status.value = "Browser tidak support Geolocation.";
                status.classList.add('text-red-500');
            } else {
                status.value = "Mencari koordinat...";
                status.classList.remove('text-red-500');
                status.classList.add('text-emerald-600', 'animate-pulse');

                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        latInput.value = position.coords.latitude;
                        lngInput.value = position.coords.longitude;
                        status.value = `Lokasi Terkunci: ${position.coords.latitude.toFixed(6)}, ${position.coords.longitude.toFixed(6)}`;
                        status.classList.remove('animate-pulse', 'text-gray-600');
                        status.classList.add('text-emerald-700', 'font-bold', 'bg-emerald-50');
                    },
                    (error) => {
                        status.classList.remove('animate-pulse');
                        status.classList.add('text-red-500');
                        switch (error.code) {
                            case error.PERMISSION_DENIED:
                                status.value = "Izin lokasi ditolak via browser.";
                                break;
                            case error.POSITION_UNAVAILABLE:
                                status.value = "Info lokasi tidak tersedia.";
                                break;
                            case error.TIMEOUT:
                                status.value = "Waktu permintaan habis.";
                                break;
                            default:
                                status.value = "Error tidak diketahui.";
                                break;
                        }
                    }
                );
            }
        }
    </script>
@endsection