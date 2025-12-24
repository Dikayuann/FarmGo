{{-- Create Modal --}}
<div x-show="showCreateModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
    role="dialog" aria-modal="true">

    {{-- Background overlay --}}
    <div x-show="showCreateModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showCreateModal = false"
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"></div>

    {{-- Modal container --}}
    <div class="flex min-h-full items-center justify-center p-4">
        {{-- Modal content --}}
        <div x-show="showCreateModal" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95" @click.stop
            class="relative bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden">

            <form action="{{ route('ternak.store') }}" method="POST">
                @csrf

                {{-- Modal Header --}}
                <div class="bg-emerald-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white">Tambah Hewan Ternak</h3>
                        <button type="button" @click="showCreateModal = false"
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

                        {{-- Nama Hewan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Nama Hewan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_hewan" required x-model="nama_hewan"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white"
                                placeholder="Contoh: Bella">
                        </div>

                        {{-- Jenis Hewan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Jenis Hewan <span class="text-red-500">*</span>
                            </label>
                            <select name="jenis_hewan" required x-model="jenis_hewan" @change="updateBreedOptions()"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white">
                                <option value="" selected>Pilih Jenis</option>
                                <option value="sapi">Sapi</option>
                                <option value="kambing">Kambing</option>
                                <option value="domba">Domba</option>
                            </select>
                        </div>

                        {{-- Ras Hewan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Ras Hewan <span class="text-red-500">*</span>
                            </label>
                            <select name="ras_hewan" required x-model="ras_hewan" id="ras_hewan_create"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white">
                                <option value="" selected>Pilih Ras</option>
                            </select>
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Jenis Kelamin <span class="text-red-500">*</span>
                            </label>
                            <select name="jenis_kelamin" required x-model="jenis_kelamin"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white">
                                <option value="" selected>Pilih Jenis Kelamin</option>
                                <option value="jantan">Jantan</option>
                                <option value="betina">Betina</option>
                            </select>
                        </div>

                        {{-- Tanggal Lahir --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Tanggal Lahir <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_lahir" required x-model="tanggal_lahir"
                                max="{{ date('Y-m-d') }}"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white">
                        </div>

                        {{-- Berat Badan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Berat Badan (kg) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="berat_badan" required step="0.01" min="0"
                                x-model="berat_badan"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white"
                                placeholder="Contoh: 250.5">
                        </div>

                        {{-- Status Kesehatan --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Status Kesehatan <span class="text-red-500">*</span>
                            </label>
                            <select name="status_kesehatan" required x-model="status_kesehatan"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white">
                                <option value="" selected>Pilih Status</option>
                                <option value="sehat">Sehat</option>
                                <option value="sakit">Sakit</option>
                                <option value="karantina">Karantina</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="bg-gray-50 px-6 py-3 flex justify-end gap-2 border-t">
                    <button type="button" @click="showCreateModal = false"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg">
                        Tambah Ternak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Breed options for dependent dropdown
    window.breedOptions = {
        'sapi': [
            'Simental',
            'Limousin',
            'Brahman',
            'Bali',
            'Madura',
            'Ongole',
            'PO (Peranakan Ongole)',
            'FH (Friesian Holstein)',
            'Jersey',
            'Angus',
            'Hereford'
        ],
        'kambing': [
            'Etawa',
            'Jawarandu',
            'Kacang',
            'Boer',
            'Saanen',
            'Anglo Nubian',
            'Gembrong',
            'Senduro',
            'Samosir'
        ],
        'domba': [
            'Merino',
            'Dorper',
            'Suffolk',
            'Texel',
            'Garut',
            'Priangan',
            'Ekor Tipis',
            'Ekor Gemuk',
            'Barbados'
        ]
    };

    function updateBreedOptions() {
        const jenisSelect = document.querySelector('select[name="jenis_hewan"]');
        const rasSelect = document.getElementById('ras_hewan_create');
        
        if (!jenisSelect || !rasSelect) return;
        
        const selectedJenis = jenisSelect.value;
        
        // Clear current options
        rasSelect.innerHTML = '<option value="">Pilih Ras</option>';
        
        // Add new options based on selected jenis
        if (selectedJenis && window.breedOptions[selectedJenis]) {
            window.breedOptions[selectedJenis].forEach(breed => {
                const option = document.createElement('option');
                option.value = breed;
                option.textContent = breed;
                rasSelect.appendChild(option);
            });
        }
    }
</script>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>