@extends('layouts.app')

@section('title', 'Pengaturan Akun | SportClub')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    {{-- HEADER --}}
    <div class="mb-10">
        <h1 class="text-3xl font-black text-gray-800 tracking-tight">Pengaturan Akun</h1>
        <p class="text-gray-500 mt-1 text-lg">Kelola informasi profil, privasi, dan keamanan Anda.</p>
    </div>

    {{-- ALERT NOTIFIKASI --}}
    @if(session('success'))
    <div class="mb-8 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl shadow-sm flex items-center justify-between animate-fade-in-down">
        <div class="flex items-center">
            <div class="bg-emerald-100 p-1.5 rounded-full mr-3">
                <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-600 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-8 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl shadow-sm flex items-center justify-between animate-fade-in-down">
        <div class="flex items-center">
            <div class="bg-red-100 p-1.5 rounded-full mr-3">
                <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <p class="text-sm font-bold text-red-800">{{ session('error') }}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- KOLOM KIRI: EDIT DATA DIRI --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                {{-- Card Header --}}
                <div class="px-8 py-6 bg-gradient-to-r from-emerald-600 to-teal-600">
                    <h2 class="text-white font-bold text-lg flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Edit Profil
                    </h2>
                    <p class="text-emerald-100 text-xs mt-1">Perbarui informasi pribadi Anda di sini.</p>
                </div>
                
                <div class="p-8">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Avatar Upload --}}
                        <div class="flex flex-col sm:flex-row items-center gap-8 mb-10">
                            <div class="relative group cursor-pointer">
                                <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-emerald-50 shadow-lg ring-2 ring-emerald-100">
                                    @if(Auth::user()->avatar)
                                        <img id="preview-avatar" src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-full h-full object-cover transition duration-300 group-hover:scale-105">
                                    @else
                                        <img id="preview-avatar" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=10b981&color=fff&size=256&bold=true" class="w-full h-full object-cover transition duration-300 group-hover:scale-105">
                                    @endif
                                </div>
                                {{-- Overlay Upload Icon --}}
                                <label for="avatar-upload" class="absolute inset-0 flex items-center justify-center bg-black/30 text-white opacity-0 group-hover:opacity-100 transition-all duration-300 rounded-full cursor-pointer backdrop-blur-[2px]">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </label>
                            </div>
                            
                            <div class="flex-1 text-center sm:text-left w-full">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Foto Profil</label>
                                <input type="file" name="avatar" id="avatar-upload" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition cursor-pointer" accept="image/*" onchange="previewImage(event)">
                                <p class="text-xs text-gray-400 mt-2">Format: JPG, PNG. Maks 2MB.</p>
                                @error('avatar') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 px-4 py-3 transition font-medium">
                                @error('name') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nomor WhatsApp</label>
                                <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 px-4 py-3 transition font-medium" placeholder="08...">
                                @error('phone') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="mb-10">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Bio Singkat</label>
                            <textarea name="bio" rows="3" class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 px-4 py-3 transition font-medium" placeholder="Contoh: Kiper Futsal, Main Santai, Cari Keringat">{{ old('bio', Auth::user()->bio) }}</textarea>
                            <div class="flex justify-between mt-1">
                                @error('bio') 
                                    <p class="text-red-500 text-xs font-bold">{{ $message }}</p> 
                                @else
                                    <span></span>
                                @enderror
                                <p class="text-xs text-gray-400">Maksimal 100 karakter.</p>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-gray-50">
                            <button type="submit" class="bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:shadow-emerald-200/50 transition transform active:scale-95 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: PASSWORD & INFO AKUN --}}
        <div class="lg:col-span-1 space-y-8">
            
            {{-- Card Ganti Password --}}
            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="p-6 bg-gray-50 border-b border-gray-100">
                    <h2 class="text-gray-800 font-bold text-lg flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        {{ !is_null(Auth::user()->password) ? 'Ganti Password' : 'Buat Password Baru' }}
                    </h2>
                </div>
                <div class="p-6">
                    
                    @if(is_null(Auth::user()->password))
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-r-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs text-blue-700 leading-relaxed font-medium">
                                        Anda login via <strong>Google</strong>. Buat password agar bisa login manual.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        @if(!is_null(Auth::user()->password))
                        <div class="mb-4 relative">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1">Password Lama</label>
                            <div class="relative">
                                <input type="password" name="current_password" id="current_password" class="w-full bg-gray-50 border border-gray-200 rounded-xl text-sm px-4 py-2.5 focus:ring-emerald-500 focus:border-emerald-500 pr-10 transition">
                                <button type="button" onclick="togglePassword('current_password')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </button>
                            </div>
                            @error('current_password') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>
                        @endif

                        <div class="mb-4 relative">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1">Password Baru</label>
                            <div class="relative">
                                <input type="password" name="password" id="password" class="w-full bg-gray-50 border border-gray-200 rounded-xl text-sm px-4 py-2.5 focus:ring-emerald-500 focus:border-emerald-500 pr-10 transition">
                                <button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </button>
                            </div>
                            @error('password') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-6 relative">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1">Ulangi Password</label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="password_confirmation" class="w-full bg-gray-50 border border-gray-200 rounded-xl text-sm px-4 py-2.5 focus:ring-emerald-500 focus:border-emerald-500 pr-10 transition">
                                <button type="button" onclick="togglePassword('password_confirmation')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white font-bold py-3 rounded-xl transition text-sm shadow-md transform active:scale-95 flex justify-center items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            {{ !is_null(Auth::user()->password) ? 'Update Password' : 'Simpan Password' }}
                        </button>
                    </form>
                </div>
            </div>

            {{-- Card Info Akun (Warna Emerald/Teal) --}}
            <div class="bg-emerald-50 rounded-2xl p-6 border border-emerald-100 shadow-sm">
                <h3 class="text-emerald-800 font-bold mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Info Akun
                </h3>
                <div class="space-y-3 text-sm text-emerald-700">
                    <p>Status: <span class="font-bold uppercase bg-emerald-200 px-2 py-0.5 rounded text-xs text-emerald-900">{{ Auth::user()->role }}</span></p>
                    <p>Email: <span class="font-medium text-emerald-800">{{ Auth::user()->email }}</span></p>
                    <p>Bergabung: <span class="font-medium text-emerald-800">{{ Auth::user()->created_at->format('d M Y') }}</span></p>
                </div>
            </div>

            @if(Auth::user()->role !== 'admin')
            <div class="bg-red-50 rounded-2xl p-6 border border-red-100 shadow-sm mt-8 opacity-80 hover:opacity-100 transition">
                <div class="flex items-start gap-3">
                    <div class="bg-red-100 p-2 rounded-full text-red-600 flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-red-800 font-bold text-lg">Hapus Akun</h3>
                        <p class="text-sm text-red-600 mt-1 mb-4 leading-relaxed">
                            Tindakan ini tidak dapat dibatalkan. Semua data riwayat mabar Anda akan dihapus permanen.
                        </p>
                        
                        <form action="{{ route('profile.destroy') }}" method="POST" onsubmit="return confirm('PERINGATAN KERAS:\n\nApakah Anda yakin ingin menghapus akun ini secara PERMANEN?\nSemua data Anda akan hilang dan tidak bisa dikembalikan.\n\nKlik OK untuk melanjutkan.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-white border border-red-300 text-red-600 hover:bg-red-600 hover:text-white font-bold py-2 px-4 rounded-xl text-sm transition shadow-sm flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Ya, Hapus Akun Saya
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

<script>
// Fungsi Preview Foto Profil
function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.getElementById('preview-avatar');
        output.src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}

// Fungsi Show/Hide Password
function togglePassword(fieldId) {
    const input = document.getElementById(fieldId);
    if (input.type === "password") {
        input.type = "text";
    } else {
        input.type = "password";
    }
}
</script>
@endsection