@extends('layouts.app')

@section('title', 'Detail Ternak - FarmGo')
@section('page-title', 'Detail Data Ternak')

@section('content')
    <div class="flex flex-col gap-6" x-data="{ showEditModal: false, showScanModal: false, currentAnimal: @js($animal) }">
        {{-- Back Button --}}
        <div>
            <a href="{{ route('ternak.index') }}"
                class="inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-700 font-medium transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke Daftar
            </a>
        </div>

        {{-- Animal Information Card --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-8 py-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="text-white">
                        <h1 class="text-3xl font-bold mb-2">{{ $animal->nama_hewan }}</h1>
                        <p class="text-emerald-100 text-lg">Kode: <span
                                class="font-semibold">{{ $animal->kode_hewan }}</span></p>
                    </div>
                    <div class="flex gap-3">
                        <button onclick="document.getElementById('editModal').classList.remove('hidden')"
                            class="inline-flex items-center gap-2 bg-white text-emerald-600 px-4 py-2 rounded-lg transition font-medium shadow-sm hover:bg-emerald-50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            Edit Data
                        </button>
                    </div>
                </div>
            </div>

            {{-- Content --}}
            <div class="p-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {{-- QR Code Section --}}
                    <div class="lg:col-span-1">
                        <div class="bg-gray-50 rounded-xl p-6 text-center border border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">QR Code</h3>
                            @if($animal->qr_url)
                                <div class="bg-white p-4 rounded-lg border-4 border-emerald-500 inline-block mb-4">
                                    <img src="{{ $animal->qr_url }}" alt="QR Code" class="w-48 h-48">
                                </div>
                                <button onclick="openScanModal()"
                                    class="w-full inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition font-medium shadow-sm">
                                    <i class="fa-solid fa-camera text-sm mr-2"></i>
                                    <span>Pindai QR</span>
                                </button>
                            @else
                                <div class="text-gray-400 py-8">
                                    <svg class="w-16 h-16 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v1m6 11h2m-6 0h-2v4h2v-4zM6 6h2v2H6V6zm0 12h2v2H6v-2zm12-8h2v2h-2v-2zm-4 0h2v2h-2v-2zm-4 4h2v2h-2v-2zm0-4h2v2h-2v-2zm-8 4h2v2H6v-2zm0-4h2v2H6v-2zm12-4h2v2h-2V6zm-4 0h2v2h-2V6z">
                                        </path>
                                    </svg>
                                    <p class="text-sm">QR Code belum tersedia</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Details Section --}}
                    <div class="lg:col-span-2">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6">Informasi Ternak</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Jenis Hewan --}}
                            <div class="border-l-4 border-emerald-500 pl-4">
                                <p class="text-sm text-gray-500 mb-1">Jenis Hewan</p>
                                <p class="text-lg font-semibold text-gray-800">{{ ucfirst($animal->jenis_hewan) }}</p>
                            </div>

                            {{-- Ras --}}
                            <div class="border-l-4 border-emerald-500 pl-4">
                                <p class="text-sm text-gray-500 mb-1">Ras</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $animal->ras_hewan }}</p>
                            </div>

                            {{-- Jenis Kelamin --}}
                            <div class="border-l-4 border-emerald-500 pl-4">
                                <p class="text-sm text-gray-500 mb-1">Jenis Kelamin</p>
                                <p class="text-lg font-semibold text-gray-800">{{ ucfirst($animal->jenis_kelamin) }}</p>
                            </div>

                            {{-- Tanggal Lahir --}}
                            <div class="border-l-4 border-emerald-500 pl-4">
                                <p class="text-sm text-gray-500 mb-1">Tanggal Lahir</p>
                                <p class="text-lg font-semibold text-gray-800">
                                    {{ \Carbon\Carbon::parse($animal->tanggal_lahir)->format('d M Y') }}
                                </p>
                            </div>

                            {{-- Usia --}}
                            <div class="border-l-4 border-emerald-500 pl-4">
                                <p class="text-sm text-gray-500 mb-1">Usia</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $animal->usia }}</p>
                            </div>

                            {{-- Berat Badan --}}
                            <div class="border-l-4 border-emerald-500 pl-4">
                                <p class="text-sm text-gray-500 mb-1">Berat Badan</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $animal->berat_badan }} kg</p>
                            </div>

                            {{-- Status Ternak --}}
                            <div class="border-l-4 border-emerald-500 pl-4 md:col-span-2">
                                <p class="text-sm text-gray-500 mb-2">Status Ternak</p>
                                @if($animal->status_ternak == 'beli')
                                    <span
                                        class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z">
                                            </path>
                                        </svg>
                                        Beli
                                    </span>
                                @elseif($animal->status_ternak == 'perkawinan')
                                    <span
                                        class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Perkawinan
                                    </span>
                                @else
                                    <span
                                        class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-amber-100 text-amber-800">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732l-3.354 1.935-1.18 4.455a1 1 0 01-1.933 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732l3.354-1.935 1.18-4.455A1 1 0 0112 2z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Hadiah
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Additional Info --}}
                        <div class="mt-8 bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                            <div class="flex">
                                <svg class="w-5 h-5 text-blue-400 mr-3 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <h4 class="text-sm font-semibold text-blue-800 mb-1">Informasi Tambahan</h4>
                                    <p class="text-sm text-blue-700">
                                        Data ini dapat diakses dengan memindai QR Code. Pastikan untuk selalu memperbarui
                                        informasi kesehatan ternak secara berkala.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Parent/Breeding Information --}}
    @if($animal->perkawinan)
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-pink-600 to-pink-700 px-8 py-6">
                <h2 class="text-2xl font-bold text-white">Informasi Kelahiran</h2>
                <p class="text-pink-100 mt-1">Data induk dan perkawinan</p>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Jantan/Pejantan Info --}}
                    <div class="bg-blue-50 rounded-xl p-6 border border-blue-200">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="bg-blue-500 text-white p-3 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-blue-600 font-medium">PEJANTAN</p>
                                @if($animal->perkawinan->jantan_type === 'owned' && $animal->perkawinan->jantan)
                                    <h3 class="text-lg font-bold text-blue-900">{{ $animal->perkawinan->jantan->nama_hewan }}</h3>
                                @elseif($animal->perkawinan->jantan_type === 'external')
                                    <h3 class="text-lg font-bold text-blue-900">
                                        {{ $animal->perkawinan->jantan_external_name ?? 'External' }}
                                    </h3>
                                @elseif($animal->perkawinan->jantan_type === 'semen')
                                    <h3 class="text-lg font-bold text-blue-900">Sperma: {{ $animal->perkawinan->semen_code ?? '-' }}
                                    </h3>
                                @endif
                            </div>
                        </div>
                        <div class="space-y-2 text-sm">
                            @if($animal->perkawinan->jantan_type === 'owned' && $animal->perkawinan->jantan)
                                <div class="flex justify-between">
                                    <span class="text-blue-700">Kode:</span>
                                    <span class="font-medium text-blue-900">{{ $animal->perkawinan->jantan->kode_hewan }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-blue-700">Ras:</span>
                                    <span class="font-medium text-blue-900">{{ $animal->perkawinan->jantan->ras_hewan }}</span>
                                </div>
                            @elseif($animal->perkawinan->jantan_type === 'external')
                                <div class="flex justify-between">
                                    <span class="text-blue-700">Sumber:</span>
                                    <span class="font-medium text-blue-900">External</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-blue-700">Ras:</span>
                                    <span
                                        class="font-medium text-blue-900">{{ $animal->perkawinan->jantan_external_breed ?? '-' }}</span>
                                </div>
                            @elseif($animal->perkawinan->jantan_type === 'semen')
                                <div class="flex justify-between">
                                    <span class="text-blue-700">Produsen:</span>
                                    <span class="font-medium text-blue-900">{{ $animal->perkawinan->semen_producer ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-blue-700">Breed:</span>
                                    <span class="font-medium text-blue-900">{{ $animal->perkawinan->semen_breed ?? '-' }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Betina/Induk Info --}}
                    <div class="bg-pink-50 rounded-xl p-6 border border-pink-200">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="bg-pink-500 text-white p-3 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-pink-600 font-medium">INDUK BETINA</p>
                                <h3 class="text-lg font-bold text-pink-900">{{ $animal->perkawinan->betina->nama_hewan }}</h3>
                            </div>
                        </div>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-pink-700">Kode:</span>
                                <span class="font-medium text-pink-900">{{ $animal->perkawinan->betina->kode_hewan }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-pink-700">Ras:</span>
                                <span class="font-medium text-pink-900">{{ $animal->perkawinan->betina->ras_hewan }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Breeding Details --}}
                <div class="mt-6 bg-gray-50 rounded-xl p-6 border border-gray-200">
                    <h4 class="font-semibold text-gray-900 mb-4">Detail Perkawinan</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <p class="text-gray-600 mb-1">Tanggal Perkawinan:</p>
                            <p class="font-semibold text-gray-900">
                                {{ \Carbon\Carbon::parse($animal->perkawinan->tanggal_perkawinan)->format('d M Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-600 mb-1">Metode:</p>
                            <p class="font-semibold text-gray-900">
                                {{ ucfirst(str_replace('_', ' ', $animal->perkawinan->metode_perkawinan)) }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-600 mb-1">Status:</p>
                            <span
                                class="px-3 py-1 text-xs font-semibold rounded-full 
                                                                                            {{ $animal->perkawinan->status_reproduksi === 'melahirkan' ? 'bg-green-100 text-green-800' :
                ($animal->perkawinan->status_reproduksi === 'bunting' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst($animal->perkawinan->status_reproduksi) }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-300">
                        <a href="{{ route('reproduksi.show', $animal->perkawinan->id) }}"
                            class="inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-700 font-medium transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            Lihat Detail Perkawinan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Health History Section --}}
    @if($healthStats['total_checkups'] > 0)
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6">
                <h2 class="text-2xl font-bold text-white">Riwayat Kesehatan</h2>
                <p class="text-blue-100 mt-1">Tracking perubahan kondisi ternak dari waktu ke waktu</p>
            </div>

            {{-- Statistics Cards --}}
            <div class="p-8 border-b border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Total Checkups --}}
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 border border-purple-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-purple-600 mb-1">Total Pemeriksaan</p>
                                <h3 class="text-3xl font-bold text-purple-900">{{ $healthStats['total_checkups'] }}</h3>
                            </div>
                            <div class="bg-purple-500 text-white p-4 rounded-lg">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Latest Weight --}}
                    <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl p-6 border border-emerald-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-emerald-600 mb-1">Berat Terkini</p>
                                <h3 class="text-3xl font-bold text-emerald-900">{{ $healthStats['latest_weight'] }} <span
                                        class="text-lg">kg</span></h3>
                            </div>
                            <div class="bg-emerald-500 text-white p-4 rounded-lg">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Weight Change --}}
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-blue-600 mb-1">Perubahan Berat</p>
                                <h3
                                    class="text-3xl font-bold {{ $healthStats['weight_change'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $healthStats['weight_change'] >= 0 ? '+' : '' }}{{ $healthStats['weight_change'] }} <span
                                        class="text-lg">kg</span>
                                </h3>
                            </div>
                            <div class="bg-blue-500 text-white p-4 rounded-lg">
                                @if($healthStats['weight_change'] >= 0)
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                @else
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                    </svg>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Weight Chart --}}
            <div class="p-8 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Grafik Perkembangan Berat Badan</h3>
                <div class="bg-gray-50 rounded-xl p-6">
                    <canvas id="weightChart" height="80"></canvas>
                </div>
            </div>

            {{-- Timeline --}}
            <div class="p-8">
                <h3 class="text-lg font-bold text-gray-800 mb-6">Timeline Pemeriksaan</h3>
                <div class="space-y-6">
                    @foreach($animal->healthRecords->sortByDesc('tanggal_pemeriksaan')->values()->take(5) as $index => $record)
                        <div class="relative pl-8 pb-6 {{ $loop->last ? '' : 'border-l-2 border-gray-200' }}">
                            {{-- Timeline Dot --}}
                            <div class="absolute left-0 -ml-2 mt-1">
                                @if($index === 0)
                                    <div class="w-4 h-4 rounded-full bg-emerald-500 border-4 border-white shadow"></div>
                                @else
                                    <div class="w-4 h-4 rounded-full bg-gray-300 border-4 border-white"></div>
                                @endif
                            </div>

                            {{-- Record Card --}}
                            <div
                                class="bg-gray-50 rounded-xl p-6 hover:shadow-md transition {{ $index === 0 ? 'border-2 border-emerald-500' : 'border border-gray-200' }}">
                                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                    {{-- Left: Date & Type --}}
                                    <div>
                                        <div class="flex items-center gap-3 mb-2">
                                            <h4 class="text-lg font-bold text-gray-900">
                                                {{ \Carbon\Carbon::parse($record->tanggal_pemeriksaan)->format('d M Y') }}
                                            </h4>
                                            @if($index === 0)
                                                <span
                                                    class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">
                                                    Terbaru
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-500">
                                            <span class="font-medium">{{ ucfirst($record->jenis_pemeriksaan) }}</span> •
                                            <span
                                                class="text-xs">{{ \Carbon\Carbon::parse($record->tanggal_pemeriksaan)->diffForHumans() }}</span>
                                        </p>
                                    </div>

                                    {{-- Right: Status Badge --}}
                                    <div>
                                        @if($record->status_kesehatan == 'sehat')
                                            <span
                                                class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-emerald-100 text-emerald-800">
                                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                Sehat
                                            </span>
                                        @elseif($record->status_kesehatan == 'sakit')
                                            <span
                                                class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                Sakit
                                            </span>
                                        @elseif($record->status_kesehatan == 'dalam_perawatan')
                                            <span
                                                class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                Dalam Perawatan
                                            </span>
                                        @else
                                            <span
                                                class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                Sembuh
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Details Grid --}}
                                <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                                        <p class="text-xs text-gray-500 mb-1">Berat Badan</p>
                                        <p class="text-lg font-bold text-gray-900">{{ $record->berat_badan }} kg</p>
                                        @if($index > 0)
                                            @php
                                                $prevRecord = $animal->healthRecords[$index - 1];
                                                $weightDiff = $record->berat_badan - $prevRecord->berat_badan;
                                            @endphp
                                            <p class="text-xs {{ $weightDiff >= 0 ? 'text-green-600' : 'text-red-600' }} font-medium">
                                                {{ $weightDiff >= 0 ? '+' : '' }}{{ round($weightDiff, 2) }} kg
                                            </p>
                                        @endif
                                    </div>

                                    @if($record->suhu_tubuh)
                                        <div class="bg-white rounded-lg p-3 border border-gray-200">
                                            <p class="text-xs text-gray-500 mb-1">Suhu Tubuh</p>
                                            <p class="text-lg font-bold text-gray-900">{{ $record->suhu_tubuh }}°C</p>
                                        </div>
                                    @endif

                                    @if($record->biaya)
                                        <div class="bg-white rounded-lg p-3 border border-gray-200">
                                            <p class="text-xs text-gray-500 mb-1">Biaya</p>
                                            <p class="text-lg font-bold text-gray-900">Rp
                                                {{ number_format($record->biaya, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    @endif

                                    @if($record->pemeriksaan_berikutnya)
                                        <div class="bg-white rounded-lg p-3 border border-gray-200">
                                            <p class="text-xs text-gray-500 mb-1">Pemeriksaan Berikutnya</p>
                                            <p class="text-sm font-semibold text-blue-600">
                                                {{ \Carbon\Carbon::parse($record->pemeriksaan_berikutnya)->format('d M Y') }}
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                {{-- Diagnosis & Treatment --}}
                                @if($record->diagnosis || $record->tindakan || $record->obat || $record->catatan)
                                    <div class="mt-4 pt-4 border-t border-gray-200 space-y-3">
                                        @if($record->diagnosis)
                                            <div>
                                                <p class="text-sm font-semibold text-gray-700 mb-1">Diagnosis:</p>
                                                <p class="text-sm text-gray-600">{{ $record->diagnosis }}</p>
                                            </div>
                                        @endif

                                        @if($record->tindakan)
                                            <div>
                                                <p class="text-sm font-semibold text-gray-700 mb-1">Tindakan:</p>
                                                <p class="text-sm text-gray-600">{{ $record->tindakan }}</p>
                                            </div>
                                        @endif

                                        @if($record->obat)
                                            <div>
                                                <p class="text-sm font-semibold text-gray-700 mb-1">Obat:</p>
                                                <p class="text-sm text-gray-600">{{ $record->obat }}</p>
                                            </div>
                                        @endif

                                        @if($record->catatan)
                                            <div>
                                                <p class="text-sm font-semibold text-gray-700 mb-1">Catatan:</p>
                                                <p class="text-sm text-gray-600 italic">{{ $record->catatan }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            @if($animal->healthRecords->count() > 5)
                <div class="mt-6 text-center border-t border-gray-100 pt-4">
                    <a href="{{ route('kesehatan.index', ['animal_id' => $animal->id]) }}"
                        class="text-emerald-600 hover:text-emerald-700 font-medium inline-flex items-center gap-1 transition-colors group">
                        Lihat Semua Riwayat ({{ $animal->healthRecords->count() }})
                        <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3">
                            </path>
                        </svg>
                    </a>
                </div>
            @endif
        </div>
        </div>
    @else
        {{-- Empty State --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden p-12 text-center">
            <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                </path>
            </svg>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Riwayat Kesehatan</h3>
            <p class="text-gray-500 mb-6">Mulai lacak kesehatan ternak dengan melakukan pemeriksaan pertama</p>
            <a href="{{ route('kesehatan.create', ['animal_id' => $animal->id]) }}"
                class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-lg transition font-medium shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Pemeriksaan
            </a>
        </div>
    @endif

    {{-- Modals --}}
    @include('ternak.modals.edit')
    @include('ternak.modals.scan')
    </div>

    {{-- Chart.js for Weight Chart --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @if($healthStats['total_checkups'] > 0)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const ctx = document.getElementById('weightChart');
                if (!ctx) return;

                // Prepare data (reverse to show chronologically)
                const healthRecords = @json($animal->healthRecords->sortBy('tanggal_pemeriksaan')->values());

                const labels = healthRecords.map(record => {
                    const date = new Date(record.tanggal_pemeriksaan);
                    return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
                });

                const weights = healthRecords.map(record => parseFloat(record.berat_badan));

                // Create gradient
                const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 400);
                gradient.addColorStop(0, 'rgba(16, 185, 129, 0.3)');
                gradient.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Berat Badan (kg)',
                            data: weights,
                            borderColor: 'rgb(16, 185, 129)',
                            backgroundColor: gradient,
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            pointBackgroundColor: 'rgb(16, 185, 129)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointHoverBackgroundColor: 'rgb(16, 185, 129)',
                            pointHoverBorderColor: '#fff',
                            pointHoverBorderWidth: 3,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                labels: {
                                    font: {
                                        size: 14,
                                        weight: '600'
                                    },
                                    usePointStyle: true,
                                    padding: 20
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(31, 41, 55, 0.95)',
                                titleFont: {
                                    size: 14,
                                    weight: 'bold'
                                },
                                bodyFont: {
                                    size: 13
                                },
                                padding: 12,
                                borderColor: 'rgb(16, 185, 129)',
                                borderWidth: 2,
                                displayColors: false,
                                callbacks: {
                                    label: function (context) {
                                        return 'Berat: ' + context.parsed.y + ' kg';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: false,
                                ticks: {
                                    callback: function (value) {
                                        return value + ' kg';
                                    },
                                    font: {
                                        size: 12
                                    }
                                },
                                grid: {
                                    color: 'rgba(156, 163, 175, 0.2)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        size: 11
                                    },
                                    maxRotation: 45,
                                    minRotation: 0
                                }
                            }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        }
                    }
                });
            });
        </script>
    @endif

    {{-- QR Scanner Script - nimiq/qr-scanner --}}
    <script type="module">
        import QrScanner from 'https://cdn.jsdelivr.net/npm/qr-scanner@1.4.2/qr-scanner.min.js';

        let qrScanner = null;
        let currentCamera = 'environment'; // 'environment' = back, 'user' = front
        let availableCameras = [];

        window.startScanner = async function () {
            const video = document.getElementById('qr-video');
            const scanResult = document.getElementById('scan-result');
            if (!video) return;

            // Reset scan result
            if (scanResult) scanResult.classList.add('hidden');

            // Check camera support first
            const hasCamera = await QrScanner.hasCamera();
            if (!hasCamera) {
                alert('Perangkat tidak memiliki kamera atau kamera tidak dapat diakses.');
                return;
            }

            // Get available cameras for mobile switch
            try {
                availableCameras = await QrScanner.listCameras(true);
                console.log('Available cameras:', availableCameras);

                // Hide switch button if only 1 camera
                const switchBtn = document.querySelector('[onclick="switchCamera()"]');
                if (switchBtn) {
                    switchBtn.style.display = availableCameras.length > 1 ? 'block' : 'none';
                }
            } catch (e) {
                console.log('Could not list cameras:', e);
            }

            qrScanner = new QrScanner(
                video,
                result => {
                    // Vibrate on success (mobile)
                    if (navigator.vibrate) {
                        navigator.vibrate(200);
                    }

                    document.getElementById('scan-result').classList.remove('hidden');
                    qrScanner.stop();

                    setTimeout(() => {
                        window.location.href = result.data;
                    }, 500);
                },
                {
                    highlightScanRegion: true,
                    highlightCodeOutline: true,
                    preferredCamera: currentCamera,
                    maxScansPerSecond: 5,
                    calculateScanRegion: (video) => {
                        // Square scan region in center for better mobile experience
                        const smallestDimension = Math.min(video.videoWidth, video.videoHeight);
                        const scanRegionSize = Math.round(0.6 * smallestDimension);
                        return {
                            x: Math.round((video.videoWidth - scanRegionSize) / 2),
                            y: Math.round((video.videoHeight - scanRegionSize) / 2),
                            width: scanRegionSize,
                            height: scanRegionSize,
                        };
                    }
                }
            );

            try {
                await qrScanner.start();
                updateCameraLabel();
            } catch (err) {
                console.error('Scanner error:', err);

                let errorMsg = 'Tidak dapat mengakses kamera. ';
                if (err.name === 'NotAllowedError') {
                    errorMsg += 'Izin kamera ditolak. Silakan izinkan akses kamera di pengaturan browser.';
                } else if (err.name === 'NotFoundError') {
                    errorMsg += 'Kamera tidak ditemukan pada perangkat ini.';
                } else if (err.name === 'NotSupportedError' || err.name === 'InsecureContextError') {
                    errorMsg += 'Kamera membutuhkan koneksi HTTPS yang aman.';
                } else {
                    errorMsg += 'Pastikan izin kamera sudah diberikan.';
                }

                alert(errorMsg);
            }
        }

        window.stopScanner = function () {
            if (qrScanner) {
                qrScanner.stop();
                qrScanner.destroy();
                qrScanner = null;
            }
        }

        window.switchCamera = async function () {
            if (!qrScanner) return;

            // Toggle between front and back camera
            currentCamera = currentCamera === 'environment' ? 'user' : 'environment';

            try {
                await qrScanner.setCamera(currentCamera);
                updateCameraLabel();

                // Vibrate feedback (mobile)
                if (navigator.vibrate) {
                    navigator.vibrate(50);
                }
            } catch (err) {
                console.error('Camera switch error:', err);

                // Fallback: cycle through available cameras
                if (availableCameras.length > 1) {
                    const currentIndex = availableCameras.findIndex(c =>
                        c.label.toLowerCase().includes(currentCamera === 'environment' ? 'back' : 'front')
                    );
                    const nextIndex = (currentIndex + 1) % availableCameras.length;

                    try {
                        await qrScanner.setCamera(availableCameras[nextIndex].id);
                        updateCameraLabel();
                    } catch (e) {
                        console.error('Fallback camera switch failed:', e);
                    }
                }
            }
        }

        function updateCameraLabel() {
            const label = document.getElementById('camera-label');
            if (label) {
                label.textContent = currentCamera === 'environment' ? 'Kamera Belakang' : 'Kamera Depan';
            }
        }
    </script>
@endsection