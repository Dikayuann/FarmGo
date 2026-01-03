@extends('layouts.app')

@section('title', 'Monitoring Kesehatan - FarmGo')
@section('page-title', 'Monitoring Kesehatan')

@section('content')
    <div class="flex flex-col gap-6" x-data="{ 
                        showViewModal: false, 
                        showDeleteModal: false, 
                        currentRecord: null
                    }">
        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-lg">
                <p class="text-emerald-700 font-medium">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Pemeriksaan</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total'] ?? 0 }}</p>
                    </div>
                    <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Hewan Sehat</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['sehat'] ?? 0 }}</p>
                    </div>
                    <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Hewan Sakit</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['sakit'] ?? 0 }}</p>
                    </div>
                    <div class="h-12 w-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Bulan Ini</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['bulan_ini'] ?? 0 }}</p>
                    </div>
                    <div class="h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>


        {{-- Header & Actions --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="text-2xl font-semibold text-gray-800">Riwayat Pemeriksaan Kesehatan</h2>

            <a href="{{ route('kesehatan.create') }}"
                class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg transition shadow-sm font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Tambah Catatan Kesehatan</span>
            </a>
        </div>

        {{-- Filters --}}
        <form method="GET" action="{{ route('kesehatan.index') }}"
            class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col md:flex-row gap-4 items-start">
            <div class="relative w-full md:w-96">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ $search ?? '' }}"
                    class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm transition"
                    placeholder="Cari berdasarkan nama hewan atau kode...">
            </div>

            <div class="flex gap-3 w-full md:w-auto flex-wrap">
                <select name="animal_id"
                    class="block w-full md:w-48 pl-3 pr-10 py-2 text-base border-gray-200 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-lg bg-gray-50 text-gray-600"
                    onchange="this.form.submit()">
                    <option value="all" {{ ($animalId ?? 'all') == 'all' ? 'selected' : '' }}>Semua Hewan</option>
                    @foreach ($animals as $animal)
                        <option value="{{ $animal->id }}" {{ ($animalId ?? '') == $animal->id ? 'selected' : '' }}>
                            {{ $animal->kode_hewan }} - {{ $animal->nama_hewan }}
                        </option>
                    @endforeach
                </select>

                <select name="status"
                    class="block w-full md:w-40 pl-3 pr-10 py-2 text-base border-gray-200 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-lg bg-gray-50 text-gray-600"
                    onchange="this.form.submit()">
                    <option value="all" {{ ($status ?? 'all') == 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="sehat" {{ ($status ?? '') == 'sehat' ? 'selected' : '' }}>Sehat</option>
                    <option value="sakit" {{ ($status ?? '') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                    <option value="dalam_perawatan" {{ ($status ?? '') == 'dalam_perawatan' ? 'selected' : '' }}>Dalam
                        Perawatan</option>
                    <option value="sembuh" {{ ($status ?? '') == 'sembuh' ? 'selected' : '' }}>Sembuh</option>
                </select>

                <select name="jenis_pemeriksaan"
                    class="block w-full md:w-40 pl-3 pr-10 py-2 text-base border-gray-200 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-lg bg-gray-50 text-gray-600"
                    onchange="this.form.submit()">
                    <option value="all" {{ ($jenisPemeriksaan ?? 'all') == 'all' ? 'selected' : '' }}>Semua Jenis
                    </option>
                    <option value="rutin" {{ ($jenisPemeriksaan ?? '') == 'rutin' ? 'selected' : '' }}>Rutin</option>
                    <option value="darurat" {{ ($jenisPemeriksaan ?? '') == 'darurat' ? 'selected' : '' }}>Darurat
                    </option>
                    <option value="follow_up" {{ ($jenisPemeriksaan ?? '') == 'follow_up' ? 'selected' : '' }}>Follow Up
                    </option>
                </select>
            </div>
        </form>

        {{-- Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Tanggal</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Hewan</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Jenis Pemeriksaan</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Berat (kg)</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Suhu (°C)</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($healthRecords as $record)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $record->tanggal_pemeriksaan->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $record->animal->kode_hewan }}</p>
                                        <p class="text-xs text-gray-500">{{ $record->animal->nama_hewan }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    @if ($record->jenis_pemeriksaan == 'rutin')
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Rutin
                                        </span>
                                    @elseif($record->jenis_pemeriksaan == 'darurat')
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Darurat
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                            Follow Up
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $record->berat_badan }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $record->suhu_tubuh ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($record->status_kesehatan == 'sehat')
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-emerald-100 text-emerald-800">
                                            Sehat
                                        </span>
                                    @elseif($record->status_kesehatan == 'sakit')
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Sakit
                                        </span>
                                    @elseif($record->status_kesehatan == 'dalam_perawatan')
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Dalam Perawatan
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Sembuh
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-3">
                                        <button type="button"
                                            @click="currentRecord = {{ json_encode($record) }}; showViewModal = true"
                                            class="text-blue-600 hover:text-blue-700 transition" title="Lihat Detail">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </button>
                                        <a href="{{ route('kesehatan.edit', $record->id) }}"
                                            class="text-emerald-600 hover:text-emerald-700 transition" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>
                                        <button type="button"
                                            @click="currentRecord = {{ json_encode($record) }}; showDeleteModal = true"
                                            class="text-red-500 hover:text-red-700 transition" title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <p class="text-lg font-medium">Belum ada catatan kesehatan</p>
                                        <p class="text-sm mt-1">Klik tombol "Tambah Catatan Kesehatan" untuk menambahkan
                                            catatan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($healthRecords->hasPages())
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    {{ $healthRecords->links() }}
                </div>
            @endif
        </div>

        {{-- Modals --}}
        @include('kesehatan.modals.view')
        @include('kesehatan.modals.delete')
    </div>
@endsection