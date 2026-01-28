<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - FarmGo</title>
    @vite(['resources/css/app.css'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Card Container -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <!-- Header with Logo and Gradient -->
            <div class="bg-gradient-to-br from-emerald-400 via-emerald-500 to-teal-600 p-8 text-center">
                <div class="flex justify-center mb-4">
                    <div class="bg-white rounded-full p-3">
                        <img src="{{ asset('image/FarmGo.png') }}" alt="FarmGo" class="h-12 w-12">
                    </div>
                </div>
                <h1 class="text-2xl font-bold text-white mb-1">Verifikasi Email Anda</h1>
                <p class="text-emerald-50 text-sm">Satu langkah lagi untuk memulai</p>
            </div>

            <!-- Content -->
            <div class="p-8" x-data="{
                sent: false,
                loading: false,
                error: '',
                email: '{{ session('email') ?? old('email') ?? '' }}',
                
                async resendEmail() {
                    if (this.loading) return;
                    
                    this.loading = true;
                    this.error = '';
                    
                    try {
                        const response = await fetch('{{ route('verification.resend') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ email: this.email })
                        });
                        
                        const data = await response.json();
                        
                        if (response.ok && data.success) {
                            this.sent = true;
                            setTimeout(() => this.sent = false, 8000);
                        } else {
                            // Show error message from backend (rate limiting, etc)
                            this.error = data.message || 'Terjadi kesalahan. Silakan coba lagi.';
                            setTimeout(() => this.error = '', 8000);
                        }
                    } catch (error) {
                        console.error('Resend failed:', error);
                        this.error = 'Terjadi kesalahan. Silakan coba lagi.';
                        setTimeout(() => this.error = '', 8000);
                    } finally {
                        this.loading = false;
                    }
                },
            }">
                <!-- Initial Email Sent Info -->
                <div class="mb-6 p-4 rounded-xl bg-gradient-to-r from-blue-50 to-cyan-50 border-2 border-blue-200">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-bold text-blue-900">Email Verifikasi Telah Dikirim</p>
                            <p class="text-xs text-blue-700 mt-1">
                                Kami telah mengirim link verifikasi ke <span class="font-semibold"
                                    x-text="email"></span>
                            </p>
                            <p class="text-xs text-blue-600 mt-1">
                                Silakan cek inbox atau folder spam Anda
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Success Notification (for resend) -->
                <div x-show="sent" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="mb-6 p-4 rounded-xl bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-emerald-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-bold text-emerald-900">Email Berhasil Dikirim Ulang!</p>
                            <p class="text-xs text-emerald-700 mt-0.5">Silakan cek inbox atau folder spam Anda</p>
                        </div>
                    </div>
                </div>

                <!-- Main Icon -->
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-emerald-50 rounded-full mb-4">
                        <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>

                    <h2 class="text-xl font-bold text-gray-900 mb-2">Cek Email Anda</h2>
                    <p class="text-gray-600 text-sm mb-2">
                        Kami telah mengirim link verifikasi ke:
                    </p>
                    <p class="text-emerald-600 font-semibold text-base" x-text="email"></p>
                </div>

                <!-- Instructions -->
                <div class="bg-gray-50 rounded-xl p-5 mb-6">
                    <p class="text-sm text-gray-700 mb-3 font-semibold">Langkah selanjutnya:</p>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <div
                                class="flex-shrink-0 w-6 h-6 bg-emerald-500 rounded-full flex items-center justify-center text-white text-xs font-bold mr-3 mt-0.5">
                                1</div>
                            <p class="text-sm text-gray-600">Buka inbox email Anda (cek juga folder spam)</p>
                        </div>
                        <div class="flex items-start">
                            <div
                                class="flex-shrink-0 w-6 h-6 bg-emerald-500 rounded-full flex items-center justify-center text-white text-xs font-bold mr-3 mt-0.5">
                                2</div>
                            <p class="text-sm text-gray-600">Klik tombol "Verifikasi Email Saya"</p>
                        </div>
                        <div class="flex items-start">
                            <div
                                class="flex-shrink-0 w-6 h-6 bg-emerald-500 rounded-full flex items-center justify-center text-white text-xs font-bold mr-3 mt-0.5">
                                3</div>
                            <p class="text-sm text-gray-600">Login untuk mengakses dashboard</p>
                        </div>
                    </div>
                </div>

                <!-- Resend Section -->
                <div class="text-center mb-6">
                    <p class="text-sm text-gray-600 mb-3">Tidak menerima email?</p>

                    <!-- Error Notification (shown when rate limited or error) -->
                    <div x-show="error" x-cloak x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        class="mb-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center justify-center text-red-800">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm font-medium" x-text="error"></span>
                        </div>
                    </div>

                    <button @click="resendEmail()" :disabled="loading"
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 px-4 rounded-xl transition duration-300 flex items-center justify-center shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg x-show="loading" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <span x-show="!loading">Kirim Ulang Email</span>
                        <span x-show="loading">Mengirim...</span>
                    </button>

                    <!-- Help text -->
                    <p class="text-xs text-gray-500 mt-2">
                        Cek folder spam jika email tidak masuk ke inbox
                    </p>
                </div>

                <!-- Logout Link -->
                <div class="text-center pt-4 border-t border-gray-200">
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-gray-600 hover:text-gray-900 font-medium transition">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Help Text -->
        <p class="text-center text-sm text-gray-700 mt-6">
            Butuh bantuan? <a href="mailto:support@farmgo.com"
                class="text-emerald-600 hover:text-emerald-700 font-semibold">Hubungi Support</a>
        </p>
    </div>
</body>

</html>