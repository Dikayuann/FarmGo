@extends('layouts.app')

@section('title', 'Manajemen Ternak - FarmGo')
@section('page-title', 'Manajemen Ternak')

@section('content')
    <div class="flex flex-col gap-6" x-data="{ 
                showCreateModal: false, 
                showQRModal: false, 
                showEditModal: false, 
                showDeleteModal: false, 
                showScanModal: false, 
                currentAnimal: null,
                nama_hewan: '',
                jenis_hewan: '',
                ras_hewan: '',
                jenis_kelamin: '',
                tanggal_lahir: '',
                berat_badan: '',
                status_kesehatan: ''
            }">
        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-lg">
                <p class="text-emerald-700 font-medium">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Ternak</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total'] ?? 0 }}</p>
                    </div>
                    <div class="h-12 w-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Sapi</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['sapi'] ?? 0 }}</p>
                    </div>
                    <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Kambing</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['kambing'] ?? 0 }}</p>
                    </div>
                    <div class="h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Domba</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['domba'] ?? 0 }}</p>
                    </div>
                    <div class="h-12 w-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Kondisi Sehat</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['sehat'] ?? 0 }}</p>
                    </div>
                    <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>


        {{-- Header & Actions --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="text-2xl font-semibold text-gray-800">Daftar Hewan Ternak</h2>

            <div class="flex flex-wrap gap-3">
                <button @click="showCreateModal = true"
                    class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg transition shadow-sm font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Tambah Ternak</span>
                </button>

                <button @click="showScanModal = true"
                    class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition shadow-sm font-medium">
                    <i class="fa-solid fa-camera text-sm"></i>
                    <span>Pindai QR</span>
                </button>

                <button @click="printAllQR()"
                    class="flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 px-4 py-2 rounded-lg transition shadow-sm font-medium">
                    <i class="fa-solid fa-print text-sm"></i>
                    <span>Cetak Semua QR</span>
                </button>
            </div>
        </div>

        {{-- Filters --}}
        <form method="GET" action="{{ route('ternak.index') }}"
            class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="relative w-full md:w-96">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ $search ?? '' }}"
                    class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm transition"
                    placeholder="Cari berdasarkan nama atau ID...">
            </div>

            <div class="flex gap-4 w-full md:w-auto">
                <select name="jenis"
                    class="block w-full md:w-40 pl-3 pr-10 py-2 text-base border-gray-200 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-lg bg-gray-50 text-gray-600"
                    onchange="this.form.submit()">
                    <option value="all" {{ ($jenis ?? 'all') == 'all' ? 'selected' : '' }}>Semua Jenis</option>
                    <option value="sapi" {{ ($jenis ?? '') == 'sapi' ? 'selected' : '' }}>Sapi</option>
                    <option value="kambing" {{ ($jenis ?? '') == 'kambing' ? 'selected' : '' }}>Kambing</option>
                    <option value="domba" {{  ($jenis ?? '') == 'domba' ? 'selected' : '' }}>Domba</option>
                </select>

                <select name="status"
                    class="block w-full md:w-40 pl-3 pr-10 py-2 text-base border-gray-200 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-lg bg-gray-50 text-gray-600"
                    onchange="this.form.submit()">
                    <option value="all" {{ ($status ?? 'all') == 'all' ? 'selected' : '' }}>Semua kondisi</option>
                    <option value="sehat" {{ ($status ?? '') == 'sehat' ? 'selected' : '' }}>Sehat</option>
                    <option value="sakit" {{ ($status ?? '') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                    <option value="karantina" {{ ($status ?? '') == 'karantina' ? 'selected' : '' }}>Karantina</option>
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
                                Kode</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Nama</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Jenis & Ras</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Jenis Kelamin</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Usia</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Berat</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($animals as $animal)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $animal->kode_hewan }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $animal->nama_hewan }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    <div>
                                        <p class="font-medium">{{ ucfirst($animal->jenis_hewan) }}</p>
                                        <p class="text-xs text-gray-500">{{ $animal->ras_hewan }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ ucfirst($animal->jenis_kelamin) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $animal->usia }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $animal->berat_badan }}
                                    kg</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($animal->status_kesehatan == 'sehat')
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-emerald-100 text-emerald-800">
                                            Sehat
                                        </span>
                                    @elseif($animal->status_kesehatan == 'sakit')
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Sakit
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Karantina
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-3">
                                        <button type="button"
                                            @click="currentAnimal = {{ json_encode($animal) }}; showQRModal = true"
                                            class="text-gray-400 hover:text-gray-600 transition" title="Lihat QR">
                                            <i class="fa-solid fa-qrcode text-lg"></i>
                                        </button>
                                        <button type="button"
                                            @click="currentAnimal = {{ json_encode($animal) }}; showEditModal = true"
                                            class="text-emerald-600 hover:text-emerald-700 transition" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>
                                        <button type="button"
                                            @click="currentAnimal = {{ json_encode($animal) }}; showDeleteModal = true"
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
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                            </path>
                                        </svg>
                                        <p class="text-lg font-medium">Belum ada data ternak</p>
                                        <p class="text-sm mt-1">Klik tombol "Tambah Ternak" untuk menambahkan hewan
                                            ternak</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($animals->hasPages())
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    {{ $animals->links() }}
                </div>
            @endif
        </div>

        {{-- Modals --}}
        @include('ternak.modals.create')
        @include('ternak.modals.edit')
        @include('ternak.modals.qr')
        @include('ternak.modals.delete')
        @include('ternak.modals.scan')
    </div>

    {{-- QR Scanner Script - nimiq/qr-scanner --}}
    <script type="module">
        import QrScanner from 'https://cdn.jsdelivr.net/npm/qr-scanner@1.4.2/qr-scanner.min.js';

        let qrScanner = null;
        let currentCamera = 'environment'; // 'environment' = back, 'user' = front
        let availableCameras = [];

        // Check if camera is available on device
        window.checkCameraSupport = async function () {
            const hasCamera = await QrScanner.hasCamera();
            if (!hasCamera) {
                alert('Perangkat ini tidak memiliki kamera atau kamera tidak dapat diakses.');
                return false;
            }
            return true;
        }

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



    <script>
        // Print single QR - opens new window with QR details
        function printSingleQR(animal) {
            const printWindow = window.open('', '_blank', 'width=400,height=500');
            printWindow.document.write(`
                                    <!DOCTYPE html>
                                    <html>
                                    <head>
                                        <title>QR Code - ${animal.kode_hewan}</title>
                                        <style>
                                            body {
                                                font-family: Arial, sans-serif;
                                                text-align: center;
                                                padding: 30px;
                                            }
                                            .qr-container {
                                                border: 3px solid #10b981;
                                                border-radius: 12px;
                                                padding: 25px;
                                                display: inline-block;
                                            }
                                            h1 { font-size: 24px; margin-bottom: 5px; color: #1f2937; }
                                            .code { font-size: 18px; color: #10b981; font-weight: bold; margin-bottom: 15px; }
                                            .details { font-size: 14px; color: #6b7280; margin-bottom: 15px; }
                                            img { width: 200px; height: 200px; }
                                            .farm { font-size: 12px; color: #9ca3af; margin-top: 15px; }
                                        </style>
                                    </head>
                                    <body>
                                        <div class="qr-container">
                                            <h1>${animal.nama_hewan}</h1>
                                            <div class="code">${animal.kode_hewan}</div>
                                            <div class="details">${animal.jenis_hewan ? animal.jenis_hewan.charAt(0).toUpperCase() + animal.jenis_hewan.slice(1) : ''} - ${animal.ras_hewan || ''}</div>
                                            <img src="${animal.qr_url || ''}" alt="QR Code">
                                            <div class="farm">FarmGo - Sistem Manajemen Ternak</div>
                                        </div>
                                        <script>
                                            window.onload = function() { 
                                                setTimeout(function() { window.print(); }, 300);
                                            }
                                        <\/script>
                                    </body>
                                    </html>
                                `);
            printWindow.document.close();
        }

        // Print all QR codes
        function printAllQR() {
            window.open('{{ route("ternak.index") }}?print_all=1', '_blank');
        }
    </script>
@endsection