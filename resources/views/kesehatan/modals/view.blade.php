{{-- View Health Record Modal --}}
<div x-show="showViewModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
    role="dialog" aria-modal="true">

    {{-- Background overlay --}}
    <div x-show="showViewModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showViewModal = false"
        class="fixed inset-0 bg-gray-800/30 backdrop-blur-sm transition-opacity"></div>

    {{-- Modal container --}}
    <div class="flex min-h-full items-center justify-center p-4">
        {{-- Modal content --}}
        <div x-show="showViewModal" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95" @click.stop
            class="relative bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">

            {{-- Modal Header --}}
            <div class="bg-blue-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white" id="modal-title">
                        <i class="fa-solid fa-file-medical mr-2"></i>
                        Detail Catatan Kesehatan
                    </h3>
                    <button type="button" @click="showViewModal = false"
                        class="text-white hover:text-gray-200 transition">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Modal Body --}}
            <div class="bg-white px-6 py-5 max-h-[70vh] overflow-y-auto" x-show="currentRecord">
                <div class="space-y-4">
                    {{-- Info Hewan --}}
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Informasi Hewan</h4>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <p class="text-xs text-gray-500">Kode Hewan</p>
                                <p class="text-sm font-medium text-gray-900" x-text="currentRecord?.animal?.kode_hewan">
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Nama Hewan</p>
                                <p class="text-sm font-medium text-gray-900" x-text="currentRecord?.animal?.nama_hewan">
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Pemeriksaan Info --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Tanggal Pemeriksaan</p>
                            <p class="text-sm font-medium text-gray-900"
                                x-text="currentRecord ? new Date(currentRecord.tanggal_pemeriksaan).toLocaleString('id-ID') : '-'">
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Jenis Pemeriksaan</p>
                            <span x-show="currentRecord?.jenis_pemeriksaan === 'rutin'"
                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                Rutin
                            </span>
                            <span x-show="currentRecord?.jenis_pemeriksaan === 'darurat'"
                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Darurat
                            </span>
                            <span x-show="currentRecord?.jenis_pemeriksaan === 'follow_up'"
                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                Follow Up
                            </span>
                        </div>
                    </div>

                    {{-- Vital Signs --}}
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Vital Signs</h4>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Berat Badan</p>
                                <p class="text-lg font-semibold text-gray-900">
                                    <span x-text="currentRecord?.berat_badan"></span> kg
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Suhu Tubuh</p>
                                <p class="text-lg font-semibold text-gray-900">
                                    <span x-text="currentRecord?.suhu_tubuh || '-'"></span>
                                    <span x-show="currentRecord?.suhu_tubuh">°C</span>
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Status Kesehatan</p>
                                <span x-show="currentRecord?.status_kesehatan === 'sehat'"
                                    class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-emerald-100 text-emerald-800">
                                    Sehat
                                </span>
                                <span x-show="currentRecord?.status_kesehatan === 'sakit'"
                                    class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Sakit
                                </span>
                                <span x-show="currentRecord?.status_kesehatan === 'dalam_perawatan'"
                                    class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Dalam Perawatan
                                </span>
                                <span x-show="currentRecord?.status_kesehatan === 'sembuh'"
                                    class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Sembuh
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Medical Details --}}
                    <div class="space-y-3">
                        <div x-show="currentRecord?.gejala">
                            <p class="text-sm font-medium text-gray-700 mb-1">Gejala / Keluhan</p>
                            <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg" x-text="currentRecord?.gejala">
                            </p>
                        </div>

                        <div x-show="currentRecord?.diagnosis">
                            <p class="text-sm font-medium text-gray-700 mb-1">Diagnosis</p>
                            <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg"
                                x-text="currentRecord?.diagnosis">
                            </p>
                        </div>

                        <div x-show="currentRecord?.tindakan">
                            <p class="text-sm font-medium text-gray-700 mb-1">Tindakan / Treatment</p>
                            <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg" x-text="currentRecord?.tindakan">
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div x-show="currentRecord?.obat">
                                <p class="text-sm font-medium text-gray-700 mb-1">Obat</p>
                                <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg" x-text="currentRecord?.obat">
                                </p>
                            </div>
                            <div x-show="currentRecord?.biaya">
                                <p class="text-sm font-medium text-gray-700 mb-1">Biaya</p>
                                <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg">
                                    Rp <span
                                        x-text="currentRecord?.biaya ? new Intl.NumberFormat('id-ID').format(currentRecord.biaya) : '0'"></span>
                                </p>
                            </div>
                        </div>

                        <div x-show="currentRecord?.pemeriksaan_berikutnya">
                            <p class="text-sm font-medium text-gray-700 mb-1">Pemeriksaan Berikutnya</p>
                            <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg"
                                x-text="currentRecord?.pemeriksaan_berikutnya ? new Date(currentRecord.pemeriksaan_berikutnya).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) : '-'">
                            </p>
                        </div>

                        <div x-show="currentRecord?.catatan">
                            <p class="text-sm font-medium text-gray-700 mb-1">Catatan Tambahan</p>
                            <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg" x-text="currentRecord?.catatan">
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="bg-gray-50 px-6 py-4 flex justify-end">
                <button type="button" @click="showViewModal = false"
                    class="inline-flex justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                    Tutup
                </button>
            </div>

        </div>
    </div>
</div>