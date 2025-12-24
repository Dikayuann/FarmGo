{{-- QR Modal --}}
<div x-show="showQRModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
    aria-modal="true">

    {{-- Background overlay --}}
    <div x-show="showQRModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showQRModal = false"
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"></div>

    {{-- Modal container --}}
    <div class="flex min-h-full items-center justify-center p-4">
        {{-- Modal content --}}
        <div x-show="showQRModal" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95" @click.stop x-show="currentAnimal"
            class="relative bg-white rounded-xl shadow-xl w-full max-w-md">

            {{-- Modal Header --}}
            <div class="bg-emerald-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">QR Code Ternak</h3>
                    <button type="button" @click="showQRModal = false"
                        class="text-white/80 hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Modal Body --}}
            <div class="px-6 py-6 text-center">
                <div class="bg-emerald-50 border-2 border-emerald-200 rounded-xl p-6 mx-auto inline-block">
                    <img :src="currentAnimal?.qr_url" :alt="'QR Code ' + currentAnimal?.kode_hewan"
                        class="w-64 h-64 mx-auto">
                </div>

                <div class="mt-6">
                    <h4 class="font-bold text-xl text-gray-900" x-text="currentAnimal?.nama_hewan"></h4>
                    <p class="text-emerald-600 font-semibold text-lg mt-1" x-text="currentAnimal?.kode_hewan"></p>
                    <p class="text-gray-600 text-sm mt-2">
                        <span
                            x-text="currentAnimal?.jenis_hewan ? currentAnimal.jenis_hewan.charAt(0).toUpperCase() + currentAnimal.jenis_hewan.slice(1) : ''"></span>
                        <span x-show="currentAnimal?.ras_hewan"> - </span>
                        <span x-text="currentAnimal?.ras_hewan"></span>
                    </p>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="bg-gray-50 px-6 py-3 flex justify-end gap-2 border-t">
                <button type="button" @click="showQRModal = false"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Tutup
                </button>
                <button type="button" @click="printSingleQR(currentAnimal)"
                    class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg flex items-center gap-2">
                    <i class="fa-solid fa-print"></i>
                    <span>Cetak QR</span>
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