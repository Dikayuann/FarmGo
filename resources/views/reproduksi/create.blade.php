@extends('layouts.app')

@section('title', 'Tambah Catatan Reproduksi')

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-900">Tambah Catatan Reproduksi</h2>
                <a href="{{ route('reproduksi.index') }}" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </a>
            </div>

            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 rounded-xl p-4">
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('reproduksi.store') }}" method="POST" x-data="reproductionForm()">
                @csrf

                <!-- STEP 1: Sumber Pejantan -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">STEP 1: Sumber Pejantan</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <label class="relative flex items-center p-4 border-2 rounded-xl cursor-pointer transition"
                            :class="jantan_type === 'owned' ? 'border-green-600 bg-green-50' : 'border-gray-200 hover:border-green-300'">
                            <input type="radio" name="jantan_type" value="owned" x-model="jantan_type" class="sr-only">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <div
                                        class="bg-blue-100 text-blue-600 h-10 w-10 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">Milik Sendiri</p>
                                        <p class="text-xs text-gray-500">Dari ternak Anda</p>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <label class="relative flex items-center p-4 border-2 rounded-xl cursor-pointer transition"
                            :class="jantan_type === 'external' ? 'border-green-600 bg-green-50' : 'border-gray-200 hover:border-green-300'">
                            <input type="radio" name="jantan_type" value="external" x-model="jantan_type" class="sr-only">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <div
                                        class="bg-purple-100 text-purple-600 h-10 w-10 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">Pinjam/Sewa</p>
                                        <p class="text-xs text-gray-500">Dari peternak lain</p>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <label class="relative flex items-center p-4 border-2 rounded-xl cursor-pointer transition"
                            :class="jantan_type === 'semen' ? 'border-green-600 bg-green-50' : 'border-gray-200 hover:border-green-300'">
                            <input type="radio" name="jantan_type" value="semen" x-model="jantan_type"
                                @click="metode_perkawinan = 'ib'" class="sr-only">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <div
                                        class="bg-pink-100 text-pink-600 h-10 w-10 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">Sperma Beku</p>
                                        <p class="text-xs text-gray-500">Untuk IB</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>

                    <!-- Dynamic Fields Based on Jantan Type -->
                    <div x-show="jantan_type === 'owned'" x-transition
                        class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih Pejantan <span class="text-red-500">*</span>
                        </label>
                        <select name="jantan_id" x-model="jantan_id" :required="jantan_type === 'owned'"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">-- Pilih Pejantan --</option>
                            @foreach($jantanList as $jantan)
                                <option value="{{ $jantan->id }}" data-jenis="{{ $jantan->jenis_hewan }}">
                                    {{ $jantan->kode_hewan }} - {{ $jantan->nama_hewan }} ({{ ucfirst($jantan->jenis_hewan) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div x-show="jantan_type === 'external'" x-transition
                        class="p-4 bg-purple-50 rounded-xl border border-purple-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Pejantan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="jantan_external_name" x-model="jantan_external_name"
                                    :required="jantan_type === 'external'" placeholder="Contoh: Rocky"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Ras Platform <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="jantan_external_breed" list="breed-list-external"
                                    x-model="jantan_external_breed" :required="jantan_type === 'external'"
                                    placeholder="Ketik atau pilih ras..."
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <datalist id="breed-list-external">
                                    <option value="Simental">Simental (Sapi)</option>
                                    <option value="Limousin">Limousin (Sapi)</option>
                                    <option value="Brahman">Brahman ​(Sapi)</option>
                                    <option value="PO">PO - Peranakan Ongole (Sapi)</option>
                                    <option value="Bali">Bali (Sapi)</option>
                                    <option value="Madura">Madura (Sapi)</option>
                                    <option value="Angus">Angus (Sapi)</option>
                                    <option value="Hereford">Hereford (Sapi)</option>
                                    <option value="Holstein">Holstein/Friesian (Sapi Perah)</option>
                                    <option value="Etawa">Etawa (Kambing)</option>
                                    <option value="Jawarandu">Jawarandu (Kambing)</option>
                                    <option value="Kacang">Kacang (Kambing)</option>
                                    <option value="Boer">Boer (Kambing)</option>
                                    <option value="Saanen">Saanen (Kambing)</option>
                                    <option value="PE">PE - Peranakan Etawa (Kambing)</option>
                                    <option value="Gembrong">Gembrong (Kambing)</option>
                                    <option value="Merino">Merino (Domba)</option>
                                    <option value="Dorper">Dorper (Domba)</option>
                                    <option value="Garut">Garut (Domba)</option>
                                    <option value="Ekor Tipis">Ekor Tipis (Domba)</option>
                                </datalist>
                                <p class="text-xs text-gray-500 mt-1">💡 Ketik untuk melihat saran atau input bebas</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Pemilik <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="jantan_external_owner" :required="jantan_type === 'external'"
                                    placeholder="Contoh: Pak Joko - Peternakan Maju"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    No. Registrasi (Opsional)
                                </label>
                                <input type="text" name="jantan_external_reg_number" placeholder="Contoh: LIM-2024-001"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>

                    <div x-show="jantan_type === 'semen'" x-transition
                        class="p-4 bg-pink-50 rounded-xl border border-pink-200">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Kode Semen/Straw <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="semen_code" x-model="semen_code"
                                    :required="jantan_type === 'semen'" placeholder="Contoh: SIM-2024-123"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Produsen <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="semen_producer" list="producer-list" x-model="semen_producer"
                                    :required="jantan_type === 'semen'" placeholder="Contoh: BBIB Singosari"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <datalist id="producer-list">
                                    <option value="BBIB Singosari">
                                    <option value="BBIB Lembang">
                                    <option value="BIB Tuah Sakato">
                                    <option value="ABS Global">
                                    <option value="Semex">
                                    <option value="CRI">
                                </datalist>
                                <p class="text-xs text-gray-500 mt-1">💡 Ketik atau pilih dari saran</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Breed/Ras <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="semen_breed" list="breed-list-semen" x-model="semen_breed"
                                    :required="jantan_type === 'semen'" placeholder="Ketik atau pilih ras..."
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <datalist id="breed-list-semen">
                                    <option value="Simental">Simental (Sapi Potong)</option>
                                    <option value="Limousin">Limousin (Sapi Potong)</option>
                                    <option value="Brahman">Brahman (Sapi Potong)</option>
                                    <option value="Angus">Angus (Sapi Potong)</option>
                                    <option value="Hereford">Hereford (Sapi Potong)</option>
                                    <option value="Charolais">Charolais (Sapi Potong)</option>
                                    <option value="Holstein">Holstein/Friesian (Sapi Perah)</option>
                                    <option value="Jersey">Jersey (Sapi Perah)</option>
                                    <option value="Brown Swiss">Brown Swiss (Sapi Perah)</option>
                                    <option value="Boer">Boer (Kambing)</option>
                                    <option value="Saanen">Saanen (Kambing)</option>
                                    <option value="Etawa">Etawa (Kambing)</option>
                                    <option value="Jawarandu">Jawarandu (Kambing)</option>
                                    <option value="Dorper">Dorper (Domba)</option>
                                    <option value="Merino">Merino (Domba)</option>
                                    <option value="Suffolk">Suffolk (Domba)</option>
                                </datalist>
                                <p class="text-xs text-gray-500 mt-1">💡 Ketik untuk melihat saran atau input bebas</p>
                            </div>
                        </div>

                        <div class="mt-3 p-3 bg-blue-100 rounded-lg text-sm text-blue-800">
                            <strong>💡 Info:</strong> Metode perkawinan otomatis menjadi "Inseminasi Buatan (IB)"
                        </div>
                    </div>
                </div>

                <!-- STEP 2: Pilih Betina -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">STEP 2: Pilih Betina (Induk)</h3>
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih Betina <span class="text-red-500">*</span>
                        </label>
                        <select name="betina_id" x-model="betina_id" @change="updateEstimations()" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">-- Pilih Betina --</option>
                            @foreach($betinaList as $betina)
                                <option value="{{ $betina->id }}" data-jenis="{{ $betina->jenis_hewan }}">
                                    {{ $betina->kode_hewan }} - {{ $betina->nama_hewan }} ({{ ucfirst($betina->jenis_hewan) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- STEP 3: Tanggal & Metode -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">STEP 3: Tanggal & Metode Perkawinan</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Birahi (Opsional)
                            </label>
                            <input type="date" name="tanggal_birahi" x-model="tanggal_birahi"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <p class="text-xs text-gray-500 mt-1">Tanggal pertama kali terdeteksi birahi</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Kawin/IB <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_perkawinan" x-model="tanggal_perkawinan"
                                @change="updateEstimations()" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                    </div>

                    <!-- Metode Perkawinan -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Metode Perkawinan <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            <label class="relative flex items-center p-3 border-2 rounded-xl cursor-pointer transition"
                                :class="metode_perkawinan === 'alami' ? 'border-green-600 bg-green-50' : 'border-gray-200'">
                                <input type="radio" name="metode_perkawinan" value="alami" x-model="metode_perkawinan"
                                    :disabled="jantan_type === 'semen'" class="sr-only">
                                <span class="text-sm font-medium">Kawin Alami</span>
                            </label>
                            <label class="relative flex items-center p-3 border-2 rounded-xl cursor-pointer transition"
                                :class="metode_perkawinan === 'ib' ? 'border-green-600 bg-green-50' : 'border-gray-200'">
                                <input type="radio" name="metode_perkawinan" value="ib" x-model="metode_perkawinan"
                                    class="sr-only">
                                <span class="text-sm font-medium">Inseminasi Buatan (IB)</span>
                            </label>
                            <label class="relative flex items-center p-3 border-2 rounded-xl cursor-pointer transition"
                                :class="metode_perkawinan === 'et' ? 'border-green-600 bg-green-50' : 'border-gray-200'">
                                <input type="radio" name="metode_perkawinan" value="et" x-model="metode_perkawinan"
                                    :disabled="jantan_type === 'semen'" class="sr-only">
                                <span class="text-sm font-medium">Embryo Transfer (ET)</span>
                            </label>
                        </div>
                    </div>

                    <!-- IB Additional Fields -->
                    <div x-show="metode_perkawinan === 'ib'" x-transition
                        class="p-4 bg-yellow-50 rounded-xl border border-yellow-200">
                        <h4 class="font-medium text-gray-900 mb-3">Informasi Tambahan IB</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Inseminator
                                </label>
                                <input type="text" name="inseminator_name" placeholder="Contoh: Dr. Budi"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Waktu IB
                                </label>
                                <select name="ib_time"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    <option value="">-- Pilih Waktu --</option>
                                    <option value="pagi">Pagi (06:00 - 10:00)</option>
                                    <option value="siang">Siang (10:00 - 15:00)</option>
                                    <option value="sore">Sore (15:00 - 18:00)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Jumlah Straw
                                </label>
                                <input type="number" name="straw_count" min="1" value="1" placeholder="1"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estimasi Preview -->
                <div x-show="showEstimation" x-transition class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                    <h4 class="text-sm font-semibold text-blue-900 mb-3">📅 Estimasi Otomatis</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-600">Estimasi Kelahiran:</p>
                            <p class="font-semibold text-gray-900" x-text="estimatedBirth"></p>
                        </div>
                        <div>
                            <p class="text-gray-600">Cek Birahi Berikutnya:</p>
                            <p class="font-semibold text-gray-900" x-text="nextHeatCheck"></p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">* Estimasi dihitung otomatis berdasarkan jenis hewan dan tanggal
                        kawin</p>
                </div>

                <!-- Catatan -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan Tambahan
                    </label>
                    <textarea name="catatan" rows="3" placeholder="Tambahkan catatan tambahan (opsional)..."
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none"></textarea>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 justify-end">
                    <a href="{{ route('reproduksi.index') }}"
                        class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-medium transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-xl font-medium transition">
                        Simpan Catatan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function reproductionForm() {
                // Get URL parameters
                const urlParams = new URLSearchParams(window.location.search);
                const betinaIdParam = urlParams.get('betina_id');
                const tanggalBirahiParam = urlParams.get('tanggal_birahi');

                return {
                    jantan_type: 'owned',
                    jantan_id: '',
                    jantan_external_name: '',
                    jantan_external_breed: '',
                    jantan_external_breed_custom: '',
                    jantan_external_breed_custom_show: false,
                    semen_code: '',
                    semen_producer: '',
                    semen_producer_custom: '',
                    semen_producer_custom_show: false,
                    semen_breed: '',
                    semen_breed_custom: '',
                    semen_breed_custom_show: false,
                    betina_id: betinaIdParam || '',
                    tanggal_birahi: tanggalBirahiParam || '',
                    tanggal_perkawinan: '',
                    metode_perkawinan: 'alami',
                    showEstimation: false,
                    estimatedBirth: '',
                    nextHeatCheck: '',

                    updateEstimations() {
                        if (!this.betina_id || !this.tanggal_perkawinan) {
                            this.showEstimation = false;
                            return;
                        }

                        // Get betina's jenis_hewan
                        const betinaSelect = document.querySelector('select[name="betina_id"]');
                        const selectedOption = betinaSelect.options[betinaSelect.selectedIndex];
                        const jenisHewan = selectedOption.getAttribute('data-jenis');

                        // Calculate gestation period
                        const gestationDays = {
                            'sapi': 283,
                            'kambing': 150,
                            'domba': 147
                        };
                        const heatCycleDays = 21;

                        const matingDate = new Date(this.tanggal_perkawinan);
                        const birthDate = new Date(matingDate);
                        birthDate.setDate(birthDate.getDate() + gestationDays[jenisHewan]);

                        const heatDate = new Date(matingDate);
                        heatDate.setDate(heatDate.getDate() + heatCycleDays);

                        // Format dates
                        const formatDate = (date) => {
                            const options = { year: 'numeric', month: 'long', day: 'numeric' };
                            return date.toLocaleDateString('id-ID', options);
                        };

                        this.estimatedBirth = formatDate(birthDate);
                        this.nextHeatCheck = formatDate(heatDate);
                        this.showEstimation = true;
                    }
                }
            }
        </script>
    @endpush
@endsection