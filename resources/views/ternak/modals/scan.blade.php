{{-- Scan QR Modal --}}
<div x-show="showScanModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
    role="dialog" aria-modal="true" @open-scan.window="showScanModal = true">

    {{-- Background overlay --}}
    <div x-show="showScanModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        @click="showScanModal = false; stopScanner()"
        class="fixed inset-0 bg-gray-900/90 backdrop-blur-sm transition-opacity"></div>

    {{-- Modal container --}}
    <div class="flex min-h-full items-center justify-center p-4">
        {{-- Modal content --}}
        <div x-show="showScanModal" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95" @click.stop
            x-init="$watch('showScanModal', value => { if(value) startScanner(); else stopScanner(); })"
            class="relative bg-white rounded-xl shadow-xl w-full max-w-lg">

            {{-- Modal Header --}}
            <div class="bg-blue-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-camera text-white text-xl"></i>
                        <h3 class="text-lg font-semibold text-white">Pindai QR Code</h3>
                    </div>
                    <button type="button" @click="showScanModal = false; stopScanner()"
                        class="text-white/80 hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Modal Body --}}
            <div class="px-6 py-5">
                {{-- Camera Label --}}
                <div class="mb-3 text-center">
                    <span id="camera-label" class="text-sm font-medium text-gray-600">Kamera Belakang</span>
                </div>

                {{-- Video Container --}}
                <div class="relative bg-black rounded-lg overflow-hidden" style="aspect-ratio: 1/1;">
                    <video id="qr-video" class="w-full h-full object-cover"></video>

                    {{-- Scan Success Overlay --}}
                    <div id="scan-result"
                        class="hidden absolute inset-0 bg-emerald-500/90 flex items-center justify-center">
                        <div class="text-center text-white">
                            <svg class="w-16 h-16 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-lg font-semibold">QR Code Terdeteksi!</p>
                            <p class="text-sm opacity-90 mt-1">Mengalihkan...</p>
                        </div>
                    </div>
                </div>

                {{-- Instructions --}}
                <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="text-sm text-blue-800">
                            <p class="font-semibold mb-1">Cara Pindai:</p>
                            <ul class="list-disc list-inside space-y-1 text-xs">
                                <li>Arahkan kamera ke QR code ternak</li>
                                <li>Pastikan QR code terlihat jelas di dalam kotak</li>
                                <li>Tunggu hingga QR terdeteksi secara otomatis</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="bg-gray-50 px-6 py-3 flex justify-between items-center border-t">
                <button type="button" onclick="switchCamera()"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center gap-2">
                    <i class="fa-solid fa-camera-rotate"></i>
                    <span>Ganti Kamera</span>
                </button>
                <button type="button" @click="showScanModal = false; stopScanner()"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>