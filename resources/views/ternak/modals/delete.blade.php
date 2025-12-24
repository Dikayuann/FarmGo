{{-- Delete Modal --}}
<div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
    role="dialog" aria-modal="true">

    {{-- Background overlay --}}
    <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showDeleteModal = false"
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"></div>

    {{-- Modal container --}}
    <div class="flex min-h-full items-center justify-center p-4">
        {{-- Modal content --}}
        <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95" @click.stop x-show="currentAnimal"
            class="relative bg-white rounded-xl shadow-xl w-full max-w-md">

            <form :action="`{{ route('ternak.index') }}/${currentAnimal?.id}`" method="POST">
                @csrf
                @method('DELETE')

                {{-- Modal Header --}}
                <div class="bg-red-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white">Hapus Data Ternak</h3>
                        <button type="button" @click="showDeleteModal = false"
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
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">Konfirmasi Penghapusan</h4>
                            <p class="text-sm text-gray-600 mb-3">
                                Apakah Anda yakin ingin menghapus data ternak berikut?
                            </p>
                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                                <p class="font-semibold text-gray-900" x-text="currentAnimal?.nama_hewan"></p>
                                <p class="text-sm text-gray-600" x-text="currentAnimal?.kode_hewan"></p>
                            </div>
                            <p class="text-sm text-red-600 mt-3 font-medium">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                                Data yang dihapus tidak dapat dikembalikan!
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="bg-gray-50 px-6 py-3 flex justify-end gap-2 border-t">
                    <button type="button" @click="showDeleteModal = false"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg">
                        Ya, Hapus Data
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