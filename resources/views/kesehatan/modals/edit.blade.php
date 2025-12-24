{{-- Edit Health Record Modal --}}
<div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
    role="dialog" aria-modal="true">

    {{-- Background overlay --}}
    <div x-show="showEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showEditModal = false"
        class="fixed inset-0 bg-gray-800/30 backdrop-blur-sm transition-opacity"></div>

    {{-- Modal container --}}
    <div class="flex min-h-full items-center justify-center p-4">
        {{-- Modal content --}}
        <div x-show="showEditModal" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95" @click.stop
            class="relative bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden">

            <form x-show="currentRecord" :action="currentRecord ? `{{ url('kesehatan') }}/${currentRecord.id}` : ''"
                method="POST">
                @csrf
                @method('PUT')

                {{-- Modal Header --}}
                <div class="bg-emerald-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white" id="modal-title">
                            <i class="fa-solid fa-edit mr-2"></i>
                            Edit Catatan Kesehatan
                        </h3>
                        <button type="button" @click="showEditModal = false"
                            class="text-white hover:text-gray-200 transition">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Modal Body --}}
                <div class="bg-white px-6 py-5 max-h-[70vh] overflow-y-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Animal Select --}}
                        <div class="md:col-span-2">
                            <label for="edit_animal_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Hewan <span class="text-red-500">*</span>
                            </label>
                            <select id="edit_animal_id" name="animal_id" required :value="currentRecord?.animal_id"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-lg">
                                <option value="">Pilih Hewan</option>
                                @foreach ($animals as $animal)
                                    <option value="{{ $animal->id }}"
                                        :selected="currentRecord?.animal_id == {{ $animal->id }}">
                                        {{ $animal->kode_hewan }} - {{ $animal->nama_hewan }}
                                        ({{ ucfirst($animal->jenis_hewan) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tanggal Pemeriksaan --}}
                        <div>
                            <label for="edit_tanggal_pemeriksaan" class="block text-sm font-medium text-gray-700 mb-1">
                                Tanggal Pemeriksaan <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" id="edit_tanggal_pemeriksaan" name="tanggal_pemeriksaan"
                                required
                                :value="currentRecord ? currentRecord.tanggal_pemeriksaan.replace(' ', 'T').substring(0, 16) : ''"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                        </div>

                        {{-- Jenis Pemeriksaan --}}
                        <div>
                            <label for="edit_jenis_pemeriksaan" class="block text-sm font-medium text-gray-700 mb-1">
                                Jenis Pemeriksaan <span class="text-red-500">*</span>
                            </label>
                            <select id="edit_jenis_pemeriksaan" name="jenis_pemeriksaan" required
                                :value="currentRecord?.jenis_pemeriksaan"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-lg">
                                <option value="">Pilih Jenis</option>
                                <option value="rutin" :selected="currentRecord?.jenis_pemeriksaan === 'rutin'">Rutin
                                </option>
                                <option value="darurat" :selected="currentRecord?.jenis_pemeriksaan === 'darurat'">
                                    Darurat
                                </option>
                                <option value="follow_up" :selected="currentRecord?.jenis_pemeriksaan === 'follow_up'">
                                    Follow Up</option>
                            </select>
                        </div>

                        {{-- Berat Badan --}}
                        <div>
                            <label for="edit_berat_badan" class="block text-sm font-medium text-gray-700 mb-1">
                                Berat Badan (kg) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="edit_berat_badan" name="berat_badan" step="0.01" min="0" required
                                :value="currentRecord?.berat_badan"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                        </div>

                        {{-- Suhu Tubuh --}}
                        <div>
                            <label for="edit_suhu_tubuh" class="block text-sm font-medium text-gray-700 mb-1">
                                Suhu Tubuh (°C)
                            </label>
                            <input type="number" id="edit_suhu_tubuh" name="suhu_tubuh" step="0.1" min="0" max="50"
                                :value="currentRecord?.suhu_tubuh"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                        </div>

                        {{-- Status Kesehatan --}}
                        <div class="md:col-span-2">
                            <label for="edit_status_kesehatan" class="block text-sm font-medium text-gray-700 mb-1">
                                Status Kesehatan <span class="text-red-500">*</span>
                            </label>
                            <select id="edit_status_kesehatan" name="status_kesehatan" required
                                :value="currentRecord?.status_kesehatan"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-lg">
                                <option value="">Pilih Status</option>
                                <option value="sehat" :selected="currentRecord?.status_kesehatan === 'sehat'">Sehat
                                </option>
                                <option value="sakit" :selected="currentRecord?.status_kesehatan === 'sakit'">Sakit
                                </option>
                                <option value="dalam_perawatan"
                                    :selected="currentRecord?.status_kesehatan === 'dalam_perawatan'">Dalam Perawatan
                                </option>
                                <option value="sembuh" :selected="currentRecord?.status_kesehatan === 'sembuh'">Sembuh
                                </option>
                            </select>
                        </div>

                        {{-- Gejala --}}
                        <div class="md:col-span-2">
                            <label for="edit_gejala" class="block text-sm font-medium text-gray-700 mb-1">
                                Gejala / Keluhan
                            </label>
                            <textarea id="edit_gejala" name="gejala" rows="2" x-text="currentRecord?.gejala"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"></textarea>
                        </div>

                        {{-- Diagnosis --}}
                        <div class="md:col-span-2">
                            <label for="edit_diagnosis" class="block text-sm font-medium text-gray-700 mb-1">
                                Diagnosis
                            </label>
                            <textarea id="edit_diagnosis" name="diagnosis" rows="2" x-text="currentRecord?.diagnosis"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"></textarea>
                        </div>

                        {{-- Tindakan --}}
                        <div class="md:col-span-2">
                            <label for="edit_tindakan" class="block text-sm font-medium text-gray-700 mb-1">
                                Tindakan / Treatment
                            </label>
                            <textarea id="edit_tindakan" name="tindakan" rows="2" x-text="currentRecord?.tindakan"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"></textarea>
                        </div>

                        {{-- Obat --}}
                        <div>
                            <label for="edit_obat" class="block text-sm font-medium text-gray-700 mb-1">
                                Obat
                            </label>
                            <input type="text" id="edit_obat" name="obat" :value="currentRecord?.obat"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                        </div>

                        {{-- Biaya --}}
                        <div>
                            <label for="edit_biaya" class="block text-sm font-medium text-gray-700 mb-1">
                                Biaya (Rp)
                            </label>
                            <input type="number" id="edit_biaya" name="biaya" step="0.01" min="0"
                                :value="currentRecord?.biaya"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                        </div>

                        {{-- Pemeriksaan Berikutnya --}}
                        <div class="md:col-span-2">
                            <label for="edit_pemeriksaan_berikutnya"
                                class="block text-sm font-medium text-gray-700 mb-1">
                                Jadwal Pemeriksaan Berikutnya
                            </label>
                            <input type="date" id="edit_pemeriksaan_berikutnya" name="pemeriksaan_berikutnya"
                                :value="currentRecord?.pemeriksaan_berikutnya"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                        </div>

                        {{-- Catatan --}}
                        <div class="md:col-span-2">
                            <label for="edit_catatan" class="block text-sm font-medium text-gray-700 mb-1">
                                Catatan Tambahan
                            </label>
                            <textarea id="edit_catatan" name="catatan" rows="2" x-text="currentRecord?.catatan"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"></textarea>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3">
                    <button type="submit"
                        class="inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition">
                        <i class="fa-solid fa-save mr-2"></i>
                        Simpan Perubahan
                    </button>
                    <button type="button" @click="showEditModal = false"
                        class="inline-flex justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition">
                        Batal
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>