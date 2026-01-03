@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Notifikasi</h1>
                    <p class="text-gray-600">Kelola semua notifikasi Anda</p>
                </div>
                <div class="flex gap-3">
                    @if($notifications->where('status', 'belum_dibaca')->count() > 0)
                        <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Tandai Semua Sudah Dibaca
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex gap-6">
                    <a href="{{ route('notifications.index') }}"
                        class="border-b-2 {{ !request('filter') ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} py-4 px-1 text-sm font-medium transition">
                        Semua
                        <span class="ml-2 px-2 py-1 text-xs rounded-full {{ !request('filter') ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600' }}">
                            {{ $notifications->total() }}
                        </span>
                    </a>
                    <a href="{{ route('notifications.index', ['filter' => 'belum_dibaca']) }}"
                        class="border-b-2 {{ request('filter') == 'belum_dibaca' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} py-4 px-1 text-sm font-medium transition">
                        Belum Dibaca
                        <span class="ml-2 px-2 py-1 text-xs rounded-full {{ request('filter') == 'belum_dibaca' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600' }}">
                            {{ Auth::user()->notifications()->where('status', 'belum_dibaca')->count() }}
                        </span>
                    </a>
                    <a href="{{ route('notifications.index', ['filter' => 'sudah_dibaca']) }}"
                        class="border-b-2 {{ request('filter') == 'sudah_dibaca' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} py-4 px-1 text-sm font-medium transition">
                        Sudah Dibaca
                    </a>
                </nav>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            @forelse($notifications as $notification)
                <div
                    class="border-b border-gray-100 last:border-b-0 {{ $notification->status == 'belum_dibaca' ? 'bg-blue-50' : 'bg-white' }} hover:bg-gray-50 transition">
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <!-- Icon -->
                            <div class="shrink-0">
                                @if($notification->jenis_notifikasi == 'vaksin')
                                    <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                @elseif($notification->jenis_notifikasi == 'kesehatan')
                                    <div class="h-12 w-12 rounded-full bg-red-100 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                            </path>
                                        </svg>
                                    </div>
                                @elseif($notification->jenis_notifikasi == 'reproduksi')
                                    <div class="h-12 w-12 rounded-full bg-purple-100 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                            </path>
                                        </svg>
                                    </div>
                                @elseif($notification->jenis_notifikasi == 'langganan')
                                    <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                @else
                                    <div class="h-12 w-12 rounded-full bg-gray-100 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span
                                                class="px-2 py-1 text-xs font-medium rounded-full
                                                @if($notification->jenis_notifikasi == 'vaksin') bg-blue-100 text-blue-700
                                                @elseif($notification->jenis_notifikasi == 'kesehatan') bg-red-100 text-red-700
                                                @elseif($notification->jenis_notifikasi == 'reproduksi') bg-purple-100 text-purple-700
                                                @elseif($notification->jenis_notifikasi == 'langganan') bg-green-100 text-green-700
                                                @else bg-gray-100 text-gray-700 @endif">
                                                {{ ucfirst($notification->jenis_notifikasi) }}
                                            </span>
                                            @if($notification->status == 'belum_dibaca')
                                                <span
                                                    class="w-2 h-2 bg-blue-600 rounded-full animate-pulse"></span>
                                            @endif
                                        </div>
                                        <p
                                            class="text-base {{ $notification->status == 'belum_dibaca' ? 'font-semibold text-gray-900' : 'text-gray-700' }} mb-2">
                                            {{ $notification->pesan }}
                                        </p>
                                        <div class="flex items-center gap-4 text-sm text-gray-500">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                {{ $notification->tanggal_kirim->diffForHumans() }}
                                            </span>
                                            @if($notification->animal)
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                                        </path>
                                                    </svg>
                                                    {{ $notification->animal->kode_hewan }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex items-center gap-2">
                                        @if($notification->status == 'belum_dibaca')
                                            <form action="{{ route('notifications.mark-read', $notification->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                                    title="Tandai sudah dibaca">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-green-600 p-2" title="Sudah dibaca">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" />
                                                </svg>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-16 text-center">
                    <svg class="w-20 h-20 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                        </path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak ada notifikasi</h3>
                    <p class="text-gray-500">Anda belum memiliki notifikasi di kategori ini</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
@endsection

