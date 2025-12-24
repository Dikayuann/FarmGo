@extends('layouts.app')

@section('title', 'Detail Ternak - FarmGo')
@section('page-title', 'Detail Data Ternak')

@section('content')
    <div class="flex flex-col gap-6" x-data="{ showEditModal: false, showScanModal: false, currentAnimal: {{ $animal }} }">
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
                        <button @click="showEditModal = true"
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
                                <button @click="showScanModal = true"
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

                            {{-- Status Kesehatan --}}
                            <div class="border-l-4 border-emerald-500 pl-4 md:col-span-2">
                                <p class="text-sm text-gray-500 mb-2">Status Kesehatan</p>
                                @if($animal->status_kesehatan == 'sehat')
                                    <span
                                        class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-emerald-100 text-emerald-800">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Sehat
                                    </span>
                                @elseif($animal->status_kesehatan == 'sakit')
                                    <span
                                        class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Sakit
                                    </span>
                                @else
                                    <span
                                        class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Karantina
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

        {{-- Modals --}}
        @include('ternak.modals.edit')
        @include('ternak.modals.scan')
    </div>


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