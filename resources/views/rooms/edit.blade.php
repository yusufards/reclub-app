@extends('layouts.app')

@section('title', 'Edit Room: ' . $room->title . ' | SportClub')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <div class="mb-6">
            <a href="{{ route('rooms.show', $room) }}" class="text-emerald-600 hover:text-emerald-800 text-sm font-medium">←
                Kembali ke Detail Room</a>
        </div>

        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">

            {{-- HEADER CARD (Warna Emerald/Teal) --}}
            <div class="px-8 py-10 bg-gradient-to-br from-emerald-600 to-teal-700 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl"></div>
                <div
                    class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-emerald-400 opacity-10 rounded-full blur-2xl">
                </div>

                <div class="relative z-10">
                    <h2 class="text-3xl font-black tracking-tight">Edit Room</h2>
                    <p class="text-emerald-100 mt-1 text-sm font-medium">Perbarui detail Room: {{ $room->title }}</p>
                </div>
            </div>

            <div class="p-8 lg:p-12">

                {{-- FORM EDIT (UPDATE) --}}
                <form action="{{ route('rooms.update', $room) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- BAGIAN 1: INFORMASI AKTIVITAS --}}
                    <div class="mb-10">
                        <h3 class="text-emerald-900 font-bold text-lg mb-6 flex items-center gap-2">
                            <span
                                class="bg-emerald-100 text-emerald-600 w-8 h-8 rounded-full flex items-center justify-center text-sm font-black">1</span>
                            Informasi Aktivitas
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                            {{-- Judul Room --}}
                            <div class="md:col-span-2">
                                <label for="title" class="block text-sm font-bold text-gray-700 mb-2">Judul Room <span
                                        class="text-red-500">*</span></label>
                                <input type="text" id="title" name="title" value="{{ old('title', $room->title) }}"
                                    class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 block px-4 py-3.5 transition placeholder-gray-400 font-medium"
                                    placeholder="Contoh: Mabar Futsal Santai Jumat Malam" required>
                                @error('title') <p class="text-red-500 text-xs mt-1 font-semibold ml-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Olahraga --}}
                            <div>
                                <label for="sport_id" class="block text-sm font-bold text-gray-700 mb-2">Cabang Olahraga
                                    <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <select id="sport_id" name="sport_id"
                                        class="appearance-none w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 block px-4 py-3.5 transition cursor-pointer font-medium">
                                        <option value="" disabled class="text-gray-400">Pilih Olahraga</option>
                                        @foreach($sports as $s)
                                            <option value="{{ $s->id }}" {{ (old('sport_id', $room->sport_id) == $s->id) ? 'selected' : '' }}>{{ $s->name }}</option>
                                        @endforeach
                                    </select>
                                    <div
                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('sport_id') <p class="text-red-500 text-xs mt-1 font-semibold ml-1">{{ $message }}
                                </p> @enderror
                            </div>

                            {{-- Lokasi Lapangan --}}
                            <div>
                                <label for="venue_id" class="block text-sm font-bold text-gray-700 mb-2">Lokasi Lapangan
                                    <span class="text-red-500">*</span></label>

                                @if(old('venue_id', $room->venue_id))
                                    <div
                                        class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-2 rounded-xl mb-2 text-xs font-bold flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Lokasi terpilih: {{ $room->venue->name ?? 'Venue tidak diketahui' }}
                                    </div>
                                @endif

                                <div class="relative">
                                    <select id="venue_id" name="venue_id" required
                                        class="appearance-none w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 block px-4 py-3.5 transition cursor-pointer font-medium">
                                        <option value="" disabled class="text-gray-400">Pilih Lokasi</option>
                                        @foreach($venues as $venue)
                                            <option value="{{ $venue->id }}" {{ (old('venue_id', $room->venue_id) == $venue->id) ? 'selected' : '' }}>
                                                {{ $venue->name }} - {{ $venue->city }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div
                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('venue_id') <p class="text-red-500 text-xs mt-1 font-semibold ml-1">{{ $message }}
                                </p> @enderror
                            </div>

                            {{-- Catatan Tambahan --}}
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-bold text-gray-700 mb-2">Catatan Tambahan
                                    <span class="text-gray-400 font-normal">(Opsional)</span></label>
                                <textarea id="description" name="description" rows="3"
                                    class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 block px-4 py-3 transition placeholder-gray-400 font-medium"
                                    placeholder="Tulis info penting: bawa raket sendiri, patungan di lokasi, level pemula welcome, dll.">{{ old('description', $room->description) }}</textarea>
                                @error('description') <p class="text-red-500 text-xs mt-1 font-semibold ml-1">{{ $message }}
                                </p> @enderror
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-100 mb-10">

                    {{-- BAGIAN 2: JADWAL & BIAYA --}}
                    <div class="mb-10">
                        <h3 class="text-emerald-900 font-bold text-lg mb-6 flex items-center gap-2">
                            <span
                                class="bg-emerald-100 text-emerald-600 w-8 h-8 rounded-full flex items-center justify-center text-sm font-black">2</span>
                            Jadwal & Biaya
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                            {{-- Waktu Mulai --}}
                            <div>
                                <label for="start_datetime" class="block text-sm font-bold text-gray-700 mb-2">Waktu Mulai
                                    <span class="text-red-500">*</span></label>
                                @php
                                    // Menggunakan format yang sudah disesuaikan untuk kompatibilitas browser
                                    $dateTimeValue = old('start_datetime', $room->start_datetime->format('Y-m-d\TH:i'));
                                @endphp
                                <input type="datetime-local" id="start_datetime" name="start_datetime"
                                    value="{{ $dateTimeValue }}"
                                    class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 block px-4 py-3.5 transition font-medium"
                                    required>
                                @error('start_datetime') <p class="text-red-500 text-xs mt-1 font-semibold ml-1">
                                    {{ $message }}
                                </p> @enderror
                            </div>

                            {{-- Maks. Peserta --}}
                            <div>
                                <label for="max_participants" class="block text-sm font-bold text-gray-700 mb-2">Maks.
                                    Peserta <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="number" id="max_participants" name="max_participants"
                                        value="{{ old('max_participants', $room->max_participants) }}"
                                        class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 block px-4 py-3.5 transition font-bold text-center">
                                    <div
                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400 text-sm font-bold bg-gray-100 rounded-r-xl border-l border-gray-200">
                                        Orang
                                    </div>
                                </div>
                                @error('max_participants') <p class="text-red-500 text-xs mt-1 font-semibold ml-1">
                                    {{ $message }}
                                </p> @enderror
                            </div>

                            {{-- Total Biaya Lapangan --}}
                            <div>
                                <label for="total_cost" class="block text-sm font-bold text-gray-700 mb-2">Total Biaya
                                    Lapangan</label>
                                <div class="relative">
                                    <div
                                        class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-4 text-emerald-600 font-black text-sm">
                                        Rp
                                    </div>
                                    <input type="number" step="1000" id="total_cost" name="total_cost"
                                        value="{{ old('total_cost', $room->total_cost) }}"
                                        class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 block pl-12 pr-4 py-3.5 transition font-bold"
                                        placeholder="0">
                                </div>
                                <p class="text-[10px] text-gray-400 mt-1 ml-1">*Biaya ini akan dibagi rata antar peserta.
                                </p>
                                @error('total_cost') <p class="text-red-500 text-xs mt-1 font-semibold ml-1">{{ $message }}
                                </p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- SUBMIT BUTTON --}}
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold py-4 rounded-xl shadow-lg hover:shadow-emerald-200/50 transition transform active:scale-[0.99] flex justify-center items-center gap-2 text-lg mt-10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Perubahan Room
                    </button>
                </form>

                {{-- TOMBOL HAPUS (DELETE) --}}
                <div class="mt-8 pt-8 border-t border-gray-100">
                    <h3 class="text-lg font-bold text-red-800 mb-2">Hapus Room</h3>
                    <p class="text-sm text-gray-600 mb-4">Aksi ini tidak dapat dibatalkan. Room dan semua data partisipan
                        akan hilang.</p>

                    <form action="{{ route('rooms.destroy', $room) }}" method="POST"
                        onsubmit="return confirm('ANDA YAKIN INGIN MENGHAPUS ROOM INI?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-xl text-sm transition shadow-md flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                            Hapus Room Permanen
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // FUNGSI UNTUK MENGAMANKAN DATETIME SAAT DI-EDIT
        document.addEventListener('DOMContentLoaded', function () {
            const datetimeInput = document.getElementById('start_datetime');

            // HANYA JALANKAN JIKA BROWSER ADALAH FIREFOX (Menerapkan Workaround yang sudah ada)
            if (navigator.userAgent.indexOf("Firefox") !== -1 && datetimeInput.value) {
                // Firefox tidak menangani format YYYY-MM-DDTHH:MM dengan benar saat di-set dari PHP.
                // Kita ubah T menjadi spasi sebagai workaround, lalu di-convert oleh browser.
                let correctFormat = datetimeInput.value.replace('T', ' ');
                datetimeInput.value = correctFormat;
            }
        });
    </script>
@endpush