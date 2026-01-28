{{-- Universal Delete Modal - Works for both index (Alpine.js) and show (direct data) pages --}}
@if(Route::currentRouteName() === 'ternak.show')
    {{-- Show Page Version - Direct animal data with validation --}}
    <div id="deleteModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">

        {{-- Background overlay --}}
        <div onclick="closeDeleteModal()" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>

        {{-- Modal container --}}
        <div class="flex min-h-full items-center justify-center p-4">
            {{-- Modal content --}}
            <div onclick="event.stopPropagation()" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg">

                <form id="deleteForm" action="{{ route('ternak.destroy', $animal->id) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    {{-- Modal Header --}}
                    <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-5 rounded-t-2xl">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="bg-white/20 rounded-full p-2">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white">Hapus Data Ternak</h3>
                                    <p class="text-red-100 text-sm mt-0.5">Tindakan ini tidak dapat dibatalkan</p>
                                </div>
                            </div>
                            <button type="button" onclick="closeDeleteModal()"
                                class="text-white/80 hover:text-white hover:bg-white/10 rounded-lg p-1.5 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Modal Body --}}
                    <div class="px-6 py-6">
                        <div class="mb-5">
                            <p class="text-gray-700 mb-4">
                                Apakah Anda yakin ingin menghapus data ternak berikut?
                            </p>
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-4 rounded-xl border-2 border-gray-200">
                                <div class="flex items-center gap-3">
                                    <div class="bg-red-100 rounded-full p-3">
                                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-bold text-gray-900 text-lg">{{ $animal->nama_hewan }}</p>
                                        <p class="text-sm text-gray-600 font-medium">{{ $animal->kode_hewan }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Warning for animals with breeding records --}}
                        @if($animal->reproduksis_as_betina_count > 0 || $animal->reproduksis_as_jantan_count > 0)
                            <div class="mb-4 bg-amber-50 border-l-4 border-amber-500 p-4 rounded-r-xl">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                        </path>
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm font-bold text-amber-900 mb-1">PERINGATAN</p>
                                        <p class="text-sm text-amber-800">
                                            Ternak ini memiliki <span
                                                class="font-bold">{{ $animal->reproduksis_as_betina_count + $animal->reproduksis_as_jantan_count }}</span>
                                            catatan perkawinan yang akan ikut terhapus.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Warning for animals with health records --}}
                        @if($animal->health_records_count > 0)
                            <div class="mb-4 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-xl">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm font-bold text-blue-900 mb-1">INFORMASI</p>
                                        <p class="text-sm text-blue-800">
                                            Ternak ini memiliki <span
                                                class="font-bold">{{ $animal->health_records_count }}</span>
                                            catatan kesehatan yang akan ikut terhapus.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                            <p class="text-sm text-red-800 font-semibold flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                                Data yang dihapus tidak dapat dikembalikan
                            </p>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 border-t border-gray-200 rounded-b-2xl">
                        <button type="button" onclick="closeDeleteModal()"
                            class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border-2 border-gray-300 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 rounded-xl shadow-lg hover:shadow-xl transition-all">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                                Ya, Hapus Data
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Validation data from backend
        @php
            $isPregnant = $animal->jenis_kelamin === 'betina' && \App\Models\Perkawinan::where('betina_id', $animal->id)->where('status_reproduksi', 'bunting')->exists();

            $perkawinanIds = \App\Models\Perkawinan::where(function ($q) use ($animal) {
                $q->where('jantan_id', $animal->id)->orWhere('betina_id', $animal->id);
            })->pluck('id');

            $offspringCount = \App\Models\Animal::whereIn('perkawinan_id', $perkawinanIds)->count();
        @endphp

        const animalValidation = {
            isPregnant: {{ $isPregnant ? 'true' : 'false' }},
            hasOffspring: {{ $offspringCount > 0 ? 'true' : 'false' }},
            offspringCount: {{ $offspringCount }}
            };

        function checkBeforeDelete() {
            // Check if pregnant
            if (animalValidation.isPregnant) {
                alert('❌ TIDAK DAPAT MENGHAPUS\n\nTernak betina ini sedang bunting. Silakan ubah status reproduksi terlebih dahulu atau tunggu hingga melahirkan.');
                return;
            }

            // Check if has offspring
            if (animalValidation.hasOffspring) {
                alert(`❌ TIDAK DAPAT MENGHAPUS\n\nTernak ini memiliki ${animalValidation.offspringCount} anak. Anak-anak akan kehilangan informasi orang tua jika induk dihapus.`);
                return;
            }

            // If all checks pass, open modal
            openDeleteModal();
        }

        function openDeleteModal() {
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Close modal on ESC key
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeDeleteModal();
            }
        });
    </script>
@else
    {{-- Index Page Version - Alpine.js with currentAnimal --}}
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">

        {{-- Background overlay --}}
        <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showDeleteModal = false"
            class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>

        {{-- Modal container --}}
        <div class="flex min-h-full items-center justify-center p-4">
            {{-- Modal content --}}
            <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95" @click.stop
                class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg">

                <form :action="`{{ route('ternak.index') }}/${currentAnimal?.id}`" method="POST">
                    @csrf
                    @method('DELETE')

                    {{-- Modal Header --}}
                    <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-5 rounded-t-2xl">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="bg-white/20 rounded-full p-2">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white">Hapus Data Ternak</h3>
                                    <p class="text-red-100 text-sm mt-0.5">Tindakan ini tidak dapat dibatalkan</p>
                                </div>
                            </div>
                            <button type="button" @click="showDeleteModal = false"
                                class="text-white/80 hover:text-white hover:bg-white/10 rounded-lg p-1.5 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Modal Body --}}
                    <div class="px-6 py-6">
                        <div class="mb-5">
                            <p class="text-gray-700 mb-4">
                                Apakah Anda yakin ingin menghapus data ternak berikut?
                            </p>
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-4 rounded-xl border-2 border-gray-200">
                                <div class="flex items-center gap-3">
                                    <div class="bg-red-100 rounded-full p-3">
                                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-bold text-gray-900 text-lg" x-text="currentAnimal?.nama_hewan"></p>
                                        <p class="text-sm text-gray-600 font-medium" x-text="currentAnimal?.kode_hewan"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                            <p class="text-sm text-red-800 font-semibold flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                                Data yang dihapus tidak dapat dikembalikan
                            </p>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 border-t border-gray-200 rounded-b-2xl">
                        <button type="button" @click="showDeleteModal = false"
                            class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border-2 border-gray-300 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 rounded-xl shadow-lg hover:shadow-xl transition-all">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                                Ya, Hapus Data
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
@endif