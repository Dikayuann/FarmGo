@extends('layouts.app')

@section('title', 'Dashboard - FarmGo')
@section('page-title', 'Dashboard')

{{-- Data sudah dikirim dari DashboardController --}}

@section('content')

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        <div
            class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-start justify-between hover:shadow-md transition">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-2">Total Ternak</p>
                <h3 class="text-4xl font-bold text-gray-800">{{$totalTernak}}</h3>
            </div>
            <div
                class="bg-blue-600 h-12 w-12 rounded-xl flex items-center justify-center text-white shadow-blue-200 shadow-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
            </div>
        </div>

        <div
            class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-start justify-between hover:shadow-md transition">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-2">Perlu Cek <br>Kesehatan</p>
                <h3 class="text-4xl font-bold text-gray-800">{{ $perluCekKesehatan }}</h3>
            </div>
            <div
                class="bg-orange-500 h-12 w-12 rounded-xl flex items-center justify-center text-white shadow-orange-200 shadow-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
            </div>
        </div>

        <div
            class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-start justify-between hover:shadow-md transition">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-2">Reproduksi <br>Mendatang</p>
                <h3 class="text-4xl font-bold text-gray-800">{{ $reproduksiMendatang }}</h3>
            </div>
            <div
                class="bg-purple-600 h-12 w-12 rounded-xl flex items-center justify-center text-white shadow-purple-200 shadow-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>

        <div
            class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-start justify-between hover:shadow-md transition">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-2">Status <br>Peternakan</p>
                <h3 class="text-4xl font-bold text-gray-800">{{ $statusPeternakan }}</h3>
            </div>
            <div
                class="bg-green-500 h-12 w-12 rounded-xl flex items-center justify-center text-white shadow-green-200 shadow-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="font-medium text-lg text-gray-800 mb-6">Populasi Ternak dari Waktu ke Waktu</h3>
            <div class="relative h-72 w-full flex items-center justify-center">
                @if (count($populationData) > 0)
                    <canvas id="populationChart"></canvas>
                @else
                    <p class="text-gray-400 text-sm">Data tidak tersedia</p>
                @endif
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="font-medium text-lg text-gray-800 mb-6">Tingkat Reproduksi (%)</h3>
            <div class="relative h-72 w-full flex items-center justify-center">
                @if (count($reproductionData) > 0)
                    <canvas id="reproductionChart"></canvas>
                @else
                    <p class="text-gray-400 text-sm">Data tidak tersedia</p>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="font-medium text-lg text-gray-800 mb-6">Tugas Kesehatan Mendatang</h3>
            
            @if($tugasKesehatan->count() > 0)
                <div class="space-y-4">
                    @foreach($tugasKesehatan as $tugas)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition cursor-pointer">
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-800">{{ $tugas->animal->kode_hewan }} - {{ $tugas->animal->nama_hewan }}</span>
                                <span class="text-sm text-gray-500">{{ $tugas->jenis_pemeriksaan }}</span>
                            </div>
                            <div class="flex flex-col items-end gap-1">
                                <span class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($tugas->pemeriksaan_berikutnya)->format('d M Y') }}
                                </span>
                                
                                @php
                                    // Hitung berapa hari lagi
                                    $daysUntil = \Carbon\Carbon::today()->diffInDays($tugas->pemeriksaan_berikutnya, false);
                                    
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
                                
                                <span class="{{ $badge }} text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-sm">
                                    {{ $priority }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-400">
                    <p>Tidak ada tugas kesehatan yang dijadwalkan</p>
                </div>
            @endif
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 h-fit">
            <h3 class="font-medium text-lg text-gray-800 mb-6">Notifikasi</h3>
            <div class="space-y-4">

                <div class="p-4 bg-blue-50 text-blue-800 text-sm rounded-xl border border-blue-100">
                    <p class="font-semibold mb-1">Kode QR siap untuk dicetak</p>
                    <p class="text-xs opacity-80">(15 hewan)</p>
                </div>

                <div class="p-4 bg-orange-50 text-orange-800 text-sm rounded-xl border border-orange-100">
                    <p class="font-semibold mb-1">3 hewan memerlukan vaksinasi segera</p>
                </div>

                <div class="p-4 bg-blue-50 text-blue-800 text-sm rounded-xl border border-blue-100">
                    <p class="font-semibold mb-1">Cek kehamilan dijadwalkan untuk 5 hewan</p>
                </div>

            </div>
        </div>
    </div>
@endsection



@push('scripts')
    <script>
        @if (count($populationData) > 0)
            const ctxPop = document.getElementById('populationChart').getContext('2d');
            new Chart(ctxPop, {
                type: 'line',
                data: {
                    labels: @json($monthLabels),
                    datasets: [{
                        label: 'Total Populasi',
                        data: @json($populationData),
                        borderColor: '#22c55e', // Green FarmGo
                        backgroundColor: 'rgba(34, 197, 94, 0.05)',
                        borderWidth: 2,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#22c55e',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: false,
                            grid: { borderDash: [2, 4], color: '#f3f4f6' },
                            ticks: { font: { size: 10, family: 'Inter' } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10, family: 'Inter' } }
                        }
                    }
                }
            });
        @endif

            @if (count($reproductionData) > 0)
                const ctxRep = document.getElementById('reproductionChart').getContext('2d');
                new Chart(ctxRep, {
                    type: 'bar',
                    data: {
                        labels: ['Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                        datasets: [{
                            label: 'Tingkat (%)',
                            data: @json($reproductionData),
                            backgroundColor: '#2563eb', // Blue-600
                            borderRadius: 4,
                            barThickness: 24
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100,
                                grid: { borderDash: [2, 4], color: '#f3f4f6' },
                                ticks: { font: { size: 10, family: 'Inter' } }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { font: { size: 10, family: 'Inter' } }
                            }
                        }
                    }
                });
            @endif
    </script>
@endpush