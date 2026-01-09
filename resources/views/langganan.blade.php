@extends('layouts.app')

@section('title', 'Pilih Paket - FarmGo')
@section('page-title', 'Pilih Paket Langganan')

@section('content')

    {{-- Professional Header --}}
    <div class="text-center mb-16">
        <h1 class="text-5xl font-bold text-gray-900 mb-4">Pilih Paket yang Tepat untuk Peternakan Anda</h1>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">Tingkatkan produktivitas dan efisiensi peternakan dengan sistem
            manajemen terintegrasi</p>
    </div>

    {{-- Pricing Cards - 3 Tier Layout --}}
    <div class="max-w-7xl mx-auto">
        <div class="grid md:grid-cols-3 gap-8">

            {{-- FREE / TRIAL CARD --}}
            <div
                class="bg-white rounded-3xl shadow-lg border border-gray-200 p-8 relative hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">

                {{-- Icon --}}
                <div
                    class="w-16 h-16 rounded-2xl bg-gradient-to-br from-gray-400 to-gray-500 flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>

                {{-- Title & Price --}}
                <h3 class="text-2xl font-bold text-gray-900 text-center mb-2">Trial Gratis</h3>
                <div class="text-center mb-6">
                    <div class="flex items-baseline justify-center gap-2">
                        <span class="text-5xl font-bold text-gray-900">Rp 0</span>
                    </div>
                    <span class="text-gray-500 text-sm font-medium mt-2 block">7 hari percobaan gratis</span>
                </div>

                {{-- Description --}}
                <p class="text-center text-gray-600 mb-6 min-h-[48px]">Sempurna untuk peternakan kecil yang baru memulai</p>

                {{-- CTA Button --}}
                @if(!$user->trial_ends_at && !$activeSubscription)
                    <a href="{{ route('langganan.checkout', 'trial') }}"
                        class="block w-full bg-gradient-to-r from-gray-700 to-gray-800 hover:from-gray-800 hover:to-gray-900 text-white font-semibold py-4 rounded-xl text-center transition-all duration-300 mb-8 shadow-lg hover:shadow-xl">
                        Mulai Trial Gratis
                    </a>
                @else
                    <button disabled
                        class="block w-full bg-gray-200 text-gray-500 font-semibold py-4 rounded-xl text-center mb-8 cursor-not-allowed">
                        @if($user->isOnTrial())
                            Trial Aktif
                        @else
                            Trial Terpakai
                        @endif
                    </button>
                @endif

                {{-- Features List --}}
                <div class="space-y-4 text-sm">
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-emerald-100 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                        </div>
                        <span class="text-gray-700">Maksimal 10 ternak</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-emerald-100 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                        </div>
                        <span class="text-gray-700">Catatan kesehatan dasar</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-emerald-100 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                        </div>
                        <span class="text-gray-700">QR code generation</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-emerald-100 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                        </div>
                        <span class="text-gray-700">Email support</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-gray-200 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <span class="text-gray-700">Tracking reproduksi (max 5)</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-emerald-100 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                        </div>
                        <span class="text-gray-700">AI Assistant</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-emerald-100 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                        </div>
                        <span class="text-gray-700">Deteksi birahi</span>
                    </div>
                    <div class="flex items-start gap-3 opacity-40">
                        <div class="w-5 h-5 rounded-full bg-gray-200 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <span class="text-gray-500">Ekspor data</span>
                    </div>
                </div>
            </div>

            {{-- PREMIUM MONTHLY CARD - MOST POPULAR --}}
            <div
                class="bg-white rounded-3xl shadow-2xl border-2 border-emerald-500 p-8 relative transform md:scale-105 hover:shadow-3xl transition-all duration-300">

                {{-- Most Popular Badge --}}
                <div class="absolute -top-5 left-1/2 transform -translate-x-1/2">
                    <span
                        class="bg-gradient-to-r from-emerald-500 to-emerald-600 text-white px-6 py-2 rounded-full text-sm font-bold shadow-lg">
                        PALING POPULER
                    </span>
                </div>

                {{-- Icon --}}
                <div
                    class="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center mx-auto mb-6 mt-4 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                        </path>
                    </svg>
                </div>

                {{-- Title & Price --}}
                <h3 class="text-2xl font-bold text-gray-900 text-center mb-2">Premium Bulanan</h3>
                <div class="text-center mb-6">
                    <div class="flex items-baseline justify-center gap-2">
                        <span
                            class="text-5xl font-bold bg-gradient-to-r from-emerald-600 to-emerald-500 bg-clip-text text-transparent">Rp
                            50K</span>
                    </div>
                    <span class="text-gray-500 text-sm font-medium mt-2 block">berlaku 1 bulan</span>
                </div>

                {{-- Description --}}
                <p class="text-center text-gray-600 mb-6 min-h-[48px]">Ideal untuk peternakan berkembang yang membutuhkan
                    fitur lengkap</p>

                {{-- CTA Button --}}
                <a href="{{ route('langganan.checkout', 'premium_monthly') }}"
                    class="block w-full bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-semibold py-4 rounded-xl text-center transition-all duration-300 mb-8 shadow-lg hover:shadow-xl">
                    Mulai Berlangganan
                </a>

                {{-- Features List --}}
                <div class="space-y-4 text-sm">
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-emerald-100 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                        </div>
                        <span class="text-gray-700 font-semibold">Unlimited ternak</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-emerald-100 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                        </div>
                        <span class="text-gray-700">Monitoring kesehatan lengkap</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-emerald-100 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                        </div>
                        <span class="text-gray-700">QR code generation</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-emerald-100 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                        </div>
                        <span class="text-gray-700">Tracking reproduksi</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-emerald-100 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                        </div>
                        <span class="text-gray-700">Deteksi birahi</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-emerald-100 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                        </div>
                        <span class="text-gray-700">Ekspor data (Excel)</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-emerald-100 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                        </div>
                        <span class="text-gray-700">AI Assistant</span>
                    </div>
                </div>
            </div>

            {{-- PREMIUM YEARLY CARD --}}
            <div
                class="bg-white rounded-3xl shadow-lg border border-gray-200 p-8 relative hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">

                {{-- Savings Badge --}}
                <div class="absolute -top-4 right-6">
                    <span
                        class="bg-gradient-to-r from-amber-400 to-orange-500 text-white px-4 py-2 rounded-full text-xs font-bold shadow-lg">
                        HEMAT 16%
                    </span>
                </div>

                {{-- Icon --}}
                <div
                    class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                        </path>
                    </svg>
                </div>

                {{-- Title & Price --}}
                <h3 class="text-2xl font-bold text-gray-900 text-center mb-2">Premium Tahunan</h3>
                <div class="text-center mb-6">
                    <div class="flex items-baseline justify-center gap-2">
                        <span class="text-5xl font-bold text-gray-900">Rp 500K</span>
                    </div>
                    <span class="text-gray-500 text-sm font-medium mt-2 block">berlaku 1 tahun</span>
                </div>

                {{-- Description --}}
                <p class="text-center text-gray-600 mb-6 min-h-[48px]">Solusi terbaik untuk operasi komersial skala besar
                </p>

                {{-- CTA Button --}}
                <a href="{{ route('langganan.checkout', 'premium_yearly') }}"
                    class="block w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-4 rounded-xl text-center transition-all duration-300 mb-8 shadow-lg hover:shadow-xl">
                    Mulai Berlangganan
                </a>

                {{-- Features List --}}
                <div class="space-y-4 text-sm">
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-emerald-100 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                        </div>
                        <span class="text-gray-700">Semua fitur Premium Bulanan</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-emerald-100 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                        </div>
                        <span class="text-gray-700">Deteksi birahi lanjutan</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-emerald-100 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                        </div>
                        <span class="text-gray-700">Cetak QR massal</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Current Subscription Info (if any) --}}
    @if($activeSubscription && $activeSubscription->isActive())
        <div class="max-w-7xl mx-auto mt-16">
            <div class="bg-gradient-to-r from-emerald-50 to-green-50 border-2 border-emerald-200 rounded-2xl p-8 shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-6">
                        <div
                            class="bg-gradient-to-br from-emerald-500 to-emerald-600 text-white w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-xl text-gray-900">Langganan Aktif</p>
                            <p class="text-gray-600 mt-1">
                                <span
                                    class="font-semibold">{{ $activeSubscription->paket_langganan == 'premium_monthly' ? 'Premium Bulanan' : 'Premium Tahunan' }}</span>
                                â€¢ Berlaku hingga {{ $activeSubscription->tanggal_berakhir->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('langganan.history') }}"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-xl font-semibold transition-colors shadow-lg">
                        Lihat Riwayat
                    </a>
                </div>
            </div>
        </div>
    @endif

    {{-- Pending Transaction Alert --}}
    @if($pendingTransaction)
        <div class="max-w-7xl mx-auto mt-12">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-300 rounded-2xl p-8 shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-6">
                        <div
                            class="bg-gradient-to-br from-blue-500 to-blue-600 text-white w-16 h-16 rounded-2xl flex items-center justify-center animate-pulse shadow-lg">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-xl text-gray-900">Pembayaran Menunggu</p>
                            <p class="text-gray-600 mt-1">
                                Order <span class="font-mono font-semibold">#{{ $pendingTransaction->order_id }}</span> â€¢
                                <span class="font-semibold">Rp
                                    {{ number_format($pendingTransaction->gross_amount, 0, ',', '.') }}</span>
                                @if($pendingTransaction->expired_at)
                                    â€¢ Kadaluarsa {{ $pendingTransaction->expired_at->diffForHumans() }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('langganan.pending', $pendingTransaction->order_id) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-semibold transition-colors shadow-lg whitespace-nowrap">
                        Lanjutkan Pembayaran
                    </a>
                </div>
            </div>
        </div>
    @endif

    {{-- Trial Period Info --}}
    @if($user->isOnTrial())
        <div class="max-w-7xl mx-auto mt-12">
            <div class="bg-gradient-to-r from-amber-50 to-yellow-50 border-2 border-amber-300 rounded-2xl p-8 shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-6">
                        <div
                            class="bg-gradient-to-br from-amber-400 to-orange-500 text-white w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-xl text-gray-900">Trial Period Aktif</p>
                            <p class="text-gray-600 mt-1">
                                <span class="font-semibold">{{ $user->trialDaysRemaining() }} hari tersisa</span> &middot;
                                Berakhir {{ $user->trial_ends_at->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('langganan.checkout', 'premium_monthly') }}"
                        class="bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white px-6 py-3 rounded-xl font-semibold transition-all shadow-lg">
                        Upgrade Sekarang
                    </a>
                </div>
            </div>
        </div>
    @endif

    {{-- FAQ Section --}}
    <div class="max-w-4xl mx-auto mt-20">
        <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Pertanyaan yang Sering Diajukan</h2>
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-200">
                <h3 class="font-bold text-lg text-gray-900 mb-2">Apakah saya bisa upgrade atau downgrade paket?</h3>
                <p class="text-gray-600">Ya, Anda dapat mengubah paket langganan kapan saja. Perubahan akan berlaku pada
                    periode penagihan berikutnya.</p>
            </div>
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-200">
                <h3 class="font-bold text-lg text-gray-900 mb-2">Bagaimana cara pembayaran?</h3>
                <p class="text-gray-600">Kami menerima berbagai metode pembayaran melalui Midtrans termasuk transfer bank,
                    e-wallet, dan kartu kredit.</p>
            </div>
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-200">
                <h3 class="font-bold text-lg text-gray-900 mb-2">Apakah data saya aman?</h3>
                <p class="text-gray-600">Keamanan data Anda adalah prioritas kami. Semua data dienkripsi dan disimpan dengan
                    standar keamanan tinggi.</p>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Check for unread subscription notifications
        document.addEventListener('DOMContentLoaded', function () {
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
                    confirmButtonText: 'Upgrade Sekarang',
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