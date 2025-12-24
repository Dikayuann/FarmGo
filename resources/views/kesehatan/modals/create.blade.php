{{-- Create Health Record Modal --}}
<div x-show="showCreateModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
    role="dialog" aria-modal="true">

    {{-- Background overlay --}}
    <div x-show="showCreateModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showCreateModal = false"
        class="fixed inset-0 bg-gray-800/30 backdrop-blur-sm transition-opacity"></div>

    {{-- Modal container --}}
    <div class="flex min-h-full items-center justify-center p-4">
        {{-- Modal content --}}
        <div x-show="showCreateModal" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95" @click.stop
            class="relative bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden">

            <form method="POST" action="{{ route('kesehatan.store') }}">
                @csrf

                {{-- Modal Header --}}
                <div class="bg-emerald-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white" id="modal-title">
                            <i class="fa-solid fa-plus mr-2"></i>
                            Tambah Catatan Kesehatan
                        </h3>
                        <button type="button" @click="showCreateModal = false"
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
                            <label for="animal_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Hewan <span class="text-red-500">*</span>
                            </label>
                            <select id="animal_id" name="animal_id" required
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-lg">
                                <option value="">Pilih Hewan</option>
                                @foreach ($animals as $animal)
                                    <option value="{{ $animal->id }}">
                                        {{ $animal->kode_hewan }} - {{ $animal->nama_hewan }}
                                        ({{ ucfirst($animal->jenis_hewan) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('animal_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tanggal Pemeriksaan --}}
                        <div>
                            <label for="tanggal_pemeriksaan" class="block text-sm font-medium text-gray-700 mb-1">
                                Tanggal Pemeriksaan <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" id="tanggal_pemeriksaan" name="tanggal_pemeriksaan" required
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                            @error('tanggal_pemeriksaan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Jenis Pemeriksaan --}}
                        <div>
                            <label for="jenis_pemeriksaan" class="block text-sm font-medium text-gray-700 mb-1">
                                Jenis Pemeriksaan <span class="text-red-500">*</span>
                            </label>
                            <select id="jenis_pemeriksaan" name="jenis_pemeriksaan" required
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-lg">
                                <option value="">Pilih Jenis</option>
                                <option value="rutin">Rutin</option>
                                <option value="darurat">Darurat</option>
                                <option value="follow_up">Follow Up</option>
                            </select>
                            @error('jenis_pemeriksaan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Berat Badan --}}
                        <div>
                            <label for="berat_badan" class="block text-sm font-medium text-gray-700 mb-1">
                                Berat Badan (kg) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="berat_badan" name="berat_badan" step="0.01" min="0" required
                                placeholder="0.00"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                            @error('berat_badan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Suhu Tubuh --}}
                        <div>
                            <label for="suhu_tubuh" class="block text-sm font-medium text-gray-700 mb-1">
                                Suhu Tubuh (°C)
                            </label>
                            <input type="number" id="suhu_tubuh" name="suhu_tubuh" step="0.1" min="0" max="50"
                                placeholder="38.5"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                            @error('suhu_tubuh')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status Kesehatan --}}
                        <div class="md:col-span-2">
                            <label for="status_kesehatan" class="block text-sm font-medium text-gray-700 mb-1">
                                Status Kesehatan <span class="text-red-500">*</span>
                            </label>
                            <select id="status_kesehatan" name="status_kesehatan" required
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-lg">
                                <option value="">Pilih Status</option>
                                <option value="sehat">Sehat</option>
                                <option value="sakit">Sakit</option>
                                <option value="dalam_perawatan">Dalam Perawatan</option>
                                <option value="sembuh">Sembuh</option>
                            </select>
                            @error('status_kesehatan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Gejala --}}
                        <div class="md:col-span-2">
                            <label for="gejala" class="block text-sm font-medium text-gray-700 mb-1">
                                Gejala / Keluhan
                            </label>
                            <textarea id="gejala" name="gejala" rows="2"
                                placeholder="Tuliskan gejala atau keluhan yang diamati..."
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"></textarea>
                            @error('gejala')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Diagnosis --}}
                        <div class="md:col-span-2">
                            <label for="diagnosis" class="block text-sm font-medium text-gray-700 mb-1">
                                Diagnosis
                            </label>
                            <textarea id="diagnosis" name="diagnosis" rows="2"
                                placeholder="Diagnosis dari dokter hewan..."
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"></textarea>
                            @error('diagnosis')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tindakan --}}
                        <div class="md:col-span-2">
                            <label for="tindakan" class="block text-sm font-medium text-gray-700 mb-1">
                                Tindakan / Treatment
                            </label>
                            <textarea id="tindakan" name="tindakan" rows="2" placeholder="Tindakan yang diberikan..."
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"></textarea>
                            @error('tindakan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Obat --}}
                        <div>
                            <label for="obat" class="block text-sm font-medium text-gray-700 mb-1">
                                Obat
                            </label>
                            <input type="text" id="obat" name="obat" placeholder="Nama obat yang diberikan"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                            @error('obat')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Biaya --}}
                        <div>
                            <label for="biaya" class="block text-sm font-medium text-gray-700 mb-1">
                                Biaya (Rp)
                            </label>
                            <input type="number" id="biaya" name="biaya" step="0.01" min="0" placeholder="0"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                            @error('biaya')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Pemeriksaan Berikutnya --}}
                        <div class="md:col-span-2">
                            <label for="pemeriksaan_berikutnya" class="block text-sm font-medium text-gray-700 mb-1">
                                Jadwal Pemeriksaan Berikutnya
                            </label>
                            <input type="date" id="pemeriksaan_berikutnya" name="pemeriksaan_berikutnya"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                            @error('pemeriksaan_berikutnya')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Catatan --}}
                        <div class="md:col-span-2">
                            <label for="catatan" class="block text-sm font-medium text-gray-700 mb-1">
                                Catatan Tambahan
                            </label>
                            <textarea id="catatan" name="catatan" rows="2" placeholder="Catatan tambahan..."
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"></textarea>
                            @error('catatan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3">
                    <button type="submit"
                        class="inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition">
                        <i class="fa-solid fa-save mr-2"></i>
                        Simpan
                    </button>
                    <button type="button" @click="showCreateModal = false"
                        class="inline-flex justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>