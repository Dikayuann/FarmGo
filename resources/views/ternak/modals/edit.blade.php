{{-- Edit Modal --}}
<div id="editModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog"
    aria-modal="true">

    {{-- Background overlay --}}
    <div onclick="closeEditModal()" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"></div>

    {{-- Modal container --}}
    <div class="flex min-h-full items-center justify-center p-4">
        {{-- Modal content --}}
        <div onclick="event.stopPropagation()"
            class="relative bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden">

            <form id="editAnimalForm" action="" method="POST">
                @csrf
                @method('PUT')

                {{-- Modal Header --}}
                <div class="bg-emerald-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white">Edit Data Ternak</h3>
                        <button type="button" onclick="closeEditModal()"
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
                            <input type="text" id="edit_kode_hewan" readonly
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-600">
                        </div>

                        {{-- Nama Hewan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Nama Hewan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_hewan" id="edit_nama_hewan" required
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        {{-- Jenis Hewan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Hewan</label>
                            <input type="text" id="edit_jenis_hewan_display" readonly
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-600">
                            <input type="hidden" name="jenis_hewan" id="edit_jenis_hewan">
                        </div>

                        {{-- Ras Hewan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Ras Hewan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="ras_hewan" id="edit_ras_hewan" required
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Jenis Kelamin <span class="text-red-500">*</span>
                            </label>
                            <select name="jenis_kelamin" id="edit_jenis_kelamin" required
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="jantan">Jantan</option>
                                <option value="betina">Betina</option>
                            </select>
                        </div>

                        {{-- Tanggal Lahir --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Tanggal Lahir <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_lahir" id="edit_tanggal_lahir" required
                                max="{{ date('Y-m-d') }}"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        {{-- Berat Badan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Berat Badan (kg) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="berat_badan" id="edit_berat_badan" required step="0.01" min="0"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        {{-- Status Ternak --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Status Ternak <span class="text-red-500">*</span>
                            </label>
                            <select name="status_ternak" id="edit_status_ternak" required
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="beli">Beli</option>
                                <option value="perkawinan">Perkawinan</option>
                                <option value="hadiah">Hadiah</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="bg-gray-50 px-6 py-3 flex justify-end gap-2 border-t">
                    <button type="button" onclick="closeEditModal()"
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

<script>
    function openEditModal(animal) {
        // Set form action URL
        document.getElementById('editAnimalForm').action = '/ternak/' + animal.id;

        // Fill form fields
        document.getElementById('edit_kode_hewan').value = animal.kode_hewan || '';
        document.getElementById('edit_nama_hewan').value = animal.nama_hewan || '';
        document.getElementById('edit_jenis_hewan').value = animal.jenis_hewan || '';
        document.getElementById('edit_jenis_hewan_display').value = animal.jenis_hewan ?
            animal.jenis_hewan.charAt(0).toUpperCase() + animal.jenis_hewan.slice(1) : '';
        document.getElementById('edit_ras_hewan').value = animal.ras_hewan || '';
        document.getElementById('edit_jenis_kelamin').value = animal.jenis_kelamin || 'jantan';
        document.getElementById('edit_berat_badan').value = animal.berat_badan || '';
        document.getElementById('edit_status_ternak').value = animal.status_ternak || 'beli';

        // Format date
        if (animal.tanggal_lahir) {
            const date = new Date(animal.tanggal_lahir);
            document.getElementById('edit_tanggal_lahir').value = date.toISOString().split('T')[0];
        }

        // Show modal
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>