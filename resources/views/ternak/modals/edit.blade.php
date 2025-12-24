{{-- Edit Modal --}}
<div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
    role="dialog" aria-modal="true">

    {{-- Background overlay --}}
    <div x-show="showEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showEditModal = false"
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"></div>

    {{-- Modal container --}}
    <div class="flex min-h-full items-center justify-center p-4">
        {{-- Modal content --}}
        <div x-show="showEditModal" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95" @click.stop x-show="currentAnimal"
            class="relative bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden">

            <form :action="`{{ route('ternak.index') }}/${currentAnimal?.id}`" method="POST">
                @csrf
                @method('PUT')

                {{-- Modal Header --}}
                <div class="bg-emerald-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white">Edit Data Ternak</h3>
                        <button type="button" @click="showEditModal = false"
                            class="text-white/80 hover:text-white transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Modal Body --}}
                <div class="px-6 py-5 max-h-[calc(90vh-150px)] overflow-y-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        {{-- Kode Hewan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Kode Hewan</label>
                            <input type="text" :value="currentAnimal?.kode_hewan" readonly
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-600">
                        </div>

                        {{-- Nama Hewan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Nama Hewan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_hewan" required :value="currentAnimal?.nama_hewan"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        {{-- Jenis Hewan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Hewan</label>
                            <input type="text"
                                :value="currentAnimal?.jenis_hewan ? currentAnimal.jenis_hewan.charAt(0).toUpperCase() + currentAnimal.jenis_hewan.slice(1) : ''"
                                readonly
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-600">
                            <input type="hidden" name="jenis_hewan" :value="currentAnimal?.jenis_hewan">
                        </div>

                        {{-- Ras Hewan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Ras Hewan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="ras_hewan" required :value="currentAnimal?.ras_hewan"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Jenis Kelamin <span class="text-red-500">*</span>
                            </label>
                            <select name="jenis_kelamin" required
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="jantan" :selected="currentAnimal?.jenis_kelamin === 'jantan'">Jantan
                                </option>
                                <option value="betina" :selected="currentAnimal?.jenis_kelamin === 'betina'">Betina
                                </option>
                            </select>
                        </div>

                        {{-- Tanggal Lahir --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Tanggal Lahir <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_lahir" required :value="currentAnimal?.tanggal_lahir"
                                max="{{ date('Y-m-d') }}"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        {{-- Berat Badan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Berat Badan (kg) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="berat_badan" required step="0.01" min="0"
                                :value="currentAnimal?.berat_badan"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        {{-- Status Kesehatan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Status Kesehatan <span class="text-red-500">*</span>
                            </label>
                            <select name="status_kesehatan" required
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="sehat" :selected="currentAnimal?.status_kesehatan === 'sehat'">Sehat
                                </option>
                                <option value="sakit" :selected="currentAnimal?.status_kesehatan === 'sakit'">Sakit
                                </option>
                                <option value="karantina" :selected="currentAnimal?.status_kesehatan === 'karantina'">
                                    Karantina</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="bg-gray-50 px-6 py-3 flex justify-end gap-2 border-t">
                    <button type="button" @click="showEditModal = false"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg">
                        Simpan Perubahan
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