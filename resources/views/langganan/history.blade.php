@extends('layouts.app')

@section('title', 'Riwayat Pembayaran - FarmGo')
@section('page-title', 'Riwayat Pembayaran')

@section('content')

    <div>

        {{-- Current Subscription Card --}}
        @if($currentSubscription)
            <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl p-6 sm:p-8 text-white mb-8 shadow-xl">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                    <div>
                        <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full mb-4">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <span class="font-bold">Langganan Aktif</span>
                        </div>

                        <h1 class="text-2xl sm:text-3xl font-bold mb-2">
                            {{ $currentSubscription->paket_langganan === 'premium_monthly' ? 'Premium Bulanan' : 'Premium Tahunan' }}
                        </h1>

                        <div class="flex flex-wrap items-center gap-4 sm:gap-6 text-sm">
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

                    <div class="text-left sm:text-right">
                        <div class="text-4xl sm:text-5xl mb-2">👑</div>
                        <div class="text-xl sm:text-2xl font-bold">Rp {{ number_format($currentSubscription->harga, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Payment History --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <h2 class="text-xl font-bold text-gray-900">Riwayat Pembayaran</h2>
                <span class="text-sm text-gray-500">{{ $transactions->count() }} transaksi</span>
            </div>

            {{-- Desktop Table --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Order ID</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Paket</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Metode</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($transactions as $transaction)
                            <tr class="hover:bg-gray-50 transition-colors">
                                {{-- Order ID --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono text-sm text-gray-900 font-semibold">{{ $transaction->order_id }}</span>
                                        <button onclick="copyToClipboard('{{ $transaction->order_id }}')"
                                            class="text-gray-400 hover:text-gray-600 transition-colors" title="Copy Order ID">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>

                                {{-- Date --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $transaction->created_at->format('d M Y') }}
                                    <div class="text-xs text-gray-500">{{ $transaction->created_at->format('H:i') }}</div>
                                </td>

                                {{-- Package --}}
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

                                {{-- Price --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                    Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @switch($transaction->status)
                                        @case('settlement')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                                Berhasil
                                            </span>
                                            @break
                                        @case('pending')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                                <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2 animate-pulse"></div>
                                                Menunggu
                                            </span>
                                            @break
                                        @case('expired')
                                        @case('expire')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                                                Kadaluarsa
                                            </span>
                                            @break
                                        @case('cancelled')
                                        @case('cancel')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                                <div class="w-2 h-2 bg-gray-500 rounded-full mr-2"></div>
                                                Dibatalkan
                                            </span>
                                            @break
                                        @case('failed')
                                        @case('deny')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                                                Gagal
                                            </span>
                                            @break
                                        @case('refunded')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                                                <div class="w-2 h-2 bg-purple-500 rounded-full mr-2"></div>
                                                Refund
                                            </span>
                                            @break
                                        @default
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                                {{ ucfirst($transaction->status) }}
                                            </span>
                                    @endswitch
                                </td>

                                {{-- Payment Method --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $transaction->payment_method ?? '-' }}
                                </td>

                                {{-- Action --}}
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($transaction->status === 'pending')
                                        <a href="{{ route('langganan.pending', $transaction->order_id) }}"
                                            class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-4 py-2 rounded-lg transition-colors shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            Bayar
                                        </a>
                                    @elseif($transaction->status === 'settlement')
                                        <button onclick="showDetail('{{ $transaction->order_id }}', '{{ $transaction->created_at->format('d M Y H:i') }}', '{{ $transaction->gross_amount == 50000 ? 'Premium Bulanan' : ($transaction->gross_amount == 500000 ? 'Premium Tahunan' : 'Premium') }}', 'Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}', '{{ $transaction->payment_method ?? '-' }}', '{{ $transaction->paid_at ? $transaction->paid_at->format('d M Y H:i') : $transaction->created_at->format('d M Y H:i') }}', '{{ $transaction->langganan ? $transaction->langganan->tanggal_mulai->format('d M Y') . ' - ' . $transaction->langganan->tanggal_berakhir->format('d M Y') : '-' }}')"
                                            class="inline-flex items-center gap-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-semibold px-4 py-2 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Detail
                                        </button>
                                    @else
                                        <span class="text-gray-400 text-xs">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="text-gray-400 text-5xl mb-4">📋</div>
                                    <p class="text-gray-600 font-semibold mb-1">Belum ada riwayat pembayaran</p>
                                    <p class="text-gray-500 text-sm">Transaksi pembayaran Anda akan muncul di sini</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile Cards --}}
            <div class="md:hidden divide-y divide-gray-200">
                @forelse($transactions as $transaction)
                    <div class="p-4 hover:bg-gray-50 transition-colors">
                        {{-- Header: Order ID + Status --}}
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-mono text-xs text-gray-700 font-semibold">{{ $transaction->order_id }}</span>
                                    <button onclick="copyToClipboard('{{ $transaction->order_id }}')"
                                        class="text-gray-400 hover:text-gray-600 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500">{{ $transaction->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            @switch($transaction->status)
                                @case('settlement')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        <div class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></div>Berhasil
                                    </span>
                                    @break
                                @case('pending')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                        <div class="w-1.5 h-1.5 bg-yellow-500 rounded-full mr-1.5 animate-pulse"></div>Menunggu
                                    </span>
                                    @break
                                @case('expired')
                                @case('expire')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                        <div class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5"></div>Kadaluarsa
                                    </span>
                                    @break
                                @case('cancelled')
                                @case('cancel')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                        <div class="w-1.5 h-1.5 bg-gray-500 rounded-full mr-1.5"></div>Dibatalkan
                                    </span>
                                    @break
                                @case('failed')
                                @case('deny')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                        <div class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5"></div>Gagal
                                    </span>
                                    @break
                                @default
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                            @endswitch
                        </div>

                        {{-- Package + Price --}}
                        <div class="flex items-center justify-between mb-3">
                            <span class="font-semibold text-gray-900 text-sm">
                                @if($transaction->gross_amount == 50000) Premium Bulanan
                                @elseif($transaction->gross_amount == 500000) Premium Tahunan
                                @else Premium @endif
                            </span>
                            <span class="font-bold text-gray-900">Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}</span>
                        </div>

                        {{-- Payment Method --}}
                        @if($transaction->payment_method)
                            <p class="text-xs text-gray-500 mb-3">{{ $transaction->payment_method }}</p>
                        @endif

                        {{-- Action Button --}}
                        @if($transaction->status === 'pending')
                            <a href="{{ route('langganan.pending', $transaction->order_id) }}"
                                class="flex items-center justify-center gap-2 w-full bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold py-2.5 rounded-xl transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Bayar Sekarang
                            </a>
                        @elseif($transaction->status === 'settlement')
                            <button onclick="showDetail('{{ $transaction->order_id }}', '{{ $transaction->created_at->format('d M Y H:i') }}', '{{ $transaction->gross_amount == 50000 ? 'Premium Bulanan' : ($transaction->gross_amount == 500000 ? 'Premium Tahunan' : 'Premium') }}', 'Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}', '{{ $transaction->payment_method ?? '-' }}', '{{ $transaction->paid_at ? $transaction->paid_at->format('d M Y H:i') : $transaction->created_at->format('d M Y H:i') }}', '{{ $transaction->langganan ? $transaction->langganan->tanggal_mulai->format('d M Y') . ' - ' . $transaction->langganan->tanggal_berakhir->format('d M Y') : '-' }}')"
                                class="flex items-center justify-center gap-2 w-full bg-blue-50 hover:bg-blue-100 text-blue-700 text-sm font-semibold py-2.5 rounded-xl transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Lihat Detail
                            </button>
                        @endif
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <div class="text-gray-400 text-5xl mb-4">📋</div>
                        <p class="text-gray-600 font-semibold mb-1">Belum ada riwayat pembayaran</p>
                        <p class="text-gray-500 text-sm">Transaksi pembayaran Anda akan muncul di sini</p>
                    </div>
                @endforelse
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

@push('scripts')
    <script>
        // Copy to clipboard
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text);
            Swal.fire({
                icon: 'success',
                title: 'Disalin!',
                text: 'Order ID telah disalin ke clipboard',
                timer: 1500,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }

        // Show payment detail modal
        function showDetail(orderId, date, paket, harga, metode, paidAt, periode) {
            Swal.fire({
                title: '<span class="text-lg font-bold">Detail Pembayaran</span>',
                html: `
                    <div class="text-left space-y-3 mt-4">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Order ID</span>
                            <span class="text-sm font-mono font-semibold text-gray-900">${orderId}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Tanggal Transaksi</span>
                            <span class="text-sm font-semibold text-gray-900">${date}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Paket</span>
                            <span class="text-sm font-semibold text-gray-900">${paket}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Total Bayar</span>
                            <span class="text-sm font-bold text-emerald-600">${harga}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Metode</span>
                            <span class="text-sm font-semibold text-gray-900">${metode}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Dibayar</span>
                            <span class="text-sm font-semibold text-gray-900">${paidAt}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm text-gray-500">Periode</span>
                            <span class="text-sm font-semibold text-gray-900">${periode}</span>
                        </div>
                        <div class="mt-4 pt-3 border-t border-gray-200 flex items-center justify-center gap-2">
                            <div class="w-2.5 h-2.5 bg-green-500 rounded-full"></div>
                            <span class="text-sm font-semibold text-green-700">Pembayaran Berhasil</span>
                        </div>
                    </div>
                `,
                showCloseButton: true,
                showConfirmButton: false,
                width: 480,
                customClass: {
                    popup: 'rounded-2xl',
                }
            });
        }
    </script>
@endpush
