@extends('layouts.app')

@section('title', 'Pengaturan - FarmGo')
@section('page-title', 'Pengaturan')

@section('content')

<div class="max-w-4xl mx-auto space-y-6">

    {{-- Success/Error Messages --}}
    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-start gap-3"
            x-data="{ show: true }" x-show="show" x-transition>
            <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="flex-1">{{ session('success') }}</span>
            <button @click="show = false" class="text-green-600 hover:text-green-800">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
    @endif

    {{-- Profile Photo Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Foto Profil</h2>

        <div class="flex flex-col md:flex-row items-center gap-6">
            {{-- Current Avatar --}}
            <div class="shrink-0">
                @if (Auth::user()->avatar_url)
                    <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}"
                        class="h-32 w-32 rounded-full object-cover shadow-lg border-4 border-white ring-2 ring-emerald-100">
                @else
                    <div
                        class="h-32 w-32 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white font-bold text-3xl shadow-lg border-4 border-white ring-2 ring-emerald-100">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', Auth::user()->name)[1] ?? '', 0, 1)) }}
                    </div>
                @endif
            </div>

            {{-- Upload Form --}}
            <div class="flex-1 w-full">
                <form action="{{ route('settings.update-avatar') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-4">
                    @csrf

                    <div>
                        <label for="avatar" class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih Foto Baru
                        </label>
                        <input type="file" id="avatar" name="avatar" accept="image/jpeg,image/jpg,image/png"
                            class="block w-full text-sm text-gray-600
                                   file:mr-4 file:py-2 file:px-4
                                   file:rounded-lg file:border-0
                                   file:text-sm file:font-semibold
                                   file:bg-emerald-50 file:text-emerald-700
                                   hover:file:bg-emerald-100 file:cursor-pointer
                                   cursor-pointer border border-gray-300 rounded-lg p-2">
                        @error('avatar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">Format: JPG, JPEG, atau PNG. Maksimal 2MB.</p>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                            class="px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition font-medium text-sm">
                            <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                            Upload Foto
                        </button>

                        @if (Auth::user()->avatar)
                            <form action="{{ route('settings.delete-avatar') }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus foto profil?')"
                                    class="px-6 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition font-medium text-sm">
                                    <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                    Hapus Foto
                                </button>
                            </form>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Profile Information Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Informasi Profil</h2>

        <form action="{{ route('settings.update-profile') }}" method="POST" class="space-y-4">
            @csrf

            {{-- Name --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email (Read-only) --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email
                </label>
                <input type="email" id="email" value="{{ Auth::user()->email }}" readonly
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed">
                <p class="mt-1 text-xs text-gray-500">Email tidak dapat diubah.</p>
            </div>

            {{-- Farm Name --}}
            <div>
                <label for="farm_name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Peternakan
                </label>
                <input type="text" id="farm_name" name="farm_name"
                    value="{{ old('farm_name', Auth::user()->farm_name) }}"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                @error('farm_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Phone --}}
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                    Nomor Telepon
                </label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone', Auth::user()->phone) }}"
                    placeholder="08xxxxxxxxxx"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                @error('phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit Button --}}
            <div class="pt-2">
                <button type="submit"
                    class="w-full md:w-auto px-8 py-2.5 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition font-medium">
                    <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    {{-- Change Password Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Keamanan Akun</h2>

        @if (!Auth::user()->password)
            {{-- Set Password Form (for Google-only accounts) --}}
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-blue-800">Akun terdaftar dengan Google</p>
                        <p class="text-sm text-blue-700 mt-1">Anda dapat mengatur password untuk login menggunakan email
                            dan password.</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('settings.set-password') }}" method="POST" class="space-y-4">
                @csrf

                {{-- New Password --}}
                <div>
                    <label for="new_password_set" class="block text-sm font-medium text-gray-700 mb-2">
                        Password Baru <span class="text-red-500">*</span>
                    </label>
                    <input type="password" id="new_password_set" name="new_password" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                    @error('new_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Minimal 8 karakter.</p>
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="new_password_confirmation_set" class="block text-sm font-medium text-gray-700 mb-2">
                        Konfirmasi Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" id="new_password_confirmation_set" name="new_password_confirmation" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                </div>

                {{-- Submit Button --}}
                <div class="pt-2">
                    <button type="submit"
                        class="w-full md:w-auto px-8 py-2.5 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition font-medium">
                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                        Atur Password
                    </button>
                </div>
            </form>
        @else
            {{-- Change Password Form --}}
            <form action="{{ route('settings.update-password') }}" method="POST" class="space-y-4">
                @csrf

                {{-- Current Password --}}
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password Lama <span class="text-red-500">*</span>
                    </label>
                    <input type="password" id="current_password" name="current_password" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- New Password --}}
                <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password Baru <span class="text-red-500">*</span>
                    </label>
                    <input type="password" id="new_password" name="new_password" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                    @error('new_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Minimal 8 karakter.</p>
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Konfirmasi Password Baru <span class="text-red-500">*</span>
                    </label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                </div>

                {{-- Submit Button --}}
                <div class="pt-2">
                    <button type="submit"
                        class="w-full md:w-auto px-8 py-2.5 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition font-medium">
                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                        Ganti Password
                    </button>
                </div>
            </form>
        @endif
    </div>

    {{-- Account Info Card --}}
    <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl shadow-sm border border-emerald-100 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Informasi Akun</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-600 mb-1">Status Akun</p>
                <p class="font-semibold text-gray-800">
                    @if (Auth::user()->isAdmin())
                        <span class="text-purple-600">Administrator</span>
                    @elseif(Auth::user()->isPremium())
                        <span class="text-emerald-600">Premium</span>
                    @else
                        <span class="text-blue-600">Trial</span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-gray-600 mb-1">Metode Login</p>
                <p class="font-semibold text-gray-800">
                    @if (Auth::user()->google_id && !Auth::user()->password)
                        <span class="text-blue-600">Google OAuth</span>
                    @elseif(Auth::user()->google_id && Auth::user()->password)
                        <span class="text-gray-800">Email & Google</span>
                    @else
                        <span class="text-gray-800">Email & Password</span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-gray-600 mb-1">Jumlah Ternak</p>
                <p class="font-semibold text-gray-800">{{ Auth::user()->animals()->count() }} ekor</p>
            </div>
            <div>
                <p class="text-gray-600 mb-1">Bergabung Sejak</p>
                <p class="font-semibold text-gray-800">
                    {{ Auth::user()->created_at->format('d M Y') }}
                </p>
            </div>
        </div>
    </div>

</div>

@endsection

