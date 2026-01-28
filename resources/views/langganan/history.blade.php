@extends('layouts.app')

@section('title', 'Riwayat Langganan - FarmGo')
@section('page-title', 'Riwayat Langganan')

@section('content')

    <div>
        
        {{-- Current Subscription Card --}}
        @if($currentSubscription)
            <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl p-8 text-white mb-8 shadow-xl">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full mb-4">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <span class="font-bold">Langganan Aktif</span>
                        </div>
                        
                        <h1 class="text-3xl font-bold mb-2">
                            {{ $currentSubscription->paket_langganan === 'premium_monthly' ? 'Premium Bulanan' : 'Premium Tahunan' }}
                        </h1>
                        
                        <div class="flex items-center gap-6 text-sm">
                            <div>
                                <span class="text-white/80">Mulai:</span>
                                <span class="font-semibold">{{ $currentSubscription->tanggal_mulai->format('d M Y') }}</span>
                            </div>
                            <div>
                                <span class="text-white/80">Berakhir:</span>
                                <span class="font-semibold">{{ $currentSubscription->tanggal_berakhir->format('d M Y') }}</span>
                            </div>
                            <div>
                                <span class="text-white/80">Sisa:</span>
                                <span class="font-bold text-yellow-300">{{ $currentSubscription->daysRemaining() }} hari</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-right">
                        <div class="text-5xl mb-2">👑</div>
                        <div class="text-2xl font-bold">Rp {{ number_format($currentSubscription->harga, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Billing History --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">Riwayat Pembayaran</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Paket</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Periode</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Metode</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($transactions as $transaction)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $transaction->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-semibold text-gray-900">
                                        @if($transaction->gross_amount == 50000)
                                            Premium Bulanan
                                        @elseif($transaction->gross_amount == 500000)
                                            Premium Tahunan
                                        @else
                                            Premium
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    @if($transaction->langganan)
                                        {{ $transaction->langganan->tanggal_mulai->format('d M') }} - {{ $transaction->langganan->tanggal_berakhir->format('d M Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                    Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($transaction->status === 'settlement')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                            Berhasil
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    @php
                                        $paymentMethod = match($transaction->payment_type) {
                                            'bank_transfer' => ($transaction->bank ? strtoupper($transaction->bank) . ' Virtual Account' : 'Bank Transfer'),
                                            'echannel' => 'Mandiri Bill Payment',
                                            'gopay' => 'GoPay',
                                            'shopeepay' => 'ShopeePay',
                                            'qris' => 'QRIS',
                                            'credit_card' => 'Credit/Debit Card',
                                            default => ucfirst(str_replace('_', ' ', $transaction->payment_type ?? 'Unknown'))
                                        };
                                        echo $paymentMethod;
                                    @endphp
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="text-gray-400 text-5xl mb-4">📋</div>
                                    <p class="text-gray-600">Belum ada riwayat langganan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Back Button --}}
        <div class="mt-6">
            <a href="{{ route('langganan.index') }}" 
                class="inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-700 font-semibold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke Langganan
            </a>
        </div>

    </div>

@endsection
