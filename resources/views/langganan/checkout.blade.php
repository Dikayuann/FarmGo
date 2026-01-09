@extends('layouts.app')

@section('title', 'Checkout - FarmGo')
@section('page-title', 'Checkout Pembayaran')

@section('content')

    {{-- Back Button --}}
    <div class="mb-8">
        <a href="{{ route('langganan') }}"
            class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 font-medium transition-colors">
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
            <div class="bg-white rounded-3xl shadow-lg p-8 border border-gray-100">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">Detail Paket</h2>

                <div class="flex items-start justify-between mb-8 pb-8 border-b border-gray-200">
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">{{ $details['name'] }}</h3>
                        <p class="text-gray-600 text-lg">
                            Durasi: <span class="font-semibold">{{ $details['duration'] }}
                                {{ $details['duration_unit'] }}</span>
                        </p>
                        @if(isset($details['savings']))
                            <div
                                class="mt-3 inline-flex items-center gap-2 bg-gradient-to-r from-amber-100 to-orange-100 text-orange-700 px-4 py-2 rounded-lg font-semibold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                                Hemat Rp {{ number_format($details['savings'], 0, ',', '.') }}
                            </div>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500 mb-1">Total Pembayaran</p>
                        <p class="text-4xl font-bold text-gray-900">
                            @if($details['price'] == 0)
                                <span class="text-emerald-600">GRATIS</span>
                            @else
                                Rp {{ number_format($details['price'], 0, ',', '.') }}
                            @endif
                        </p>
                    </div>
                </div>

                <div class="space-y-4">
                    <p class="font-bold text-gray-900 text-lg mb-4">Fitur yang Anda Dapatkan:</p>
                    <div class="grid md:grid-cols-2 gap-4">
                        @foreach($details['features'] as $feature)
                            <div class="flex items-start gap-3 bg-gray-50 rounded-xl p-4">
                                <div
                                    class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-medium">{{ $feature }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            @if($details['package_type'] !== 'trial')
                {{-- Payment Information --}}
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-3xl p-8 border-2 border-blue-200">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-500 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-blue-900 text-lg mb-2">Informasi Pembayaran</h3>
                            <ul class="space-y-3 text-blue-800">
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                        </path>
                                    </svg>
                                    <span>Setelah klik tombol bayar, akan muncul halaman pembayaran Midtrans</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                        </path>
                                    </svg>
                                    <span>Pilih metode pembayaran (Bank Transfer, E-Wallet, QRIS, Kartu Kredit)</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                        </path>
                                    </svg>
                                    <span>Ikuti instruksi pembayaran dan selesaikan transaksi</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                        </path>
                                    </svg>
                                    <span>Langganan otomatis aktif setelah pembayaran berhasil</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Payment Summary Sidebar --}}
        <div class="lg:col-span-1">
            <div
                class="bg-gradient-to-br from-emerald-600 via-emerald-700 to-green-700 rounded-3xl shadow-2xl p-8 text-white sticky top-8">
                <h3 class="text-2xl font-bold mb-8">Ringkasan Pembayaran</h3>

                <div class="space-y-4 mb-8">
                    <div class="flex justify-between pb-4 border-b border-white/20">
                        <span class="text-emerald-100">Paket</span>
                        <span class="font-semibold text-right">{{ $details['name'] }}</span>
                    </div>

                    @if($details['package_type'] !== 'trial')
                        <div class="flex justify-between pb-4 border-b border-white/20">
                            <span class="text-emerald-100">Harga</span>
                            <span class="font-semibold">Rp {{ number_format($details['price'], 0, ',', '.') }}</span>
                        </div>

                        @if(isset($details['savings']))
                            <div class="flex justify-between pb-4 border-b border-white/20">
                                <span class="text-yellow-200">Diskon</span>
                                <span class="font-semibold text-yellow-200">- Rp
                                    {{ number_format($details['savings'], 0, ',', '.') }}</span>
                            </div>
                        @endif
                    @endif

                    <div class="flex justify-between pt-4">
                        <span class="text-xl font-bold">Total</span>
                        <span class="text-3xl font-bold">
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
                            class="w-full bg-white text-emerald-700 hover:bg-emerald-50 font-bold py-4 rounded-xl transition-all duration-300 shadow-lg hover:shadow-2xl mb-4 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Aktifkan Trial Gratis
                        </button>
                    </form>
                @else
                    <button onclick="processPayment()"
                        class="w-full bg-white text-emerald-700 hover:bg-emerald-50 font-bold py-4 rounded-xl transition-all duration-300 shadow-lg hover:shadow-2xl mb-4 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                            </path>
                        </svg>
                        Lanjutkan Pembayaran
                    </button>
                @endif

                <p class="text-center text-sm text-emerald-100 mb-6">
                    @if($details['package_type'] === 'trial')
                        Tidak perlu kartu kredit
                    @else
                        Pembayaran aman dengan Midtrans
                    @endif
                </p>

                {{-- Trust Badges --}}
                <div class="pt-6 border-t border-white/20 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                        </div>
                        <span class="text-sm">Pembayaran Aman & Terenkripsi</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-sm">Garansi Uang Kembali</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                        </div>
                        <span class="text-sm">Support 24/7</span>
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