@extends('layouts.app')

@section('title', 'Edit Catatan Reproduksi')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-900">Edit Catatan Reproduksi</h2>
            <a href="{{ route('reproduksi.show', $perkawinan->id) }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
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

        <!-- Display Parents (Read-only) -->
        <div class="mb-6 p-4 bg-gray-50 rounded-xl">
            <h3 class="font-medium text-gray-900 mb-3">Hewan yang Dikawinkan</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-600">Jantan:</p>
                    @if($perkawinan->jantan_type === 'owned' && $perkawinan->jantan)
                        <p class="font-semibold">{{ $perkawinan->jantan->nama_hewan }}</p>
                        <p class="text-xs text-gray-500">{{ $perkawinan->jantan->kode_hewan }}</p>
                    @elseif($perkawinan->jantan_type === 'external')
                        <p class="font-semibold">{{ $perkawinan->jantan_external_name ?? 'External' }}</p>
                        <p class="text-xs text-gray-500">{{ $perkawinan->jantan_external_breed ?? '-' }}</p>
                    @elseif($perkawinan->jantan_type === 'semen')
                        <p class="font-semibold">Sperma: {{ $perkawinan->semen_code ?? '-' }}</p>
                        <p class="text-xs text-gray-500">{{ $perkawinan->semen_breed ?? '-' }}</p>
                    @endif
                </div>
                <div>
                    <p class="text-gray-600">Betina:</p>
                    <p class="font-semibold">{{ $perkawinan->betina->nama_hewan }}</p>
                    <p class="text-xs text-gray-500">{{ $perkawinan->betina->kode_hewan }}</p>
                </div>
            </div>
        </div>

        <form action="{{ route('reproduksi.update', $perkawinan->id) }}" method="POST" x-data="editForm({{ $perkawinan->status_reproduksi == 'melahirkan' ? 'true' : 'false' }})">
            @csrf
            @method('PUT')

            <!-- Status Reproduksi -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Status Reproduksi <span class="text-red-500">*</span>
                </label>
                
                <div class="flex flex-wrap gap-3 mb-3">
                    @foreach(['menunggu', 'bunting', 'melahirkan', 'gagal'] as $status)
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input 
                                type="radio" 
                                name="status_reproduksi" 
                                value="{{ $status }}"
                                x-model="currentStatus"
                                {{ $perkawinan->status_reproduksi == $status ? 'checked' : '' }}
                                class="w-4 h-4 text-green-600 focus:ring-green-500">
                            <span class="text-sm font-medium text-gray-700">{{ ucfirst($status) }}</span>
                        </label>
                    @endforeach
                </div>

                <!-- Status Flow Indicator -->
                <div class="p-3 bg-blue-50 rounded-lg text-xs text-gray-600">
                    <strong>Alur Status:</strong> Menunggu → Bunting → Melahirkan/Gagal
                </div>
            </div>

            <!-- Tanggal Melahirkan (conditional) -->
            <div x-show="currentStatus === 'melahirkan'" class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Tanggal Melahirkan <span class="text-red-500">*</span>
                </label>
                <input 
                    type="date" 
                    name="tanggal_melahirkan"
                    value="{{ $perkawinan->tanggal_melahirkan ? $perkawinan->tanggal_melahirkan->format('Y-m-d') : '' }}"
                    :required="currentStatus === 'melahirkan'"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>

            <!-- Jumlah Anak (conditional) -->
            <div x-show="currentStatus === 'melahirkan'" class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Jumlah Anak <span class="text-red-500">*</span>
                </label>
                <input 
                    type="number" 
                    name="jumlah_anak"
                    value="{{ $perkawinan->jumlah_anak ?? '' }}"
                    min="0"
                    :required="currentStatus === 'melahirkan'"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                <p class="text-xs text-gray-500 mt-1">Masukkan 0 jika tidak ada anak yang bertahan hidup</p>
            </div>

            <!-- Catatan -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Catatan
                </label>
                <textarea 
                    name="catatan" 
                    rows="4"
                    placeholder="Tambahkan catatan tambahan (opsional)..."
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none">{{ old('catatan', $perkawinan->catatan) }}</textarea>
            </div>

            <!-- Info Box -->
            <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-xl text-sm text-yellow-800">
                <strong>Catatan:</strong> Jika status diubah menjadi "Melahirkan" atau "Gagal", pengingat otomatis akan dinonaktifkan.
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 justify-end">
                <a href="{{ route('reproduksi.show', $perkawinan->id) }}" 
                   class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-medium transition">
                    Batal
                </a>
                <button 
                    type="submit"
                    class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-xl font-medium transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function editForm(initialMelahirkan) {
    return {
        currentStatus: initialMelahirkan ? 'melahirkan' : '{{ $perkawinan->status_reproduksi }}',
    }
}
</script>
@endpush
@endsection
