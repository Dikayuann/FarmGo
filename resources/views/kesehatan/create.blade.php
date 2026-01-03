@extends('layouts.app')

@section('title', 'Tambah Catatan Kesehatan - FarmGo')
@section('page-title', 'Tambah Catatan Kesehatan')

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('kesehatan.index') }}"
            class="inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-700 font-medium transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke Daftar
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        {{-- Header --}}
        <div class="bg-emerald-600 px-8 py-6">
            <h1 class="text-2xl font-bold text-white">
                <i class="fa-solid fa-plus mr-2"></i>
                Tambah Catatan Kesehatan
            </h1>
            <p class="text-emerald-100 mt-1">Isi form di bawah untuk menambahkan catatan kesehatan baru</p>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('kesehatan.store') }}">
            @csrf

            <div class="px-8 py-6" x-data="{ showOptional: false }">
                {{-- Informasi Wajib --}}
                <div class="mb-6">
                    <h4 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                        <i class="fa-solid fa-circle-info text-emerald-600 mr-2"></i>
                        Informasi Wajib
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Animal Select - Searchable --}}
                        <div class="md:col-span-2" x-data="{
                            open: false,
                            search: '',
                            selected: null,
                            animals: {{ json_encode($animals->map(fn($a) => ['id' => $a->id, 'kode' => $a->kode_hewan, 'nama' => $a->nama_hewan, 'jenis' => ucfirst($a->jenis_hewan)])) }},
                            init() {
                                // Pre-select animal if provided
                                let preselectedId = {{ $animalId ?? 'null' }};
                                if (preselectedId) {
                                    this.selected = this.animals.find(a => a.id == preselectedId);
                                }
                            },
                            get filteredAnimals() {
                                if (this.search === '') return this.animals;
                                return this.animals.filter(animal => 
                                    animal.kode.toLowerCase().includes(this.search.toLowerCase()) ||
                                    animal.nama.toLowerCase().includes(this.search.toLowerCase()) ||
                                    animal.jenis.toLowerCase().includes(this.search.toLowerCase())
                                );
                            },
                            selectAnimal(animal) {
                                this.selected = animal;
                                this.open = false;
                                this.search = '';
                            }
                        }" @click.away="open = false">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Hewan <span class="text-red-500">*</span>
                            </label>
                            
                            {{-- Hidden input for form submission --}}
                            <input type="hidden" name="animal_id" :value="selected?.id" required>
                            
                            {{-- Custom Select Button --}}
                            <button type="button" @click="open = !open"
                                class="relative w-full bg-white border border-gray-300 rounded-lg shadow-sm pl-3 pr-10 py-3 text-left cursor-pointer focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <span class="block truncate" x-text="selected ? `${selected.kode} - ${selected.nama} (${selected.jenis})` : 'Pilih atau cari hewan...'"></span>
                                <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                    <i class="fa-solid fa-chevron-down text-gray-400"></i>
                                </span>
                            </button>

                            {{-- Dropdown --}}
                            <div x-show="open" x-transition
                                class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-lg py-1 ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none">
                                
                                {{-- Search Input --}}
                                <div class="sticky top-0 bg-white px-2 py-2 border-b">
                                    <input type="text" x-model="search" @click.stop
                                        placeholder="Cari kode, nama, atau jenis..."
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                                </div>

                                {{-- Animal List --}}
                                <template x-for="animal in filteredAnimals" :key="animal.id">
                                    <div @click="selectAnimal(animal)"
                                        class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-emerald-50 transition"
                                        :class="selected?.id === animal.id ? 'bg-emerald-100' : ''">
                                        <span class="block truncate" :class="selected?.id === animal.id ? 'font-semibold' : 'font-normal'">
                                            <span x-text="animal.kode"></span> - 
                                            <span x-text="animal.nama"></span>
                                            (<span x-text="animal.jenis"></span>)
                                        </span>
                                        <span x-show="selected?.id === animal.id"
                                            class="absolute inset-y-0 right-0 flex items-center pr-4 text-emerald-600">
                                            <i class="fa-solid fa-check"></i>
                                        </span>
                                    </div>
                                </template>

                                {{-- No Results --}}
                                <div x-show="filteredAnimals.length === 0" class="px-3 py-2 text-gray-500 text-sm">
                                    Tidak ada hewan yang ditemukan
                                </div>
                            </div>

                            @error('animal_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tanggal Pemeriksaan --}}
                        <div>
                            <label for="tanggal_pemeriksaan" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal & Waktu Pemeriksaan <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" id="tanggal_pemeriksaan" name="tanggal_pemeriksaan" required value="{{ old('tanggal_pemeriksaan', now()->format('Y-m-d\TH:i')) }}"
                                class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 px-3 py-2">
                            @error('tanggal_pemeriksaan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Jenis Pemeriksaan --}}
                        <div>
                            <label for="jenis_pemeriksaan" class="block text-sm font-medium text-gray-700 mb-2">
                                Jenis Pemeriksaan <span class="text-red-500">*</span>
                            </label>
                            <select id="jenis_pemeriksaan" name="jenis_pemeriksaan" required
                                class="block w-full pl-3 pr-10 py-2 border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 rounded-lg">
                                <option value="">Pilih Jenis</option>
                                <option value="rutin" {{ old('jenis_pemeriksaan') == 'rutin' ? 'selected' : '' }}>Rutin</option>
                                <option value="darurat" {{ old('jenis_pemeriksaan') == 'darurat' ? 'selected' : '' }}>Darurat</option>
                                <option value="follow_up" {{ old('jenis_pemeriksaan') == 'follow_up' ? 'selected' : '' }}>Follow Up</option>
                            </select>
                            @error('jenis_pemeriksaan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Berat Badan --}}
                        <div>
                            <label for="berat_badan" class="block text-sm font-medium text-gray-700 mb-2">
                                Berat Badan (kg) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="berat_badan" name="berat_badan" step="0.01" min="0" required value="{{ old('berat_badan') }}"
                                placeholder="Contoh: 350.5"
                                class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 px-3 py-2">
                            @error('berat_badan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status Kesehatan --}}
                        <div>
                            <label for="status_kesehatan" class="block text-sm font-medium text-gray-700 mb-2">
                                Status Kesehatan <span class="text-red-500">*</span>
                            </label>
                            <select id="status_kesehatan" name="status_kesehatan" required
                                class="block w-full pl-3 pr-10 py-2 border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 rounded-lg">
                                <option value="">Pilih Status</option>
                                <option value="sehat" {{ old('status_kesehatan') == 'sehat' ? 'selected' : '' }}>Sehat</option>
                                <option value="sakit" {{ old('status_kesehatan') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                <option value="dalam_perawatan" {{ old('status_kesehatan') == 'dalam_perawatan' ? 'selected' : '' }}>Dalam Perawatan</option>
                                <option value="sembuh" {{ old('status_kesehatan') == 'sembuh' ? 'selected' : '' }}>Sembuh</option>
                            </select>
                            @error('status_kesehatan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Divider --}}
                <div class="border-t border-gray-200 my-6"></div>

                {{-- Informasi Tambahan (Opsional) - Collapsible --}}
                <div>
                    <button type="button" @click="showOptional = !showOptional"
                        class="w-full flex items-center justify-between text-lg font-semibold text-gray-700 hover:text-emerald-600 transition py-2">
                        <span class="flex items-center">
                            <i class="fa-solid fa-notes-medical text-emerald-600 mr-2"></i>
                            Informasi Tambahan (Opsional)
                        </span>
                        <i class="fa-solid transition-transform duration-200"
                            :class="showOptional ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>

                    <div x-show="showOptional" x-collapse class="mt-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Suhu Tubuh --}}
                            <div>
                                <label for="suhu_tubuh" class="block text-sm font-medium text-gray-700 mb-2">
                                    Suhu Tubuh (°C)
                                </label>
                                <input type="number" id="suhu_tubuh" name="suhu_tubuh" step="0.1" min="0" max="50" value="{{ old('suhu_tubuh') }}"
                                    placeholder="Contoh: 38.5"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 px-3 py-2">
                                @error('suhu_tubuh')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Biaya --}}
                            <div>
                                <label for="biaya" class="block text-sm font-medium text-gray-700 mb-2">
                                    Biaya Pemeriksaan (Rp)
                                </label>
                                <input type="number" id="biaya" name="biaya" step="1000" min="0" value="{{ old('biaya') }}"
                                    placeholder="Contoh: 150000"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 px-3 py-2">
                                @error('biaya')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Gejala --}}
                            <div class="md:col-span-2">
                                <label for="gejala" class="block text-sm font-medium text-gray-700 mb-2">
                                    Gejala / Keluhan
                                </label>
                                <textarea id="gejala" name="gejala" rows="2"
                                    placeholder="Contoh: Nafsu makan menurun, terlihat lemas"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 px-3 py-2">{{ old('gejala') }}</textarea>
                                @error('gejala')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Diagnosis --}}
                            <div class="md:col-span-2">
                                <label for="diagnosis" class="block text-sm font-medium text-gray-700 mb-2">
                                    Diagnosis
                                </label>
                                <textarea id="diagnosis" name="diagnosis" rows="2"
                                    placeholder="Diagnosis dari dokter hewan (jika ada)"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 px-3 py-2">{{ old('diagnosis') }}</textarea>
                                @error('diagnosis')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Tindakan --}}
                            <div class="md:col-span-2">
                                <label for="tindakan" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tindakan / Treatment
                                </label>
                                <textarea id="tindakan" name="tindakan" rows="2"
                                    placeholder="Tindakan yang diberikan (jika ada)"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 px-3 py-2">{{ old('tindakan') }}</textarea>
                                @error('tindakan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Obat --}}
                            <div class="md:col-span-2">
                                <label for="obat" class="block text-sm font-medium text-gray-700 mb-2">
                                    Obat yang Diberikan
                                </label>
                                <input type="text" id="obat" name="obat" value="{{ old('obat') }}"
                                    placeholder="Contoh: Antibiotik, Vitamin, dll"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 px-3 py-2">
                                @error('obat')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Pemeriksaan Berikutnya --}}
                            <div class="md:col-span-2">
                                <label for="pemeriksaan_berikutnya"
                                    class="block text-sm font-medium text-gray-700 mb-2">
                                    Jadwal Pemeriksaan Berikutnya
                                </label>
                                <input type="date" id="pemeriksaan_berikutnya" name="pemeriksaan_berikutnya" value="{{ old('pemeriksaan_berikutnya') }}"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 px-3 py-2">
                                @error('pemeriksaan_berikutnya')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Catatan --}}
                            <div class="md:col-span-2">
                                <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                                    Catatan Tambahan
                                </label>
                                <textarea id="catatan" name="catatan" rows="3"
                                    placeholder="Catatan lain yang perlu dicatat..."
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 px-3 py-2">{{ old('catatan') }}</textarea>
                                @error('catatan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="bg-gray-50 px-8 py-4 flex justify-end gap-3 border-t">
                <a href="{{ route('kesehatan.index') }}"
                    class="inline-flex justify-center px-6 py-2 border border-gray-300 text-sm font-medium rounded-lg shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition">
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex justify-center items-center px-6 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition">
                    <i class="fa-solid fa-save mr-2"></i>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
