@extends('layouts.app')

@section('title', 'Menunggu Pembayaran - FarmGo')
@section('page-title', 'Menunggu Pembayaran')

@section('content')

    <div class="max-w-4xl mx-auto">

        {{-- Order Header --}}
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl p-8 text-white mb-6 shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}</h1>
                    <p class="text-blue-100">Order ID: <span class="font-mono">{{ $transaction->order_id }}</span></p>
                </div>
                <button onclick="copyOrderId()"
                    class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span class="text-sm">Copy</span>
                </button>
            </div>

            {{-- Countdown Timer --}}
            @if($transaction->expired_at)
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-semibold">Selesaikan pembayaran dalam:</span>
                        </div>
                        <div id="countdown" class="text-2xl font-bold font-mono text-yellow-300">
                            --:--:--
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Status Badge --}}
        <div class="mb-6">
            <div
                class="inline-flex items-center gap-2 bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full border border-yellow-200">
                <div class="w-2 h-2 bg-yellow-500 rounded-full animate-pulse transition-transform"></div>
                <span class="font-semibold">
                    @if($transaction->status === 'pending')
                        Menunggu Pembayaran
                    @elseif($transaction->status === 'settlement')
                        Pembayaran Berhasil
                    @else
                        {{ ucfirst($transaction->status) }}
                    @endif
                </span>
            </div>
        </div>

        {{-- Payment Details Card --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden mb-6">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">Detail Pembayaran</h2>
            </div>

            <div class="p-6 space-y-4">
                {{-- Payment Method --}}
                @if($transaction->payment_type)
                    <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                        <span class="text-gray-600">Metode Pembayaran</span>
                        <div class="flex items-center gap-2">
                            @if($transaction->bank)
                                <img src="https://via.placeholder.com/32x32/3b82f6/ffffff?text={{ strtoupper($transaction->bank) }}"
                                    alt="{{ $transaction->bank }}" class="w-8 h-8 rounded">
                            @endif
                            <span class="font-semibold text-gray-900">
                                @if($transaction->payment_type === 'bank_transfer' && $transaction->bank)
                                    {{ strtoupper($transaction->bank) }} Virtual Account
                                @elseif($transaction->payment_type === 'gopay')
                                    GoPay
                                @elseif($transaction->payment_type === 'qris')
                                    QRIS
                                @elseif($transaction->payment_type === 'credit_card')
                                    Credit/Debit Card
                                @else
                                    {{ ucfirst(str_replace('_', ' ', $transaction->payment_type)) }}
                                @endif
                            </span>
                        </div>
                    </div>
                @endif

                {{-- Payment Code / VA Number --}}
                @if($transaction->payment_code)
                    <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                        <p class="text-sm text-gray-600 mb-2">
                            @if($transaction->payment_type === 'bank_transfer')
                                Nomor Virtual Account
                            @elseif($transaction->payment_type === 'qris')
                                QRIS Code
                            @else
                                Payment Code
                            @endif
                        </p>

                        @if($transaction->payment_type === 'qris' && isset($transaction->midtrans_response['actions']))
                            {{-- Display QR Code for QRIS --}}
                            @php
                                $qrCodeUrl = null;
                                foreach ($transaction->midtrans_response['actions'] as $action) {
                                    if ($action['name'] === 'generate-qr-code') {
                                        $qrCodeUrl = $action['url'];
                                        break;
                                    }
                                }
                            @endphp

                            @if($qrCodeUrl)
                                <div class="flex flex-col items-center gap-4">
                                    <div class="bg-white p-4 rounded-xl border-2 border-blue-200">
                                        <img src="{{ $qrCodeUrl }}" alt="QR Code" class="w-64 h-64">
                                    </div>
                                    <p class="text-sm text-gray-600 text-center">Scan QR code di atas dengan aplikasi e-wallet Anda</p>
                                </div>
                            @else
                                <div class="flex items-center justify-between">
                                    <span class="text-2xl font-bold font-mono text-blue-700">{{ $transaction->payment_code }}</span>
                                    <button onclick="copyPaymentCode()"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        Copy
                                    </button>
                                </div>
                            @endif
                        @else
                            {{-- Display VA Number or Payment Code --}}
                            <div class="flex items-center justify-between">
                                <span class="text-3xl font-bold font-mono text-blue-700">{{ $transaction->payment_code }}</span>
                                <button onclick="copyPaymentCode()"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    Copy
                                </button>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Bank Account Details for VA --}}
                @if($transaction->payment_type === 'bank_transfer' && $transaction->bank && isset($transaction->midtrans_response['va_numbers'][0]))
                    @php
                        $vaDetails = $transaction->midtrans_response['va_numbers'][0];
                    @endphp
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                        <p class="text-sm text-gray-600 mb-3">Detail Transfer</p>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Bank Tujuan</span>
                                <span class="font-semibold text-gray-900">{{ strtoupper($vaDetails['bank']) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Nomor VA</span>
                                <span class="font-semibold text-gray-900 font-mono">{{ $vaDetails['va_number'] }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Total Amount --}}
                <div class="flex items-center justify-between text-lg pt-2">
                    <span class="font-semibold text-gray-900">Total Pembayaran</span>
                    <span class="text-2xl font-bold text-gray-900">Rp
                        {{ number_format($transaction->gross_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Payment Instructions --}}
        @if($transaction->payment_type === 'bank_transfer' && $transaction->bank)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden mb-6">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Cara Pembayaran</h2>
                </div>

                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div
                                class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold shrink-0">
                                1</div>
                            <div>
                                <p class="font-semibold text-gray-900 mb-1">Buka aplikasi mobile banking
                                    {{ strtoupper($transaction->bank) }}
                                </p>
                                <p class="text-sm text-gray-600">Atau gunakan ATM {{ strtoupper($transaction->bank) }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div
                                class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold shrink-0">
                                2</div>
                            <div>
                                <p class="font-semibold text-gray-900 mb-1">Pilih menu Transfer / Bayar</p>
                                <p class="text-sm text-gray-600">Pilih Virtual Account atau transfer antar bank</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div
                                class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold shrink-0">
                                3</div>
                            <div>
                                <p class="font-semibold text-gray-900 mb-1">Masukkan nomor Virtual Account</p>
                                <p class="text-sm text-gray-600 font-mono">{{ $transaction->payment_code }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div
                                class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold shrink-0">
                                4</div>
                            <div>
                                <p class="font-semibold text-gray-900 mb-1">Konfirmasi jumlah pembayaran</p>
                                <p class="text-sm text-gray-600">Pastikan jumlahnya Rp
                                    {{ number_format($transaction->gross_amount, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div
                                class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold shrink-0">
                                5</div>
                            <div>
                                <p class="font-semibold text-gray-900 mb-1">Selesaikan pembayaran</p>
                                <p class="text-sm text-gray-600">Status akan otomatis terupdate setelah pembayaran berhasil</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Debug Panel (Development Only) --}}
        @if(config('app.debug'))
            <div class="bg-gray-900 text-white rounded-2xl p-6 mb-6 font-mono text-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-yellow-400">🐛 Debug Panel</h3>
                    <span id="debug-status" class="text-green-400">Polling Active</span>
                </div>

                <div class="space-y-2">
                    <div>
                        <span class="text-gray-400">Current Status:</span>
                        <span id="debug-current-status" class="text-yellow-300 font-bold">{{ $transaction->status }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400">Langganan ID:</span>
                        <span id="debug-langganan-id" class="text-yellow-300">{{ $transaction->langganan_id ?? 'null' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400">Poll Count:</span>
                        <span id="debug-poll-count" class="text-yellow-300">0</span>
                    </div>
                    <div>
                        <span class="text-gray-400">Last Response:</span>
                        <pre id="debug-response"
                            class="text-green-300 text-xs mt-2 p-2 bg-gray-800 rounded overflow-auto max-h-40">Waiting...</pre>
                    </div>
                </div>
            </div>
        @endif

        {{-- Action Buttons --}}
        <div class="flex gap-4">
            <button onclick="checkPaymentStatus()"
                class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-4 rounded-xl transition-colors shadow-lg flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                    </path>
                </svg>
                Cek Status Pembayaran
            </button>
            <a href="{{ route('langganan') }}"
                class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-4 rounded-xl transition-colors shadow-lg text-center">
                Kembali
            </a>
        </div>

        {{-- Help Section --}}
        <div class="mt-6 bg-gray-50 rounded-xl p-6 border border-gray-200">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="font-semibold text-gray-900 mb-2">Butuh Bantuan?</p>
                    <p class="text-sm text-gray-600">
                        Jika ada kendala dalam pembayaran, hubungi support kami di
                        <a href="mailto:support@farmgo.com"
                            class="text-blue-600 hover:text-blue-700 font-semibold">support@farmgo.com</a>
                        atau WhatsApp <a href="https://wa.me/6281234567890"
                            class="text-blue-600 hover:text-blue-700 font-semibold">0812-3456-7890</a>
                    </p>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script>
        // Countdown Timer
        @if($transaction->expired_at)
            const expiredAt = new Date('{{ $transaction->expired_at->toIso8601String() }}').getTime();

            const countdown = setInterval(function () {
                const now = new Date().getTime();
                const distance = expiredAt - now;

                if (distance < 0) {
                    clearInterval(countdown);
                    document.getElementById("countdown").innerHTML = "EXPIRED";
                    Swal.fire({
                        icon: 'error',
                        title: 'Pembayaran Kadaluarsa',
                        text: 'Waktu pembayaran telah habis. Silakan buat transaksi baru.',
                        confirmButtonColor: '#dc2626'
                    }).then(() => {
                        window.location.href = '{{ route('langganan') }}';
                    });
                    return;
                }

                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById("countdown").innerHTML =
                    String(hours).padStart(2, '0') + ":" +
                    String(minutes).padStart(2, '0') + ":" +
                    String(seconds).padStart(2, '0');
            }, 1000);
        @endif

            // Copy Order ID
            function copyOrderId() {
                navigator.clipboard.writeText('{{ $transaction->order_id }}');
                Swal.fire({
                    icon: 'success',
                    title: 'Copied!',
                    text: 'Order ID telah disalin',
                    timer: 1500,
                    showConfirmButton: false
                });
            }

        // Copy Payment Code
        function copyPaymentCode() {
            navigator.clipboard.writeText('{{ $transaction->payment_code }}');
            Swal.fire({
                icon: 'success',
                title: 'Copied!',
                text: 'Nomor pembayaran telah disalin',
                timer: 1500,
                showConfirmButton: false
            });
        }

        // Check Payment Status
        function checkPaymentStatus() {
            Swal.fire({
                title: 'Mengecek status...',
                text: 'Mohon tunggu',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('{{ route('langganan.check-status', $transaction->order_id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(response => response.json())
                .then(data => {
                    Swal.close();

                    if (data.paid) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Pembayaran Berhasil!',
                            text: 'Langganan Anda telah aktif',
                            confirmButtonColor: '#059669'
                        }).then(() => {
                            window.location.href = '{{ route('dashboard') }}';
                        });
                    } else {
                        Swal.fire({
                            icon: 'info',
                            title: 'Belum Ada Pembayaran',
                            text: 'Pembayaran Anda masih dalam status: ' + data.status,
                            confirmButtonColor: '#3b82f6'
                        });
                    }
                })
                .catch(error => {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal mengecek status pembayaran',
                        confirmButtonColor: '#dc2626'
                    });
                });
        }

        // Auto refresh - Check payment status every 5 seconds
        let pollingInterval;
        let pollCount = 0;
        const maxPolls = 288; // 24 minutes (288 * 5 seconds)

        function startPolling() {
            pollingInterval = setInterval(function () {
                pollCount++;

                // Update debug panel
                document.getElementById('debug-poll-count').textContent = pollCount;
                document.getElementById('debug-status').textContent = 'Checking...';
                document.getElementById('debug-status').className = 'text-yellow-400';

                // Show subtle indicator that we're checking
                const statusBadge = document.querySelector('.animate-pulse');
                if (statusBadge) {
                    statusBadge.classList.add('scale-110');
                    setTimeout(() => statusBadge.classList.remove('scale-110'), 300);
                }

                // Silent check in background
                fetch('{{ route('langganan.check-status', $transaction->order_id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Poll #' + pollCount + ':', data);

                        // Update debug panel
                        document.getElementById('debug-response').textContent = JSON.stringify(data, null, 2);
                        document.getElementById('debug-current-status').textContent = data.status || 'unknown';
                        document.getElementById('debug-status').textContent = 'Active';
                        document.getElementById('debug-status').className = 'text-green-400';

                        if (data.paid) {
                            // Payment successful!
                            clearInterval(pollingInterval);

                            document.getElementById('debug-status').textContent = 'PAID - Redirecting...';
                            document.getElementById('debug-status').className = 'text-green-400 animate-pulse';

                            Swal.fire({
                                icon: 'success',
                                title: 'Pembayaran Berhasil!',
                                html: '🎉 Terima kasih!<br>Langganan Anda telah aktif.',
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true
                            }).then(() => {
                                window.location.href = '{{ route('dashboard') }}';
                            });
                        } else if (data.status && data.status !== 'pending') {
                            // Status changed (but not success)
                            if (data.status === 'expire' || data.status === 'cancel') {
                                clearInterval(pollingInterval);
                                document.getElementById('debug-status').textContent = 'EXPIRED/CANCELLED';
                                document.getElementById('debug-status').className = 'text-red-400';

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Pembayaran ' + (data.status === 'expire' ? 'Kadaluarsa' : 'Dibatalkan'),
                                    text: 'Silakan buat transaksi baru',
                                    confirmButtonColor: '#dc2626'
                                }).then(() => {
                                    window.location.href = '{{ route('langganan') }}';
                                });
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Polling error:', error);
                        document.getElementById('debug-response').textContent = 'ERROR: ' + error.message;
                        document.getElementById('debug-status').textContent = 'Error';
                        document.getElementById('debug-status').className = 'text-red-400';
                    });

                // Stop after max polls to prevent endless polling
                if (pollCount >= maxPolls) {
                    clearInterval(pollingInterval);
                    console.log('Polling stopped after', pollCount, 'attempts');
                    document.getElementById('debug-status').textContent = 'Stopped (Max Polls)';
                    document.getElementById('debug-status').className = 'text-gray-400';
                }
            }, 5000); // Every 5 seconds
        }

        // Start polling when page loads
        startPolling();

        // Stop polling when user leaves the page
        window.addEventListener('beforeunload', function () {
            if (pollingInterval) {
                clearInterval(pollingInterval);
            }
        });

        // Also check when tab becomes visible again
        document.addEventListener('visibilitychange', function () {
            if (!document.hidden && !pollingInterval) {
                console.log('Tab visible again, resuming polling');
                pollCount = 0;
                startPolling();
            }
        });
    </script>
@endpush