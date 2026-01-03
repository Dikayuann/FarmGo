@extends('layouts.app')

@section('title', 'Reproduksi - FarmGo')
@section('page-title', 'Catatan Reproduksi/Perkawinan')

@section('content')
    <div>

        <!-- Status Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div
                class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-start justify-between hover:shadow-md transition">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-2">Total Perkawinan</p>
                    <h3 class="text-4xl font-bold text-gray-800">{{ $statusCounts['total'] }}</h3>
                </div>
                <div
                    class="bg-blue-600 h-12 w-12 rounded-xl flex items-center justify-center text-white shadow-blue-200 shadow-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                        </path>
                    </svg>
                </div>
            </div>

            <div
                class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-start justify-between hover:shadow-md transition">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-2">Sedang Bunting</p>
                    <h3 class="text-4xl font-bold text-gray-800">{{ $statusCounts['bunting'] }}</h3>
                </div>
                <div
                    class="bg-purple-600 h-12 w-12 rounded-xl flex items-center justify-center text-white shadow-purple-200 shadow-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <div
                class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-start justify-between hover:shadow-md transition">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-2">Sudah Melahirkan</p>
                    <h3 class="text-4xl font-bold text-gray-800">{{ $statusCounts['melahirkan'] }}</h3>
                </div>
                <div
                    class="bg-green-500 h-12 w-12 rounded-xl flex items-center justify-center text-white shadow-green-200 shadow-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <div
                class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-start justify-between hover:shadow-md transition">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-2">Pengingat Aktif</p>
                    <h3 class="text-4xl font-bold text-gray-800">{{ $upcomingReminders->count() }}</h3>
                </div>
                <div
                    class="bg-orange-500 h-12 w-12 rounded-xl flex items-center justify-center text-white shadow-orange-200 shadow-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-6">
            <div class="flex flex-col lg:flex-row gap-4 items-start lg:items-center justify-between">
                <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                    <!-- Search -->
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari kode atau nama hewan..."
                            class="pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent w-full sm:w-80 text-sm"
                            id="searchInput" />
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>

                    <!-- Status Filter -->
                    <select name="status" id="statusFilter"
                        class="px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                        <option value="all">Semua Status</option>
                        <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="bunting" {{ request('status') == 'bunting' ? 'selected' : '' }}>Bunting</option>
                        <option value="melahirkan" {{ request('status') == 'melahirkan' ? 'selected' : '' }}>Melahirkan
                        </option>
                        <option value="gagal" {{ request('status') == 'gagal' ? 'selected' : '' }}>Gagal</option>
                    </select>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('heat-detection.create') }}"
                        class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2.5 rounded-xl font-medium transition shadow-sm flex items-center gap-2 whitespace-nowrap">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path>
                        </svg>
                        Catat Birahi
                    </a>

                    <a href="{{ route('reproduksi.create') }}"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-xl font-medium transition shadow-sm flex items-center gap-2 whitespace-nowrap">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Catatan Reproduksi
                    </a>
                </div>
            </div>
        </div>

        {{-- Pending Heat Detections --}}
        @if($pendingHeatDetections->count() > 0)
            <div class="bg-orange-50 border border-orange-200 rounded-2xl p-5 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path>
                        </svg>
                        <h3 class="font-semibold text-orange-900">Deteksi Birahi Pending ({{ $pendingHeatDetections->count() }})
                        </h3>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($pendingHeatDetections as $heat)
                        <div class="bg-white p-4 rounded-xl border border-orange-200 hover:shadow-md transition">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $heat->animal->nama_hewan }}</p>
                                    <p class="text-xs text-gray-500">{{ $heat->animal->kode_hewan }}</p>
                                </div>
                                <span
                                    class="bg-orange-100 text-orange-800 text-xs font-medium px-2 py-1 rounded-full">Pending</span>
                            </div>
                            <div class="text-xs text-gray-600 space-y-1 mb-3">
                                <div class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span>{{ $heat->tanggal_deteksi->format('d M Y') }}</span>
                                    <span class="text-gray-400">•</span>
                                    <span>{{ $heat->tanggal_deteksi->diffForHumans() }}</span>
                                </div>
                                @if($heat->gejala && count($heat->gejala) > 0)
                                    <div class="text-xs text-gray-500">
                                        <span class="font-medium">Gejala:</span> {{ implode(', ', array_slice($heat->gejala, 0, 2)) }}
                                        @if(count($heat->gejala) > 2)
                                            <span class="text-orange-600">+{{ count($heat->gejala) - 2 }} lagi</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('reproduksi.create', ['heat_detection_id' => $heat->id, 'betina_id' => $heat->animal_id, 'tanggal_birahi' => $heat->tanggal_deteksi->format('Y-m-d')]) }}"
                                    class="flex-1 bg-green-600 hover:bg-green-700 text-white text-xs font-medium px-3 py-2 rounded-lg transition text-center">
                                    Kawinkan
                                </a>
                                <form action="{{ route('heat-detection.destroy', $heat->id) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Hapus catatan birahi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-gray-500 hover:text-red-600 p-2 rounded-lg hover:bg-gray-100 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" view Box="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Reproduction Records Table -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6">
                    <h3 class="font-semibold text-lg text-gray-800 mb-4">Riwayat Perkawinan</h3>

                    @if($perkawinans->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left text-xs font-semibold text-gray-600 uppercase tracking-wider pb-3">
                                            Kode</th>
                                        <th class="text-left text-xs font-semibold text-gray-600 uppercase tracking-wider pb-3">
                                            Jantan</th>
                                        <th class="text-left text-xs font-semibold text-gray-600 uppercase tracking-wider pb-3">
                                            Betina</th>
                                        <th class="text-left text-xs font-semibold text-gray-600 uppercase tracking-wider pb-3">
                                            Tanggal</th>
                                        <th class="text-left text-xs font-semibold text-gray-600 uppercase tracking-wider pb-3">
                                            Metode</th>
                                        <th class="text-left text-xs font-semibold text-gray-600 uppercase tracking-wider pb-3">
                                            Status</th>
                                        <th
                                            class="text-center text-xs font-semibold text-gray-600 uppercase tracking-wider pb-3">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($perkawinans as $perkawinan)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="py-4">
                                                <span
                                                    class="font-mono text-sm font-medium text-gray-900">#{{ $perkawinan->id }}</span>
                                            </td>
                                            <td class="py-4">
                                                <div class="text-sm">
                                                    @if($perkawinan->jantan_type === 'owned' && $perkawinan->jantan)
                                                        <div class="font-medium text-gray-900">{{ $perkawinan->jantan->nama_hewan }}
                                                        </div>
                                                        <div class="text-gray-500 text-xs">{{ $perkawinan->jantan->kode_hewan }}</div>
                                                    @elseif($perkawinan->jantan_type === 'external')
                                                        <div class="font-medium text-gray-900">
                                                            {{ $perkawinan->jantan_external_name ?? 'External' }}
                                                        </div>
                                                        <div class="text-gray-500 text-xs">
                                                            {{ $perkawinan->jantan_external_breed ?? '-' }}
                                                        </div>
                                                    @elseif($perkawinan->jantan_type === 'semen')
                                                        <div class="font-medium text-gray-900">Sperma:
                                                            {{ $perkawinan->semen_code ?? '-' }}
                                                        </div>
                                                        <div class="text-gray-500 text-xs">{{ $perkawinan->semen_breed ?? '-' }}</div>
                                                    @else
                                                        <div class="font-medium text-gray-900">-</div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="py-4">
                                                <div class="text-sm">
                                                    <div class="font-medium text-gray-900">{{ $perkawinan->betina->nama_hewan }}
                                                    </div>
                                                    <div class="text-gray-500 text-xs">{{ $perkawinan->betina->kode_hewan }}</div>
                                                </div>
                                            </td>
                                            <td class="py-4 text-sm text-gray-600">
                                                {{ \Carbon\Carbon::parse($perkawinan->tanggal_perkawinan)->format('d M Y') }}
                                            </td>
                                            <td class="py-4">
                                                <span
                                                    class="px-2 py-1 text-xs font-medium rounded-full {{ $perkawinan->metode_perkawinan == 'alami' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                                    {{ ucfirst(str_replace('_', ' ', $perkawinan->metode_perkawinan)) }}
                                                </span>
                                            </td>
                                            <td class="py-4">
                                                @php
                                                    $statusColors = [
                                                        'menunggu' => 'bg-gray-100 text-gray-800',
                                                        'bunting' => 'bg-purple-100 text-purple-800',
                                                        'melahirkan' => 'bg-green-100 text-green-800',
                                                        'gagal' => 'bg-red-100 text-red-800',
                                                    ];
                                                @endphp
                                                <span
                                                    class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$perkawinan->status_reproduksi] }}">
                                                    {{ ucfirst($perkawinan->status_reproduksi) }}
                                                </span>
                                            </td>
                                            <td class="py-4">
                                                <div class="flex items-center justify-center gap-2">
                                                    <a href="{{ route('reproduksi.show', $perkawinan->id) }}"
                                                        class="text-blue-600 hover:text-blue-800 transition" title="Lihat Detail">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                    <a href="{{ route('reproduksi.edit', $perkawinan->id) }}"
                                                        class="text-green-600 hover:text-green-800 transition" title="Edit">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                    <form action="{{ route('reproduksi.destroy', $perkawinan->id) }}" method="POST"
                                                        class="inline"
                                                        onsubmit="return confirm('Yakin ingin menghapus catatan ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-800 transition"
                                                            title="Hapus">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $perkawinans->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                </path>
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">Belum ada catatan reproduksi</h3>
                            <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan catatan perkawinan pertama Anda.</p>
                            <a href="{{ route('reproduksi.create') }}"
                                class="mt-4 inline-block bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-xl font-medium transition">
                                Tambah Catatan
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Upcoming Reminders -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 h-fit">
                <h3 class="font-semibold text-lg text-gray-800 mb-4">Pengingat Mendatang</h3>

                @if($upcomingReminders->count() > 0)
                    <div class="space-y-3">
                        @foreach($upcomingReminders as $reminder)
                            @php
                                $daysUntil = \Carbon\Carbon::today()->diffInDays($reminder->reminder_birahi_berikutnya, false);

                                if ($daysUntil <= 3) {
                                    $badge = 'bg-red-500';
                                    $priority = 'Tinggi';
                                } elseif ($daysUntil <= 7) {
                                    $badge = 'bg-gray-800';
                                    $priority = 'Sedang';
                                } else {
                                    $badge = 'bg-gray-400';
                                    $priority = 'Rendah';
                                }
                            @endphp

                            <div class="p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <p class="font-medium text-sm text-gray-900">{{ $reminder->betina->nama_hewan }}</p>
                                        <p class="text-xs text-gray-500">{{ $reminder->betina->kode_hewan }}</p>
                                    </div>
                                    <span class="{{ $badge }} text-white text-xs font-bold px-2 py-1 rounded-full">
                                        {{ $priority }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span>{{ \Carbon\Carbon::parse($reminder->reminder_birahi_berikutnya)->format('d M Y') }}</span>
                                    <span class="text-gray-400">•</span>
                                    <span>{{ abs($daysUntil) }} hari lagi</span>
                                </div>
                                <a href="{{ route('reproduksi.show', $reminder->id) }}"
                                    class="mt-2 text-xs text-green-600 hover:text-green-800 font-medium inline-flex items-center gap-1">
                                    Lihat Detail
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                        </path>
                                    </svg>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-400">
                        <svg class="mx-auto h-12 w-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                        <p class="text-sm">Tidak ada pengingat dijadwalkan</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Search with debounce
            let searchTimeout;
            document.getElementById('searchInput').addEventListener('input', function (e) {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    applyFilters();
                }, 500);
            });

            // Status filter
            document.getElementById('statusFilter').addEventListener('change', function () {
                applyFilters();
            });

            function applyFilters() {
                const search = document.getElementById('searchInput').value;
                const status = document.getElementById('statusFilter').value;

                const url = new URL(window.location.href);
                url.searchParams.set('search', search);
                url.searchParams.set('status', status);

                window.location.href = url.toString();
            }
        </script>
    @endpush
@endsection