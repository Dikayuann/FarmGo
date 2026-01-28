@extends('layouts.app')

@section('title', 'Ekspor Data - FarmGo')

@section('content')
    <div>
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Ekspor Data</h1>
            <p class="text-gray-600 mt-2">Ekspor data ternak, kesehatan, dan reproduksi dalam format Excel atau CSV</p>
        </div>

        @php
            $user = Auth::user();
            // Use the User model's built-in export check method
            $isPremium = $user->canExportData();
        @endphp

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        <!-- Trial User Overlay -->
        @if(!$isPremium)
            <div
                class="bg-gradient-to-r from-emerald-50 to-green-50 border-2 border-emerald-200 rounded-xl p-8 mb-6 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Fitur Premium</h3>
                <p class="text-gray-600 mb-6 max-w-2xl mx-auto">
                    Fitur ekspor data hanya tersedia untuk pengguna premium. Upgrade paket Anda sekarang untuk mengakses fitur
                    ini dan fitur premium lainnya.
                </p>
                <a href="{{ route('langganan.index') }}"
                    class="inline-flex items-center gap-2 bg-emerald-600 text-white px-6 py-3 rounded-lg hover:bg-emerald-700 transition font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z">
                        </path>
                    </svg>
                    Upgrade ke Premium
                </a>
            </div>
        @endif

        <!-- Export Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" x-data="{ isPremium: {{ $isPremium ? 'true' : 'false' }} }">

            <!-- Export Data Ternak -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 {{ !$isPremium ? 'opacity-60' : '' }}">
                <div class="flex items-start gap-4 mb-4">
                    <div class="bg-emerald-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">Data Ternak</h3>
                        <p class="text-sm text-gray-600">Ekspor semua data hewan dengan filter</p>
                    </div>
                </div>

                <form action="{{ route('ekspor.animals') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Hewan</label>
                        <select name="jenis_hewan"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                            {{ !$isPremium ? 'disabled' : '' }}>
                            <option value="all">Semua Jenis</option>
                            <option value="sapi">Sapi</option>
                            <option value="kambing">Kambing</option>
                            <option value="domba">Domba</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Kesehatan</label>
                        <select name="status_kesehatan"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                            {{ !$isPremium ? 'disabled' : '' }}>
                            <option value="all">Semua Status</option>
                            <option value="sehat">Sehat</option>
                            <option value="sakit">Sakit</option>
                            <option value="dalam perawatan">Dalam Perawatan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Format</label>
                        <select name="format"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                            {{ !$isPremium ? 'disabled' : '' }}>
                            <option value="excel">Excel (.xlsx)</option>
                            <option value="csv">CSV (.csv)</option>
                        </select>
                    </div>

                    <button type="submit"
                        class="w-full bg-emerald-600 text-white px-4 py-3 rounded-lg hover:bg-emerald-700 transition font-semibold flex items-center justify-center gap-2"
                        {{ !$isPremium ? 'disabled' : '' }}>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Ekspor Data Ternak
                    </button>
                </form>
            </div>

            <!-- Export Riwayat Kesehatan -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 {{ !$isPremium ? 'opacity-60' : '' }}">
                <div class="flex items-start gap-4 mb-4">
                    <div class="bg-blue-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">Riwayat Kesehatan</h3>
                        <p class="text-sm text-gray-600">Ekspor catatan pemeriksaan kesehatan</p>
                    </div>
                </div>

                <form action="{{ route('ekspor.health-records') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Hewan (Opsional)</label>
                        <select name="animal_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                            {{ !$isPremium ? 'disabled' : '' }}>
                            <option value="all">Semua Hewan</option>
                            @foreach($animals as $animal)
                                <option value="{{ $animal->id }}">{{ $animal->nama_hewan }} ({{ $animal->kode_hewan }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                            <input type="date" name="tanggal_dari"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                {{ !$isPremium ? 'disabled' : '' }}>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                            <input type="date" name="tanggal_sampai"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                {{ !$isPremium ? 'disabled' : '' }}>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Format</label>
                        <select name="format"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                            {{ !$isPremium ? 'disabled' : '' }}>
                            <option value="excel">Excel (.xlsx)</option>
                            <option value="csv">CSV (.csv)</option>
                        </select>
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 transition font-semibold flex items-center justify-center gap-2"
                        {{ !$isPremium ? 'disabled' : '' }}>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Ekspor Riwayat Kesehatan
                    </button>
                </form>
            </div>

            <!-- Export Data Reproduksi -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 {{ !$isPremium ? 'opacity-60' : '' }}">
                <div class="flex items-start gap-4 mb-4">
                    <div class="bg-purple-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">Data Reproduksi</h3>
                        <p class="text-sm text-gray-600">Ekspor catatan perkawinan dan kelahiran</p>
                    </div>
                </div>

                <form action="{{ route('ekspor.reproduction') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Reproduksi</label>
                        <select name="status_reproduksi"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                            {{ !$isPremium ? 'disabled' : '' }}>
                            <option value="all">Semua Status</option>
                            <option value="kawin">Kawin</option>
                            <option value="bunting">Bunting</option>
                            <option value="melahirkan">Melahirkan</option>
                            <option value="gagal">Gagal</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                            <input type="date" name="tanggal_dari"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                {{ !$isPremium ? 'disabled' : '' }}>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                            <input type="date" name="tanggal_sampai"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                {{ !$isPremium ? 'disabled' : '' }}>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Format</label>
                        <select name="format"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                            {{ !$isPremium ? 'disabled' : '' }}>
                            <option value="excel">Excel (.xlsx)</option>
                            <option value="csv">CSV (.csv)</option>
                        </select>
                    </div>

                    <button type="submit"
                        class="w-full bg-purple-600 text-white px-4 py-3 rounded-lg hover:bg-purple-700 transition font-semibold flex items-center justify-center gap-2"
                        {{ !$isPremium ? 'disabled' : '' }}>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Ekspor Data Reproduksi
                    </button>
                </form>
            </div>

            <!-- Export Laporan Komprehensif -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 {{ !$isPremium ? 'opacity-60' : '' }}">
                <div class="flex items-start gap-4 mb-4">
                    <div class="bg-amber-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">Laporan Komprehensif</h3>
                        <p class="text-sm text-gray-600">Ekspor semua data dalam satu file Excel</p>
                    </div>
                </div>

                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-4">
                    <p class="text-sm text-amber-800">
                        <strong>Catatan:</strong> Laporan ini berisi semua data ternak, riwayat kesehatan, dan data
                        reproduksi dalam satu file Excel dengan multiple sheets, termasuk ringkasan statistik.
                    </p>
                </div>

                <form action="{{ route('ekspor.comprehensive') }}" method="POST" class="space-y-4">
                    @csrf

                    <button type="submit"
                        class="w-full bg-amber-600 text-white px-4 py-3 rounded-lg hover:bg-amber-700 transition font-semibold flex items-center justify-center gap-2"
                        {{ !$isPremium ? 'disabled' : '' }}>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Ekspor Laporan Lengkap
                    </button>
                </form>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            // Show success notification when download starts
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    if (!this.querySelector('button[type="submit"]').disabled) {
                        setTimeout(() => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Ekspor Berhasil!',
                                text: 'File Anda sedang diunduh...',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }, 500);
                    }
                });
            });
        </script>
    @endpush

@endsection