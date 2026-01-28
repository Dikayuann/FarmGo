{{-- Profile Photo Card - BASE64 VERSION (NO FILE UPLOAD ISSUES) --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6" x-data="{
    uploading: false,
    
    async uploadAvatar(event) {
        const file = event.target.files[0];
        if (!file) return;
        
        // Validate file type
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!validTypes.includes(file.type)) {
            Swal.fire({
                icon: 'error',
                title: 'Format File Tidak Valid',
                text: 'Hanya file JPG, JPEG, PNG, atau GIF yang diperbolehkan.',
                confirmButtonColor: '#10b981'
            });
            event.target.value = '';
            return;
        }
        
        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            Swal.fire({
                icon: 'error',
                title: 'Ukuran File Terlalu Besar',
                text: 'Ukuran file maksimal 2MB.',
                confirmButtonColor: '#10b981'
            });
            event.target.value = '';
            return;
        }
        
        // Convert to base64 and upload
        this.uploading = true;
        const reader = new FileReader();
        
        reader.onload = async (e) => {
            try {
                // Use FormData instead of JSON for better handling
                const formData = new FormData();
                formData.append('avatar_base64', e.target.result);
                formData.append('filename', file.name);
                
                const response = await fetch('{{ route('settings.update-avatar') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                        // Don't set Content-Type - let browser set it with boundary
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Use toast instead of modal
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                    
                    Toast.fire({
                        icon: 'success',
                        title: 'Foto profil berhasil diperbarui!'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Upload gagal');
                }
            } catch (error) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4000
                });
                
                Toast.fire({
                    icon: 'error',
                    title: 'Upload Gagal',
                    text: error.message || 'Terjadi kesalahan saat upload'
                });
            } finally {
                this.uploading = false;
                event.target.value = '';
            }
        };
        
        reader.onerror = () => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Gagal membaca file',
                confirmButtonColor: '#ef4444'
            });
            this.uploading = false;
        };
        
        reader.readAsDataURL(file);
    }
}">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Foto Profil</h2>

    <div class="flex flex-col md:flex-row items-center gap-6">
        {{-- Current Avatar --}}
        <div class="shrink-0">
            @if (Auth::user()->avatar_url)
                <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}"
                    class="h-32 w-32 rounded-full object-cover shadow-lg border-4 border-white ring-2 ring-emerald-100">
            @else
                <div
                    class="h-32 w-32 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white font-bold text-3xl shadow-lg border-4 border-white ring-2 ring-emerald-100">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', Auth::user()->name)[1] ?? '', 0, 1)) }}
                </div>
            @endif
        </div>

        {{-- Upload Form - BASE64 METHOD --}}
        <div class="flex-1 w-full">
            <div class="space-y-4">
                <div>
                    <label for="avatar" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Foto Baru
                    </label>
                    <input type="file" id="avatar" accept="image/jpeg,image/jpg,image/png,image/gif"
                        @change="uploadAvatar($event)" :disabled="uploading" class="block w-full text-sm text-gray-600
                                   file:mr-4 file:py-2 file:px-4
                                   file:rounded-lg file:border-0
                                   file:text-sm file:font-semibold
                                   file:bg-emerald-50 file:text-emerald-700
                                   hover:file:bg-emerald-100 file:cursor-pointer
                                   cursor-pointer border border-gray-300 rounded-lg p-2
                                   disabled:opacity-50 disabled:cursor-not-allowed">
                    <p class="mt-2 text-xs text-gray-500">
                        <i class="fa-solid fa-info-circle mr-1"></i>
                        Format: JPG, JPEG, PNG, atau GIF. Maksimal 2MB. Upload otomatis setelah pilih file.
                    </p>
                </div>

                {{-- Loading Indicator --}}
                <div x-show="uploading" x-cloak class="flex items-center gap-2 text-emerald-600">
                    <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                    <span class="text-sm font-medium">Mengupload...</span>
                </div>

                {{-- Delete Button --}}
                @if (Auth::user()->avatar || Auth::user()->avatar_url)
                    <button type="button" @click="
                                Swal.fire({
                                    title: 'Hapus Foto Profil?',
                                    text: 'Foto akan dihapus dan diganti dengan inisial nama Anda.',
                                    icon: 'warning',
                                    iconColor: '#ef4444',
                                    showCancelButton: true,
                                    confirmButtonText: 'Ya, Hapus',
                                    cancelButtonText: 'Batal',
                                    confirmButtonColor: '#ef4444',
                                    cancelButtonColor: '#6b7280',
                                    reverseButtons: true,
                                    customClass: {
                                        popup: 'rounded-2xl',
                                        confirmButton: 'rounded-lg px-6 py-2.5 font-medium',
                                        cancelButton: 'rounded-lg px-6 py-2.5 font-medium'
                                    }
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Submit delete form with FormData
                                        const formData = new FormData();
                                        formData.append('_method', 'DELETE');
                                        
                                        fetch('{{ route('settings.delete-avatar') }}', {
                                            method: 'POST',
                                            headers: {
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                'Accept': 'application/json'
                                            },
                                            body: formData
                                        }).then(response => response.json())
                                        .then(data => {
                                            if (data.success) {
                                                const Toast = Swal.mixin({
                                                    toast: true,
                                                    position: 'top-end',
                                                    showConfirmButton: false,
                                                    timer: 2000,
                                                    timerProgressBar: true
                                                });
                                                
                                                Toast.fire({
                                                    icon: 'success',
                                                    title: 'Foto profil berhasil dihapus!'
                                                }).then(() => window.location.reload());
                                            }
                                        }).catch(error => {
                                            const Toast = Swal.mixin({
                                                toast: true,
                                                position: 'top-end',
                                                showConfirmButton: false,
                                                timer: 3000
                                            });
                                            
                                            Toast.fire({
                                                icon: 'error',
                                                title: 'Gagal menghapus foto'
                                            });
                                        });
                                    }
                                })
                            "
                        class="px-6 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition font-medium text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                        Hapus Foto
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>