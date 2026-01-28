@extends('layouts.app')

@section('title', 'Detail Reproduksi')

@section('content')
    <div x-data="{ showDeleteModal: false }">
        <!-- Header with Back Button -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('reproduksi.index') }}" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900">Detail Catatan Reproduksi</h2>
                    <p class="text-sm text-gray-500">Kode Perkawinan #{{ $perkawinan->id }}</p>
                </div>
            </div>

            @php
                $statusColors = [
                    'menunggu' => 'bg-gray-100 text-gray-800',
                    'bunting' => 'bg-purple-100 text-purple-800',
                    'melahirkan' => 'bg-green-100 text-green-800',
                    'gagal' => 'bg-red-100 text-red-800',
                ];
            @endphp
            <span class="px-4 py-2 text-sm font-medium rounded-xl {{ $statusColors[$perkawinan->status_reproduksi] }}">
                {{ ucfirst($perkawinan->status_reproduksi) }}
            </span>
        </div>

        <!-- Parent Animals Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Jantan Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-blue-100 text-blue-600 h-12 w-12 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">PEJANTAN</p>
                        @if($perkawinan->jantan_type === 'owned' && $perkawinan->jantan)
                            <h3 class="text-lg font-semibold text-gray-900">{{ $perkawinan->jantan->nama_hewan }}</h3>
                        @elseif($perkawinan->jantan_type === 'external')
                            <h3 class="text-lg font-semibold text-gray-900">
                                {{ $perkawinan->jantan_external_name ?? 'External' }}
                            </h3>
                        @elseif($perkawinan->jantan_type === 'semen')
                            <h3 class="text-lg font-semibold text-gray-900">Sperma: {{ $perkawinan->semen_code ?? '-' }}</h3>
                        @endif
                    </div>
                </div>
                <div class="space-y-2 text-sm">
                    @if($perkawinan->jantan_type === 'owned' && $perkawinan->jantan)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Kode Hewan:</span>
                            <span class="font-medium">{{ $perkawinan->jantan->kode_hewan }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Jenis:</span>
                            <span class="font-medium">{{ ucfirst($perkawinan->jantan->jenis_hewan) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Ras:</span>
                            <span class="font-medium">{{ $perkawinan->jantan->ras_hewan }}</span>
                        </div>
                    @elseif($perkawinan->jantan_type === 'external')
                        <div class="flex justify-between">
                            <span class="text-gray-600">Sumber:</span>
                            <span class="font-medium">External</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Ras:</span>
                            <span class="font-medium">{{ $perkawinan->jantan_external_breed ?? '-' }}</span>
                        </div>
                        @if($perkawinan->jantan_external_owner)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Pemilik:</span>
                                <span class="font-medium">{{ $perkawinan->jantan_external_owner }}</span>
                            </div>
                        @endif
                    @elseif($perkawinan->jantan_type === 'semen')
                        <div class="flex justify-between">
                            <span class="text-gray-600">Produsen:</span>
                            <span class="font-medium">{{ $perkawinan->semen_producer ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Breed:</span>
                            <span class="font-medium">{{ $perkawinan->semen_breed ?? '-' }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Betina Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-pink-100 text-pink-600 h-12 w-12 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">BETINA</p>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $perkawinan->betina->nama_hewan }}</h3>
                    </div>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Kode Hewan:</span>
                        <span class="font-medium">{{ $perkawinan->betina->kode_hewan }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Jenis:</span>
                        <span class="font-medium">{{ ucfirst($perkawinan->betina->jenis_hewan) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Ras:</span>
                        <span class="font-medium">{{ $perkawinan->betina->ras_hewan }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Timeline Reproduksi</h3>

            <div class="relative">
                <!-- Vertical line -->
                <div class="absolute left-5 top-0 bottom-0 w-0.5 bg-gray-200"></div>

                <div class="space-y-6">
                    <!-- Tanggal Birahi (if exists) -->
                    @if($perkawinan->tanggal_birahi)
                        <div class="relative flex items-start gap-4">
                            <div
                                class="bg-orange-100 text-orange-600 h-10 w-10 rounded-full flex items-center justify-center z-10">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Deteksi Birahi</p>
                                <p class="text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($perkawinan->tanggal_birahi)->format('d F Y') }}
                                </p>
                            </div>
                        </div>
                    @endif

                    <!-- Tanggal Perkawinan -->
                    <div class="relative flex items-start gap-4">
                        <div class="bg-pink-100 text-pink-600 h-10 w-10 rounded-full flex items-center justify-center z-10">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Perkawinan</p>
                            <p class="text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($perkawinan->tanggal_perkawinan)->format('d F Y') }}
                            </p>
                            <span
                                class="inline-block mt-1 px-2 py-1 text-xs font-medium rounded-full {{ $perkawinan->metode_perkawinan == 'alami' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                {{ ucfirst(str_replace('_', ' ', $perkawinan->metode_perkawinan)) }}
                            </span>
                        </div>
                    </div>

                    <!-- Next Heat Check (if active) -->
                    @if($perkawinan->reminder_status == 'aktif' && $perkawinan->reminder_birahi_berikutnya)
                        <div class="relative flex items-start gap-4">
                            <div class="bg-blue-100 text-blue-600 h-10 w-10 rounded-full flex items-center justify-center z-10">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Cek Birahi Berikutnya</p>
                                <p class="text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($perkawinan->reminder_birahi_berikutnya)->format('d F Y') }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ abs(\Carbon\Carbon::today()->diffInDays($perkawinan->reminder_birahi_berikutnya, false)) }}
                                    hari lagi
                                </p>
                            </div>
                        </div>
                    @endif

                    <!-- Estimasi Kelahiran -->
                    @if($perkawinan->estimasi_kelahiran)
                        <div class="relative flex items-start gap-4">
                            <div
                                class="bg-purple-100 text-purple-600 h-10 w-10 rounded-full flex items-center justify-center z-10">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Estimasi Kelahiran</p>
                                <p class="text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($perkawinan->estimasi_kelahiran)->format('d F Y') }}
                                </p>
                                @if($perkawinan->status_reproduksi == 'bunting')
                                    <p class="text-xs text-purple-600 font-medium mt-1">
                                        {{ $perkawinan->sisa_hari }} hari lagi
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Tanggal Melahirkan (if exists) -->
                    @if($perkawinan->tanggal_melahirkan)
                        <div class="relative flex items-start gap-4">
                            <div
                                class="bg-green-100 text-green-600 h-10 w-10 rounded-full flex items-center justify-center z-10">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Kelahiran</p>
                                <p class="text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($perkawinan->tanggal_melahirkan)->format('d F Y') }}
                                </p>
                                @if($perkawinan->jumlah_anak)
                                    <p class="text-xs text-green-600 font-medium mt-1">
                                        {{ $perkawinan->jumlah_anak }} anak
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Offspring Section -->
        @if($perkawinan->offspring->count() > 0 || $perkawinan->status_reproduksi == 'melahirkan')
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Anak yang Dihasilkan</h3>
                    @if($perkawinan->status_reproduksi == 'melahirkan')
                        <a href="{{ route('reproduksi.add-offspring', $perkawinan->id) }}"
                            class="text-green-600 hover:text-green-700 font-medium text-sm flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah Anak
                        </a>
                    @endif
                </div>

                @if($perkawinan->offspring->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($perkawinan->offspring as $offspring)
                            <a href="{{ route('ternak.show', $offspring->id) }}"
                                class="block p-4 bg-gray-50 hover:bg-gray-100 rounded-xl transition">
                                <div class="flex items-start gap-3">
                                    <div
                                        class="bg-green-100 text-green-600 h-10 w-10 rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-900 truncate">{{ $offspring->nama_hewan }}</p>
                                        <p class="text-xs text-gray-500">{{ $offspring->kode_hewan }}</p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span
                                                class="px-2 py-0.5 text-xs rounded-full {{ $offspring->jenis_kelamin == 'jantan' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                                {{ ucfirst($offspring->jenis_kelamin) }}
                                            </span>
                                            <span class="text-xs text-gray-500">{{ $offspring->usia }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 text-center py-4">Belum ada anak yang didaftarkan</p>
                @endif
            </div>
        @endif

        <!-- Notes Section -->
        @if($perkawinan->catatan)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Catatan</h3>
                <p class="text-gray-700 whitespace-pre-line">{{ $perkawinan->catatan }}</p>
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex gap-3 justify-end">
            <a href="{{ route('reproduksi.edit', $perkawinan->id) }}"
                class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-xl font-medium transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                    </path>
                </svg>
                Edit Catatan
            </a>
            <button type="button" @click="showDeleteModal = true"
                class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl font-medium transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                    </path>
                </svg>
                Hapus
            </button>
        </div>

        {{-- Include Delete Modal --}}
        @include('reproduksi.modals.delete')
    </div>
@endsection