{{-- Delete Health Record Modal --}}
<div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
    role="dialog" aria-modal="true">

    {{-- Background overlay --}}
    <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showDeleteModal = false"
        class="fixed inset-0 bg-gray-800/30 backdrop-blur-sm transition-opacity"></div>

    {{-- Modal container --}}
    <div class="flex min-h-full items-center justify-center p-4">
        {{-- Modal content --}}
        <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95" @click.stop
            class="relative bg-white rounded-xl shadow-xl w-full max-w-lg overflow-hidden">


            <div class="bg-white px-6 pt-5 pb-4">
                {{-- Icon --}}
                <div class="sm:flex sm:items-start">
                    <div
                        class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Hapus Catatan Kesehatan
                        </h3>
                        <div class="mt-2" x-show="currentRecord">
                            <p class="text-sm text-gray-500">
                                Apakah Anda yakin ingin menghapus catatan kesehatan untuk hewan
                                <span class="font-semibold text-gray-700"
                                    x-text="currentRecord?.animal?.nama_hewan"></span>
                                pada tanggal
                                <span class="font-semibold text-gray-700"
                                    x-text="currentRecord ? new Date(currentRecord.tanggal_pemeriksaan).toLocaleDateString('id-ID') : ''"></span>?
                                Data yang sudah dihapus tidak dapat dikembalikan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3">
                <form x-show="currentRecord" :action="currentRecord ? `{{ url('kesehatan') }}/${currentRecord.id}` : ''"
                    method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                        <i class="fa-solid fa-trash mr-2"></i>
                        Hapus
                    </button>
                </form>
                <button type="button" @click="showDeleteModal = false"
                    class="inline-flex justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                    Batal
                </button>
            </div>

        </div>
    </div>
</div>