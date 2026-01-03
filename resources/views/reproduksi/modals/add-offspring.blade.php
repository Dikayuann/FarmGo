@extends('layouts.app')

@section('title', 'Tambah Anak')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-900">Tambah Anak ke Manajemen Ternak</h2>
                <a href="{{ route('reproduksi.show', $perkawinan->id) }}" class="text-gray-400 hover:text-gray-600">
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

            <!-- Parent Information -->
            <div class="mb-6 p-4 bg-gradient-to-r from-blue-50 to-pink-50 rounded-xl border border-gray-200">
                <h3 class="font-medium text-gray-900 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                        </path>
                    </svg>
                    Informasi Induk
                </h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-600 mb-1">Jantan:</p>
                        @if($perkawinan->jantan_type === 'owned' && $perkawinan->jantan)
                            <p class="font-semibold text-gray-900">{{ $perkawinan->jantan->nama_hewan }}</p>
                            <p class="text-xs text-gray-500">{{ $perkawinan->jantan->kode_hewan }} • {{ $perkawinan->jantan->ras_hewan }}</p>
                        @elseif($perkawinan->jantan_type === 'external')
                            <p class="font-semibold text-gray-900">{{ $perkawinan->jantan_external_name ?? 'External' }}</p>
                            <p class="text-xs text-gray-500">External • {{ $perkawinan->jantan_external_breed ?? '-' }}</p>
                        @elseif($perkawinan->jantan_type === 'semen')
                            <p class="font-semibold text-gray-900">Sperma: {{ $perkawinan->semen_code ?? '-' }}</p>
                            <p class="text-xs text-gray-500">{{ $perkawinan->semen_producer ?? '-' }} • {{ $perkawinan->semen_breed ?? '-' }}</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-gray-600 mb-1">Betina:</p>
                        <p class="font-semibold text-gray-900">{{ $perkawinan->betina->nama_hewan }}</p>
                        <p class="text-xs text-gray-500">{{ $perkawinan->betina->kode_hewan }} •
                            {{ $perkawinan->betina->ras_hewan }}</p>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-200">
                    <p class="text-xs text-gray-600">
                        <span class="font-medium">Jenis:</span> {{ ucfirst($perkawinan->betina->jenis_hewan) }} •
                        <span class="font-medium">Ras:</span> {{ $perkawinan->betina->ras_hewan }} (akan diwariskan)
                    </p>
                </div>
            </div>

            <form action="{{ route('reproduksi.store-offspring', $perkawinan->id) }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Kode Hewan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kode Hewan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kode_hewan" value="{{ old('kode_hewan', $suggestedKode) }}" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent font-mono">
                        <p class="text-xs text-gray-500 mt-1">Kode otomatis tersedia, bisa diubah jika perlu</p>
                    </div>

                    <!-- Nama Hewan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Hewan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_hewan" value="{{ old('nama_hewan') }}" required
                            placeholder="Contoh: Bella, Max, Luna"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Jenis Kelamin -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-4">
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="radio" name="jenis_kelamin" value="jantan" required
                                    class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm font-medium text-gray-700">Jantan</span>
                            </label>
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="radio" name="jenis_kelamin" value="betina"
                                    class="w-4 h-4 text-pink-600 focus:ring-pink-500">
                                <span class="text-sm font-medium text-gray-700">Betina</span>
                            </label>
                        </div>
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Lahir <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_lahir"
                            value="{{ old('tanggal_lahir', $perkawinan->tanggal_melahirkan ? $perkawinan->tanggal_melahirkan->format('Y-m-d') : date('Y-m-d')) }}"
                            required 
                            min="{{ $perkawinan->tanggal_perkawinan->format('Y-m-d') }}"
                            max="{{ date('Y-m-d') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Tanggal lahir harus setelah tanggal perkawinan ({{ $perkawinan->tanggal_perkawinan->format('d M Y') }})</p>
                    </div>
                </div>

                <!-- Berat Badan -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Berat Badan (kg) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="berat_badan" value="{{ old('berat_badan') }}" step="0.01" min="0" required
                        placeholder="Contoh: 2.5"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Berat badan saat lahir</p>
                </div>

                <!-- Info Box -->
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl text-sm">
                    <div class="flex gap-2">
                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="text-blue-800">
                            <strong>Informasi:</strong>
                            <ul class="list-disc list-inside mt-1 space-y-1">
                                <li>Jenis hewan dan ras akan otomatis diwariskan dari induk betina</li>
                                <li>QR Code akan otomatis dibuat setelah penyimpanan</li>
                                <li>Anak akan otomatis terhubung dengan catatan perkawinan ini</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 justify-end">
                    <a href="{{ route('reproduksi.show', $perkawinan->id) }}"
                        class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-medium transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-xl font-medium transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah ke Manajemen Ternak
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection