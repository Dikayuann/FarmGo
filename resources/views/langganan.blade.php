@extends('layouts.app')

@section('title', 'Pilih Paket - FarmGo')
@section('page-title', 'Pilih Paket Langganan')

@section('content')

    {{-- Simple Header --}}
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-3">Pilih Paket yang Tepat untuk Anda</h1>
        <p class="text-lg text-gray-600">Kelola peternakan dengan lebih efisien bersama FarmGo</p>
    </div>

    {{-- Pricing Cards - 3 Tier Layout --}}
    <div class="max-w-7xl mx-auto">
        <div class="grid md:grid-cols-3 gap-6">

            {{-- FREE / TRIAL CARD --}}
            <div
                class="bg-white rounded-2xl shadow-md border-2 border-gray-200 p-8 relative hover:shadow-xl transition-shadow">
                {{-- Icon --}}
                <div class="w-20 h-20 rounded-full bg-gray-400 flex items-center justify-center mx-auto mb-6">
                    <span class="text-3xl font-bold text-white">F</span>
                </div>

                {{-- Title & Price --}}
                <h3 class="text-2xl font-bold text-gray-900 text-center mb-2">Trial</h3>
                <div class="text-center mb-6">
                    <span class="text-5xl font-bold text-gray-900">Rp 0</span>
                    <span class="text-gray-600 text-lg"> / 7 hari</span>
                </div>

                {{-- Description --}}
                <p class="text-center text-gray-600 mb-4">Sempurna untuk peternakan kecil yang baru memulai</p>
                <p class="text-center text-red-500 font-semibold mb-6">7 hari trial</p>

                {{-- CTA Button --}}
                @if(!$user->trial_ends_at && !$activeSubscription)
                    <a href="{{ route('langganan.checkout', 'trial') }}"
                        class="block w-full bg-gray-800 hover:bg-gray-900 text-white font-semibold py-3 rounded-lg text-center transition-colors mb-6">
                        Mulai Trial
                    </a>
                @else
                    <button disabled
                        class="block w-full bg-gray-300 text-gray-500 font-semibold py-3 rounded-lg text-center mb-6 cursor-not-allowed">
                        @if($user->isOnTrial())
                            Trial Aktif
                        @else
                            Trial Terpakai
                        @endif
                    </button>
                @endif

                {{-- Features List --}}
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-gray-700">Maks 10 ternak</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-gray-700">Catatan kesehatan dasar</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-gray-700">QR code generation</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-gray-700">Email support</span>
                    </li>
                    <li class="flex items-start gap-2 text-gray-400">
                        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                        <span class="line-through">Tracking reproduksi</span>
                    </li>
                    <li class="flex items-start gap-2 text-gray-400">
                        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                        <span class="line-through">Ekspor data</span>
                    </li>
                </ul>
            </div>

            {{-- PREMIUM MONTHLY CARD - MOST POPULAR --}}
            <div
                class="bg-white rounded-2xl shadow-xl border-4 border-emerald-500 p-8 relative transform scale-105 hover:shadow-2xl transition-all">
                {{-- Most Popular Badge --}}
                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                    <span
                        class="bg-gradient-to-r from-emerald-500 to-blue-500 text-white px-6 py-2 rounded-full text-sm font-bold shadow-lg">
                        Paling Populer
                    </span>
                </div>

                {{-- Icon --}}
                <div class="w-20 h-20 rounded-full bg-blue-500 flex items-center justify-center mx-auto mb-6 mt-4">
                    <span class="text-3xl font-bold text-white">B</span>
                </div>

                {{-- Title & Price --}}
                <h3 class="text-2xl font-bold text-gray-900 text-center mb-2">Premium Bulanan</h3>
                <div class="text-center mb-6">
                    <span class="text-5xl font-bold text-gray-900">Rp 50K</span>
                    <span class="text-gray-600 text-lg"> / bulan</span>
                </div>

                {{-- Description --}}
                <p class="text-center text-gray-600 mb-8">Cocok untuk peternakan berkembang</p>

                {{-- CTA Button --}}
                <a href="{{ route('langganan.checkout', 'premium_monthly') }}"
                    class="block w-full bg-gradient-to-r from-emerald-500 to-blue-500 hover:from-emerald-600 hover:to-blue-600 text-white font-semibold py-3 rounded-lg text-center transition-all mb-6 shadow-lg">
                    Upgrade Sekarang
                </a>

                {{-- Features List --}}
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-gray-700 font-semibold">Unlimited ternak</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-gray-700">Monitoring kesehatan lengkap</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-gray-700">QR code generation</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-gray-700">Tracking reproduksi</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-gray-700">Analitik dasar</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-gray-700">Priority email support</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-gray-700">Ekspor data (CSV)</span>
                    </li>
                </ul>
            </div>

            {{-- PREMIUM YEARLY CARD - PRO --}}
            <div
                class="bg-white rounded-2xl shadow-md border-2 border-gray-200 p-8 relative hover:shadow-xl transition-shadow">
                {{-- Savings Badge --}}
                <div class="absolute -top-3 right-4">
                    <span class="bg-yellow-400 text-yellow-900 px-3 py-1 rounded-full text-xs font-bold">
                        HEMAT 16%
                    </span>
                </div>

                {{-- Icon --}}
                <div class="w-20 h-20 rounded-full bg-emerald-500 flex items-center justify-center mx-auto mb-6">
                    <span class="text-3xl font-bold text-white">P</span>
                </div>

                {{-- Title & Price --}}
                <h3 class="text-2xl font-bold text-gray-900 text-center mb-2">Premium Tahunan</h3>
                <div class="text-center mb-6">
                    <span class="text-5xl font-bold text-gray-900">Rp 500K</span>
                    <span class="text-gray-600 text-lg"> / tahun</span>
                </div>

                {{-- Description --}}
                <p class="text-center text-gray-600 mb-8">Untuk operasi komersial besar</p>

                {{-- CTA Button --}}
                <a href="{{ route('langganan.checkout', 'premium_yearly') }}"
                    class="block w-full bg-gray-800 hover:bg-gray-900 text-white font-semibold py-3 rounded-lg text-center transition-colors mb-6">
                    Upgrade Sekarang
                </a>

                {{-- Features List --}}
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-gray-700 font-semibold">Unlimited ternak</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-gray-700">Advanced health monitoring</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-gray-700">Bulk QR code generation</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-gray-700">Advanced reproduction analytics</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-gray-700">Custom reports</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-gray-700">API access</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-gray-700">Priority phone support</span>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    {{-- Current Subscription Info (if any) --}}
    @if($activeSubscription && $activeSubscription->isActive())
        <div class="max-w-7xl mx-auto mt-12">
            <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="bg-emerald-500 text-white w-12 h-12 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Langganan Anda Aktif</p>
                            <p class="text-sm text-gray-600">
                                {{ $activeSubscription->paket_langganan == 'premium_monthly' ? 'Premium Bulanan' : 'Premium Tahunan' }}
                                • Aktif hingga {{ $activeSubscription->tanggal_berakhir->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('langganan.history') }}" class="text-emerald-600 hover:text-emerald-700 font-semibold">
                        Lihat Riwayat →
                    </a>
                </div>
            </div>
        </div>
    @endif

    {{-- Pending Transaction Alert --}}
    @if($pendingTransaction)
        <div class="max-w-7xl mx-auto mt-12">
            <div class="bg-blue-50 border-2 border-blue-300 rounded-2xl p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div
                            class="bg-blue-500 text-white w-12 h-12 rounded-full flex items-center justify-center animate-pulse">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Pembayaran Menunggu</p>
                            <p class="text-sm text-gray-600">
                                Order #{{ $pendingTransaction->order_id }} •
                                Rp {{ number_format($pendingTransaction->gross_amount, 0, ',', '.') }}
                                @if($pendingTransaction->expired_at)
                                    • Kadaluarsa {{ $pendingTransaction->expired_at->diffForHumans() }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('langganan.pending', $pendingTransaction->order_id) }}"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold transition-colors shadow-md">
                        Lanjutkan Pembayaran
                    </a>
                </div>
            </div>
        </div>
    @endif

    @if($user->isOnTrial())
        <div class="max-w-7xl mx-auto mt-12">
            <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="bg-yellow-400 text-yellow-900 w-12 h-12 rounded-full flex items-center justify-center">
                            <span class="text-2xl">🎁</span>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Trial Period Aktif</p>
                            <p class="text-sm text-gray-600">
                                {{ $user->trialDaysRemaining() }} hari tersisa • Berakhir
                                {{ $user->trial_ends_at->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('langganan.checkout', 'premium_monthly') }}"
                        class="bg-yellow-400 hover:bg-yellow-500 text-yellow-900 px-6 py-2 rounded-lg font-semibold transition-colors">
                        Upgrade Sekarang
                    </a>
                </div>
            </div>
        </div>
    @endif

@endsection

@push('scripts')
    <script>
        // Check for unread subscription notifications
        document.addEventListener('DOMContentLoaded', function() {
            fetch('/notifications/unread-count')
                .then(response => response.json())
                .then(data => {
                    if (data.count > 0) {
                        // Fetch latest notification
                        fetch('/api/notifications/latest-langganan')
                            .then(response => response.json())
                            .then(notif => {
                                if (notif && notif.jenis_notifikasi === 'langganan') {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Langganan Berhasil!',
                                        html: notif.pesan,
                                        confirmButtonColor: '#10b981',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            });
                    }
                });
        });

        // Auto show upgrade notification for trial users nearing expiry
        @if($user->isOnTrial() && $user->trialDaysRemaining() <= 3)
            // Check if notification already shown today
            const notifKey = 'trial_reminder_{{ date('Y-m-d') }}';
            if (!localStorage.getItem(notifKey)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Trial Anda Akan Berakhir!',
                    html: 'Hanya tersisa <strong>{{ $user->trialDaysRemaining() }} hari</strong> lagi.<br>Upgrade sekarang untuk terus menikmati fitur premium.',
                    showCancelButton: true,
                    confirmButtonText: ' Upgrade Sekarang',
                    confirmButtonColor: '#10b981',
                    cancelButtonText: 'Nanti Saja',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route('langganan.checkout', 'premium_monthly') }}';
                    }
                });
                localStorage.setItem(notifKey, 'shown');
            }
        @endif
    </script>
@endpush