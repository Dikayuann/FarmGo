@extends('layouts.app')

@section('title', 'Catat Deteksi Birahi')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-900">Catat Deteksi Birahi (Estrus)</h2>
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

            <form action="{{ route('heat-detection.store') }}" method="POST" x-data="heatDetectionForm()">
                @csrf

                <!-- Pilih Betina -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Ternak Betina <span class="text-red-500">*</span>
                    </label>
                    <select name="animal_id" x-model="animal_id" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">-- Pilih Betina --</option>
                        @foreach($betinas as $betina)
                            <option value="{{ $betina->id }}">
                                {{ $betina->kode_hewan }} - {{ $betina->nama_hewan }} ({{ ucfirst($betina->jenis_hewan) }})
                            </option>
                        @endforeach
                    </select>
                    @if($betinas->count() == 0)
                        <p class="text-sm text-yellow-600 mt-2">Belum ada ternak betina terdaftar</p>
                    @endif
                </div>

                <!-- Tanggal Deteksi -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Deteksi Birahi <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_deteksi" required value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Tanggal pertama kali ternak menunjukkan tanda-tanda birahi</p>
                </div>

                <!-- Gejala Birahi -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Tanda-Tanda Birahi yang Terdeteksi
                    </label>
                    <p class="text-xs text-gray-500 mb-3">Pilih gejala yang terlihat (bisa lebih dari satu)</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <label
                            class="flex items-start p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                            <input type="checkbox" name="gejala[]" value="gelisah"
                                class="w-4 h-4 text-green-600 focus:ring-green-500 rounded mt-0.5">
                            <div class="ml-3">
                                <span class="text-sm font-medium text-gray-700">Gelisah dan Resah</span>
                                <p class="text-xs text-gray-500">Ternak terlihat lebih aktif dari biasanya</p>
                            </div>
                        </label>

                        <label
                            class="flex items-start p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                            <input type="checkbox" name="gejala[]" value="mengembik"
                                class="w-4 h-4 text-green-600 focus:ring-green-500 rounded mt-0.5">
                            <div class="ml-3">
                                <span class="text-sm font-medium text-gray-700">Melenguh/Mengembik Berulang</span>
                                <p class="text-xs text-gray-500">Mengeluarkan suara lebih sering</p>
                            </div>
                        </label>

                        <label
                            class="flex items-start p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                            <input type="checkbox" name="gejala[]" value="nafsu_makan_turun"
                                class="w-4 h-4 text-green-600 focus:ring-green-500 rounded mt-0.5">
                            <div class="ml-3">
                                <span class="text-sm font-medium text-gray-700">Nafsu Makan Menurun</span>
                                <p class="text-xs text-gray-500">Konsumsi pakan berkurang</p>
                            </div>
                        </label>

                        <label
                            class="flex items-start p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                            <input type="checkbox" name="gejala[]" value="ekor_terangkat"
                                class="w-4 h-4 text-green-600 focus:ring-green-500 rounded mt-0.5">
                            <div class="ml-3">
                                <span class="text-sm font-medium text-gray-700">Ekor Terangkat (Tail Raising)</span>
                                <p class="text-xs text-gray-500">Posisi ekor lebih tinggi dari normal</p>
                            </div>
                        </label>

                        <label
                            class="flex items-start p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                            <input type="checkbox" name="gejala[]" value="lendir_bening"
                                class="w-4 h-4 text-green-600 focus:ring-green-500 rounded mt-0.5">
                            <div class="ml-3">
                                <span class="text-sm font-medium text-gray-700">Lendir Bening dari Vulva</span>
                                <p class="text-xs text-gray-500">Discharge mukus jernih</p>
                            </div>
                        </label>

                        <label
                            class="flex items-start p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                            <input type="checkbox" name="gejala[]" value="mounting"
                                class="w-4 h-4 text-green-600 focus:ring-green-500 rounded mt-0.5">
                            <div class="ml-3">
                                <span class="text-sm font-medium text-gray-700">Mounting Behavior</span>
                                <p class="text-xs text-gray-500">Menunggang atau ditunggangi ternak lain</p>
                            </div>
                        </label>

                        <label
                            class="flex items-start p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                            <input type="checkbox" name="gejala[]" value="vulva_membengkak"
                                class="w-4 h-4 text-green-600 focus:ring-green-500 rounded mt-0.5">
                            <div class="ml-3">
                                <span class="text-sm font-medium text-gray-700">Vulva Membengkak dan Kemerahan</span>
                                <p class="text-xs text-gray-500">Pembengkakan pada area reproduksi</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Catatan -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan Tambahan
                    </label>
                    <textarea name="catatan" rows="3"
                        placeholder="Contoh: Ternak sangat aktif pagi ini, terus mengikuti pejantan..."
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none"></textarea>
                </div>

                <!-- Action Selection -->
                <div class="mb-6 p-4 bg-blue-50 rounded-xl border border-blue-200">
                    <h4 class="text-sm font-semibold text-blue-900 mb-3">Tindak Lanjut</h4>
                    <div class="space-y-3">
                        <label class="flex items-center p-3 bg-white border-2 rounded-lg cursor-pointer transition"
                            :class="action === 'breed_now' ? 'border-green-600 bg-green-50' : 'border-gray-200'">
                            <input type="radio" name="action" value="breed_now" x-model="action"
                                class="w-4 h-4 text-green-600 focus:ring-green-500">
                            <div class="ml-3">
                                <span class="text-sm font-medium text-gray-900">Lanjut Kawin Sekarang</span>
                                <p class="text-xs text-gray-600">Langsung proses ke pencatatan perkawinan</p>
                            </div>
                        </label>

                        <label class="flex items-center p-3 bg-white border-2 rounded-lg cursor-pointer transition"
                            :class="action === 'save_only' ? 'border-green-600 bg-green-50' : 'border-gray-200'">
                            <input type="radio" name="action" value="save_only" x-model="action"
                                class="w-4 h-4 text-green-600 focus:ring-green-500">
                            <div class="ml-3">
                                <span class="text-sm font-medium text-gray-900">Simpan Catatan Saja</span>
                                <p class="text-xs text-gray-600">Catat deteks birahi, kawin dilakukan nanti</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 justify-end">
                    <a href="{{ route('reproduksi.index') }}"
                        class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-medium transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-xl font-medium transition">
                        <span x-show="action === 'breed_now'">Lanjut Kawin</span>
                        <span x-show="action === 'save_only'">Simpan Catatan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function heatDetectionForm() {
                return {
                    animal_id: '',
                    action: 'breed_now', // Default to breed now
                }
            }
        </script>
    @endpush
@endsection