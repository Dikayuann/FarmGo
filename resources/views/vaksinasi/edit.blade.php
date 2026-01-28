@extends('layouts.app')

@section('title', 'Edit Vaksinasi - FarmGo')
@section('page-title', 'Edit Vaksinasi')

@section('content')
<div>
    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('vaksinasi.index') }}"
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
                <i class="fa-solid fa-edit mr-2"></i>
                Edit Data Vaksinasi
            </h1>
            <p class="text-emerald-100 mt-1">Perbarui informasi vaksinasi di bawah ini</p>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('vaksinasi.update', $vaksinasi->id) }}">
            @csrf
            @method('PUT')

            <div class="px-8 py-6">
                {{-- Informasi Wajib --}}
                <div class="mb-6">
                    <h4 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                        <i class="fa-solid fa-circle-info text-emerald-600 mr-2"></i>
                        Informasi Wajib
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Animal Select - Searchable --}}
                        <div class="md:col-span-2 relative" x-data="{
                            open: false,
                            search: '',
                            selected: null,
                            animals: {{ json_encode($animals->map(fn($a) => ['id' => $a->id, 'kode' => $a->kode_hewan, 'nama' => $a->nama_hewan, 'jenis' => ucfirst($a->jenis_hewan)])) }},
                            init() {
                                // Pre-select animal if provided
                                let preselectedId = {{ $vaksinasi->animal_id }};
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

                        {{-- Tanggal Vaksinasi --}}
                        <div>
                            <label for="tanggal_vaksin" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Vaksinasi <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="tanggal_vaksin" name="tanggal_vaksin" required 
                                value="{{ old('tanggal_vaksin', $vaksinasi->tanggal_vaksin->format('Y-m-d')) }}"
                                max="{{ now()->format('Y-m-d') }}"
                                class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 px-3 py-2">
                            @error('tanggal_vaksin')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Jenis Vaksin --}}
                        <div>
                            <label for="jenis_vaksin" class="block text-sm font-medium text-gray-700 mb-2">
                                Jenis Vaksin <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="jenis_vaksin" name="jenis_vaksin" required 
                                value="{{ old('jenis_vaksin', $vaksinasi->jenis_vaksin) }}"
                                list="vaksin-suggestions"
                                placeholder="Contoh: Antraks, PMK, Brucellosis"
                                class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 px-3 py-2">
                            <datalist id="vaksin-suggestions">
                                <option value="Antraks">
                                <option value="Brucellosis">
                                <option value="PMK (Penyakit Mulut dan Kuku)">
                                <option value="SE (Septicaemia Epizootica)">
                                <option value="Rabies">
                                <option value="BEF (Bovine Ephemeral Fever)">
                                <option value="LSD (Lumpy Skin Disease)">
                            </datalist>
                            @error('jenis_vaksin')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Dosis --}}
                        <div>
                            <label for="dosis" class="block text-sm font-medium text-gray-700 mb-2">
                                Dosis <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="dosis" name="dosis" required 
                                value="{{ old('dosis', $vaksinasi->dosis) }}"
                                placeholder="Contoh: 2 ml, 1 dosis"
                                class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 px-3 py-2">
                            @error('dosis')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Rute Pemberian --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Rute Pemberian <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="relative flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-emerald-50 transition">
                                    <input type="radio" name="rute_pemberian" value="oral" required
                                        {{ old('rute_pemberian', $vaksinasi->rute_pemberian) == 'oral' ? 'checked' : '' }}
                                        class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300">
                                    <span class="ml-2 text-sm font-medium text-gray-700">Oral</span>
                                </label>
                                <label class="relative flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-emerald-50 transition">
                                    <input type="radio" name="rute_pemberian" value="injeksi_im" required
                                        {{ old('rute_pemberian', $vaksinasi->rute_pemberian) == 'injeksi_im' ? 'checked' : '' }}
                                        class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300">
                                    <span class="ml-2 text-sm font-medium text-gray-700">Injeksi IM</span>
                                </label>
                                <label class="relative flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-emerald-50 transition">
                                    <input type="radio" name="rute_pemberian" value="injeksi_sc" required
                                        {{ old('rute_pemberian', $vaksinasi->rute_pemberian) == 'injeksi_sc' ? 'checked' : '' }}
                                        class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300">
                                    <span class="ml-2 text-sm font-medium text-gray-700">Injeksi SC</span>
                                </label>
                                <label class="relative flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-emerald-50 transition">
                                    <input type="radio" name="rute_pemberian" value="injeksi_iv" required
                                        {{ old('rute_pemberian', $vaksinasi->rute_pemberian) == 'injeksi_iv' ? 'checked' : '' }}
                                        class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300">
                                    <span class="ml-2 text-sm font-medium text-gray-700">Injeksi IV</span>
                                </label>
                            </div>
                            @error('rute_pemberian')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Masa Penarikan --}}
                        <div>
                            <label for="masa_penarikan" class="block text-sm font-medium text-gray-700 mb-2">
                                Masa Penarikan <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" id="masa_penarikan" name="masa_penarikan" required 
                                    value="{{ old('masa_penarikan', $vaksinasi->masa_penarikan) }}"
                                    min="0"
                                    placeholder="0"
                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 px-3 py-2 pr-12">
                                <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 text-sm">
                                    hari
                                </span>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Withdrawal period sebelum produk dapat dikonsumsi</p>
                            @error('masa_penarikan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Nama Dokter --}}
                        <div>
                            <label for="nama_dokter" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Dokter/Petugas <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nama_dokter" name="nama_dokter" required 
                                value="{{ old('nama_dokter', $vaksinasi->nama_dokter) }}"
                                placeholder="Nama dokter hewan atau petugas"
                                class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 px-3 py-2">
                            @error('nama_dokter')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Jadwal Berikutnya --}}
                        <div class="md:col-span-2">
                            <label for="jadwal_berikutnya" class="block text-sm font-medium text-gray-700 mb-2">
                                Jadwal Vaksinasi Berikutnya
                            </label>
                            <input type="date" id="jadwal_berikutnya" name="jadwal_berikutnya" 
                                value="{{ old('jadwal_berikutnya', $vaksinasi->jadwal_berikutnya?->format('Y-m-d')) }}"
                                min="{{ now()->addDay()->format('Y-m-d') }}"
                                class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 px-3 py-2">
                            <p class="mt-1 text-xs text-gray-500">Opsional. Anda akan menerima notifikasi pengingat jika diisi.</p>
                            @error('jadwal_berikutnya')
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
                                class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 px-3 py-2">{{ old('catatan', $vaksinasi->catatan) }}</textarea>
                            @error('catatan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="bg-gray-50 px-8 py-4 flex justify-end gap-3 border-t">
                <a href="{{ route('vaksinasi.index') }}"
                    class="inline-flex justify-center px-6 py-2 border border-gray-300 text-sm font-medium rounded-lg shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition">
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex justify-center items-center px-6 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition">
                    <i class="fa-solid fa-save mr-2"></i>
                    Perbarui
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
