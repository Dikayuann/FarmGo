@extends('layouts.app')

@section('title', 'Checkout - FarmGo')
@section('page-title', 'Checkout Pembayaran')

@section('content')

    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('langganan') }}"
            class="text-emerald-600 hover:text-emerald-700 font-semibold flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke Pilihan Paket
        </a>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">

        {{-- Order Summary --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Package Details Card --}}
            <div class="bg-white rounded-3xl shadow-lg p-8 border border-gray-200">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Detail Paket</h2>

                <div class="flex items-start justify-between mb-6 pb-6 border-b border-gray-200">
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $details['name'] }}</h3>
                        <p class="text-gray-600">
                            Durasi: {{ $details['duration'] }} {{ $details['duration_unit'] }}
                        </p>
                        @if(isset($details['savings']))
                            <p class="text-emerald-600 font-semibold mt-1">
                                💰 Hemat Rp {{ number_format($details['savings'], 0, ',', '.') }}
                            </p>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-bold text-gray-900">
                            @if($details['price'] == 0)
                                <span class="text-emerald-600">GRATIS</span>
                            @else
                                Rp {{ number_format($details['price'], 0, ',', '.') }}
                            @endif
                        </p>
                    </div>
                </div>

                <div class="space-y-3">
                    <p class="font-semibold text-gray-700 mb-3">Fitur yang didapat:</p>
                    @foreach($details['features'] as $feature)
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">{{ $feature }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            @if($details['package_type'] !== 'trial')
                {{-- Payment Information --}}
                <div class="bg-blue-50 rounded-3xl p-6 border border-blue-200">
                    <h3 class="font-bold text-blue-900 mb-3">ℹ️ Informasi Pembayaran</h3>
                    <ul class="space-y-2 text-sm text-blue-800">
                        <li class="flex items-start gap-2">
                            <span>•</span>
                            <span>Setelah klik tombol bayar, akan muncul halaman pembayaran Midtrans</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span>•</span>
                            <span>Pilih metode pembayaran yang Anda inginkan (Bank Transfer, E-Wallet, QRIS, dll)</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span>•</span>
                            <span>Ikuti instruksi pembayaran dan selesaikan transaksi Anda</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span>•</span>
                            <span>Langganan akan otomatis aktif setelah pembayaran berhasil</span>
                        </li>
                    </ul>
                </div>
            @endif
        </div>

        {{-- Payment Summary Sidebar --}}
        <div class="lg:col-span-1">
            <div class="bg-gradient-to-br from-emerald-600 to-green-700 rounded-3xl shadow-xl p-8 text-white sticky top-8">
                <h3 class="text-xl font-bold mb-6">Ringkasan Pembayaran</h3>

                <div class="space-y-4 mb-6">
                    <div class="flex justify-between pb-3 border-b border-white/30">
                        <span class="text-emerald-100">Paket</span>
                        <span class="font-semibold">{{ $details['name'] }}</span>
                    </div>

                    @if($details['package_type'] !== 'trial')
                        <div class="flex justify-between pb-3 border-b border-white/30">
                            <span class="text-emerald-100">Harga</span>
                            <span class="font-semibold">Rp {{ number_format($details['price'], 0, ',', '.') }}</span>
                        </div>

                        @if(isset($details['savings']))
                            <div class="flex justify-between pb-3 border-b border-white/30 text-yellow-300">
                                <span>Hemat</span>
                                <span class="font-semibold">- Rp {{ number_format($details['savings'], 0, ',', '.') }}</span>
                            </div>
                        @endif
                    @endif

                    <div class="flex justify-between pt-2">
                        <span class="text-xl font-bold">Total</span>
                        <span class="text-2xl font-bold">
                            @if($details['price'] == 0)
                                GRATIS
                            @else
                                Rp {{ number_format($details['price'], 0, ',', '.') }}
                            @endif
                        </span>
                    </div>
                </div>

                @if($details['package_type'] === 'trial')
                    <form action="{{ route('langganan.trial') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full bg-white text-emerald-700 hover:bg-emerald-50 font-bold py-4 rounded-xl transition-all duration-300 shadow-lg hover:shadow-2xl mb-4">
                            🚀 Aktifkan Trial Gratis
                        </button>
                    </form>
                @else
                    <button onclick="processPayment()"
                        class="w-full bg-white text-emerald-700 hover:bg-emerald-50 font-bold py-4 rounded-xl transition-all duration-300 shadow-lg hover:shadow-2xl mb-4">
                        💳 Lanjutkan Pembayaran
                    </button>
                @endif

                <p class="text-center text-sm text-emerald-100">
                    @if($details['package_type'] === 'trial')
                        Tidak perlu kartu kredit
                    @else
                        Pembayaran aman dengan Midtrans
                    @endif
                </p>

                {{-- Trust Badges --}}
                <div class="mt-6 pt-6 border-t border-white/30 space-y-2 text-sm text-emerald-100">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                        <span>Pembayaran Aman & Terenkripsi</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Garansi Uang Kembali</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        <span>Support 24/7</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script>
        function processPayment() {
            // Show loading
            Swal.fire({
                title: 'Memproses...',
                text: 'Membuat transaksi pembayaran',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Create payment
            fetch('{{ route('langganan.payment') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    package: '{{ $details['package_type'] }}'
                })
            })
                .then(response => response.json())
                .then(data => {
                    Swal.close();

                    if (data.success) {
                        // For trial activation
                        if (data.is_trial) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Trial Aktif!',
                                text: data.message,
                                confirmButtonColor: '#059669'
                            }).then(() => {
                                window.location.href = '{{ route('dashboard') }}';
                            });
                            return;
                        }

                        // Open Midtrans Snap
                        snap.pay(data.snap_token, {
                            onSuccess: function (result) {
                                console.log('Payment success:', result);

                                // Show loading while verifying
                                Swal.fire({
                                    title: 'Memverifikasi Pembayaran...',
                                    text: 'Mohon tunggu sebentar',
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    }
                                });

                                // Wait a bit then check status to ensure webhook has processed
                                setTimeout(() => {
                                    fetch(`/langganan/check-status/${data.order_id}`, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        }
                                    })
                                        .then(response => response.json())
                                        .then(statusData => {
                                            if (statusData.paid) {
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Pembayaran Berhasil!',
                                                    text: 'Terima kasih, langganan Anda telah aktif',
                                                    confirmButtonColor: '#059669'
                                                }).then(() => {
                                                    window.location.href = '{{ route('dashboard') }}';
                                                });
                                            } else {
                                                // Status not updated yet, redirect to pending page for auto-polling
                                                window.location.href = `/langganan/pending/${data.order_id}`;
                                            }
                                        })
                                        .catch(error => {
                                            console.error('Status check error:', error);
                                            // On error, redirect to pending page
                                            window.location.href = `/langganan/pending/${data.order_id}`;
                                        });
                                }, 2000); // Wait 2 seconds for webhook to process
                            },
                            onPending: function (result) {
                                console.log('Payment pending:', result);
                                // Redirect to pending payment page
                                window.location.href = `/langganan/pending/${data.order_id}`;
                            },
                            onError: function (result) {
                                console.log('Payment error:', result);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Pembayaran Gagal',
                                    text: 'Terjadi kesalahan, silakan coba lagi',
                                    confirmButtonColor: '#dc2626'
                                });
                            },
                            onClose: function () {
                                console.log('Snap popup closed');
                                // User closed the popup without completing payment
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Pembayaran Dibatalkan',
                                    text: 'Anda bisa melanjutkan pembayaran kapan saja',
                                    confirmButtonColor: '#059669'
                                }).then(() => {
                                    window.location.href = '{{ route('langganan') }}';
                                });
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: data.message || 'Terjadi kesalahan',
                            confirmButtonColor: '#dc2626'
                        });
                    }
                })
                .catch(error => {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal membuat transaksi: ' + error.message,
                        confirmButtonColor: '#dc2626'
                    });
                });
        }
    </script>
@endpush