<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FarmGo')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        .sidebar-transition {
            transition: all 0.3s ease-in-out;
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800 font-sans">

    <div id="main-layout" class="flex h-screen overflow-hidden">

        <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-20 hidden lg:hidden"
            onclick="toggleMobileSidebar()"></div>

        <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 z-30 w-64 bg-white border-r border-gray-200
                      transform -translate-x-full lg:translate-x-0 sidebar-transition flex flex-col h-full shrink-0">

            <div class="h-20 flex items-center justify-center px-6 border-b border-gray-100">
                <div class="flex items-center justify-center text-green-600">
                    <img src="{{ asset('image/FarmGo.png') }}" alt="FarmGo" class="max-w-10 max-h-10">
                </div>
            </div>

            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto text-sm">

                <a href="{{ url('/dashboard') }}" title="Dashboard"
                    class="nav-item flex items-center px-4 py-2.5 rounded-lg transition group relative tooltip-trigger
                    {{ request()->is('dashboard') || request()->is('/') ? 'bg-emerald-600 text-white font-semibold shadow-md' : 'text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 font-medium' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                        </path>
                    </svg>
                    <span class="nav-text whitespace-nowrap ml-3">Dashboard</span>
                </a>

                <a href="{{ route('ternak.index') }}" title="Manajemen Ternak"
                    class="nav-item flex items-center px-4 py-2.5 rounded-lg transition group relative tooltip-trigger
                    {{ request()->routeIs('ternak*') ? 'bg-emerald-600 text-white font-semibold shadow-md' : 'text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 font-medium' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                    <span class="nav-text whitespace-nowrap ml-3">Manajemen Ternak</span>
                </a>

                <a href="{{ route('kesehatan.index') }}" title="Monitoring Kesehatan"
                    class="nav-item flex items-center px-4 py-2.5 rounded-lg transition group relative tooltip-trigger
                    {{ request()->routeIs('kesehatan*') ? 'bg-emerald-600 text-white font-semibold shadow-md' : 'text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 font-medium' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <span class="nav-text whitespace-nowrap ml-3">Monitoring Kesehatan</span>
                </a>

                <a href="{{ route('reproduksi.index') }}" title="Catatan Reproduksi"
                    class="nav-item flex items-center px-4 py-2.5 rounded-lg transition group relative tooltip-trigger
                    {{ request()->routeIs('reproduksi*') ? 'bg-emerald-600 text-white font-semibold shadow-md' : 'text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 font-medium' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                    <span class="nav-text whitespace-nowrap ml-3">Catatan Reproduksi</span>
                </a>

                <a href="{{ route('ekspor.index') }}" title="Ekspor Data"
                    class="nav-item flex items-center px-4 py-2.5 rounded-lg transition group relative font-medium tooltip-trigger
                    {{ request()->routeIs('ekspor*') ? 'bg-emerald-600 text-white font-semibold shadow-md' : 'text-gray-600 hover:bg-emerald-50 hover:text-emerald-700' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    <span class="nav-text whitespace-nowrap ml-3">Ekspor Data</span>
                </a>

                <a href="{{ route('langganan') }}" title="Harga / Langganan"
                    class="nav-item flex items-center px-4 py-2.5 rounded-lg transition group relative tooltip-trigger
                    {{ request()->routeIs('langganan*') ? 'bg-emerald-600 text-white font-semibold shadow-md' : 'text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 font-medium' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                        </path>
                    </svg>
                    <span class="nav-text whitespace-nowrap ml-3">Harga / Langganan</span>
                </a>

                <div class="pt-3 mt-3 border-t border-gray-200">
                    <a href="{{ route('settings.index') }}" title="Pengaturan"
                        class="nav-item flex items-center px-4 py-2.5 rounded-lg transition group relative font-medium tooltip-trigger
                        {{ request()->routeIs('settings*') ? 'bg-emerald-600 text-white font-semibold shadow-md' : 'text-gray-600 hover:bg-emerald-50 hover:text-emerald-700' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="nav-text whitespace-nowrap ml-3">Pengaturan</span>
                    </a>
                </div>
            </nav>

            <!-- Collapse Button at Bottom -->
            <div class="border-t border-gray-200 p-4">
                <button onclick="toggleDesktopSidebar()"
                    class="hidden lg:flex items-center justify-center w-full gap-3 px-4 py-3 text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 rounded-lg transition group relative font-medium"
                    title="Toggle Sidebar">
                    <!-- Icon for Expanded State (Chevron Left) -->
                    <svg id="collapse-icon" class="w-5 h-5 shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                    </svg>
                    <!-- Icon for Collapsed State (Chevron Right) - Hidden by default -->
                    <svg id="expand-icon" class="w-5 h-5 shrink-0 hidden" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                    </svg>
                    <span class="nav-text whitespace-nowrap">Ciutkan Sidebar</span>
                </button>
            </div>
        </aside>

        <main id="main-content" class="flex-1 flex flex-col h-screen overflow-y-auto sidebar-transition">

            <header
                class="bg-white h-20 border-b border-gray-200 flex items-center justify-between px-8 sticky top-0 z-10">
                <div class="flex items-center gap-4">
                    <button onclick="toggleMobileSidebar()" class="lg:hidden text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>

                <div class="flex items-center gap-6">
                    <!-- Notification Bell with Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" class="relative text-gray-400 hover:text-gray-600 transition">
                            @if($notificationCount > 0)
                                <div
                                    class="absolute -top-1 -right-1 h-5 w-5 bg-red-500 rounded-full flex items-center justify-center text-[10px] text-white font-bold border-2 border-white">
                                    {{ $notificationCount > 9 ? '9+' : $notificationCount }}
                                </div>
                            @endif
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                </path>
                            </svg>
                        </button>

                        <!-- Notification Dropdown -->
                        <div x-show="open" x-transition
                            class="absolute right-0 mt-3 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-50"
                            style="display: none;">
                            <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                                <h3 class="font-semibold text-gray-800">Notifikasi</h3>
                                @if($notificationCount > 0)
                                    <span class="text-xs text-blue-600 font-medium">{{ $notificationCount }} belum
                                        dibaca</span>
                                @endif
                            </div>
                            <div class="max-h-96 overflow-y-auto">
                                @forelse(Auth::user()->notifications()->orderBy('created_at', 'desc')->take(5)->get() as $notification)
                                    <a href="{{ route('notifications.index') }}"
                                        class="block p-4 hover:bg-gray-50 transition border-b border-gray-100">
                                        <div class="flex items-start gap-3">
                                            <div class="shrink-0">
                                                @if($notification->jenis_notifikasi == 'vaksin')
                                                    <div
                                                        class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                    </div>
                                                @elseif($notification->jenis_notifikasi == 'kesehatan')
                                                    <div
                                                        class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
                                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                @elseif($notification->jenis_notifikasi == 'reproduksi')
                                                    <div
                                                        class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                @elseif($notification->jenis_notifikasi == 'langganan')
                                                    <div
                                                        class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                    </div>
                                                @else
                                                    <div
                                                        class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center">
                                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p
                                                    class="text-sm {{ $notification->status == 'belum_dibaca' ? 'font-semibold text-gray-900' : 'text-gray-600' }}">
                                                    {{ Str::limit($notification->pesan, 60) }}
                                                </p>
                                                <p class="text-xs text-gray-400 mt-1">
                                                    {{ $notification->tanggal_kirim->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="p-8 text-center text-gray-400">
                                        <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                            </path>
                                        </svg>
                                        <p class="text-sm">Tidak ada notifikasi</p>
                                    </div>
                                @endforelse
                            </div>
                            @if($notificationCount > 0)
                                <div class="p-3 border-t border-gray-200 text-center">
                                    <a href="{{ route('notifications.index') }}"
                                        class="text-sm text-blue-600 hover:text-blue-700 font-medium">Lihat Semua
                                        Notifikasi</a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- User Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" class="flex items-center gap-3 hover:opacity-80 transition">
                            @if(Auth::user()->avatar_url)
                                <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}"
                                    class="h-10 w-10 rounded-full object-cover shadow-sm border-2 border-white">
                            @else
                                <div
                                    class="h-10 w-10 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white font-bold text-sm shadow-sm cursor-pointer hover:shadow-md transition border-2 border-white">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', Auth::user()->name)[1] ?? '', 0, 1)) }}
                                </div>
                            @endif
                        </button>

                        <!-- Profile Dropdown -->
                        <div x-show="open" x-transition
                            class="absolute right-0 mt-3 w-64 bg-white rounded-lg shadow-xl border border-gray-200 z-50"
                            style="display: none;">
                            <div class="p-4 border-b border-gray-200">
                                <p class="font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                                <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                                @if(Auth::user()->farm_name)
                                    <p class="text-xs text-gray-400 mt-1">{{ Auth::user()->farm_name }}</p>
                                @endif
                            </div>
                            <div class="py-2">
                                <a href="{{ route('settings.index') }}"
                                    class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                    Profil Saya
                                </a>
                                <a href="{{ route('settings.index') }}"
                                    class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Pengaturan
                                </a>
                            </div>
                            <div class="border-t border-gray-200">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                            </path>
                                        </svg>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="p-8 space-y-8 bg-gray-100/50">
                @yield('content')
            </div>

            <div class="h-10 lg:hidden shrink-0"></div>

        </main>

        <!-- AI Assistant Floating Button & Modal -->
        <div x-data="{
            open: false,
            messages: [],
            userInput: '',
            isLoading: false,

            // Initialize - load messages from localStorage
            init() {
                const saved = localStorage.getItem('farmgo_chat_messages');
                if (saved) {
                    try {
                        this.messages = JSON.parse(saved);
                    } catch (e) {
                        this.messages = [];
                    }
                }
            },

            // Save messages to localStorage
            saveMessages() {
                localStorage.setItem('farmgo_chat_messages', JSON.stringify(this.messages));
            },

            // Clear chat history
            clearChat() {
                this.messages = [];
                localStorage.removeItem('farmgo_chat_messages');
            },

            // Convert basic markdown to HTML
            renderMarkdown(text) {
                return text
                    .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
                    .replace(/\*(.+?)\*/g, '<em>$1</em>')
                    .replace(/\n/g, '<br>');
            },

            async sendMessage() {
                if (!this.userInput.trim() || this.isLoading) return;

                const message = this.userInput.trim();
                this.messages.push({ role: 'user', content: message });
                this.saveMessages();
                this.userInput = '';
                this.isLoading = true;

                // Auto scroll to bottom
                this.$nextTick(() => {
                    const container = this.$refs.messagesContainer;
                    if (container) container.scrollTop = container.scrollHeight;
                });

            try {
            const response = await fetch('{{ route('ai-assistant.chat') }}', {
            method: 'POST',
            headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message })
            });

            const data = await response.json();

            if (data.success) {
                this.messages.push({ role: 'assistant', content: data.message });
                this.saveMessages();
            } else {
                this.messages.push({ role: 'assistant', content: data.message || 'Terjadi kesalahan.' });
                this.saveMessages();
            }
            } catch (error) {
                this.messages.push({ role: 'assistant', content: 'Maaf, terjadi kesalahan koneksi.' });
                this.saveMessages();
            } finally {
                this.isLoading = false;
                this.$nextTick(() => {
                    const container = this.$refs.messagesContainer;
                    if (container) container.scrollTop = container.scrollHeight;
                });
            }
            }
            }" class="fixed bottom-6 right-6 z-50">

            <!-- Floating Chat Button -->
            <button @click="open = !open" x-show="!open"
                class="bg-gradient-to-br from-emerald-500 to-emerald-600 text-white rounded-full p-4 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-110 group">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                    </path>
                </svg>
            </button>

            <!-- Chat Modal -->
            <div x-show="open" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-90"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-90"
                class="bg-white rounded-2xl shadow-2xl w-96 h-[600px] flex flex-col overflow-hidden"
                style="display: none;">

                <!-- Header -->
                <div
                    class="bg-gradient-to-r from-emerald-500 to-emerald-600 text-white p-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="bg-white/20 rounded-full p-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg">FarmGo Assistant</h3>
                            <p class="text-xs text-emerald-100">Asisten Virtual Anda</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="clearChat()" class="hover:bg-white/20 rounded-full p-1 transition"
                            title="Hapus Riwayat Chat">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                        </button>
                        <button @click="open = false" class="hover:bg-white/20 rounded-full p-1 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Messages Container -->
                <div x-ref="messagesContainer" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50">
                    <!-- Welcome Message -->
                    <div x-show="messages.length === 0" class="text-center py-8">
                        <div
                            class="bg-emerald-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                        <p class="text-gray-600 font-medium mb-2">Halo! Saya FarmGo Assistant</p>
                        <p class="text-sm text-gray-500">Tanyakan apa saja tentang peternakan atau fitur FarmGo</p>
                    </div>

                    <!-- Messages -->
                    <template x-for="(msg, index) in messages" :key="index">
                        <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                            <div
                                :class="msg.role === 'user'
                                ? 'bg-emerald-500 text-white rounded-2xl rounded-tr-sm px-4 py-2 max-w-[80%]'
                                : 'bg-white text-gray-800 rounded-2xl rounded-tl-sm px-4 py-2 max-w-[80%] shadow-sm border border-gray-100'">
                                <div class="text-sm" x-html="renderMarkdown(msg.content)"></div>
                            </div>
                        </div>
                    </template>

                    <!-- Loading Indicator -->
                    <div x-show="isLoading" class="flex justify-start">
                        <div
                            class="bg-white text-gray-800 rounded-2xl rounded-tl-sm px-4 py-3 shadow-sm border border-gray-100">
                            <div class="flex gap-1">
                                <div class="w-2 h-2 bg-emerald-500 rounded-full animate-bounce"
                                    style="animation-delay: 0ms"></div>
                                <div class="w-2 h-2 bg-emerald-500 rounded-full animate-bounce"
                                    style="animation-delay: 150ms"></div>
                                <div class="w-2 h-2 bg-emerald-500 rounded-full animate-bounce"
                                    style="animation-delay: 300ms"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Input Area -->
                <div class="p-4 bg-white border-t border-gray-200">
                    <form @submit.prevent="sendMessage()" class="flex gap-2">
                        <input x-model="userInput" type="text" placeholder="Ketik pertanyaan Anda..."
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                            :disabled="isLoading">
                        <button type="submit" :disabled="!userInput.trim() || isLoading"
                            class="bg-emerald-500 text-white rounded-full p-2 hover:bg-emerald-600 transition disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const sidebarText = document.getElementById('sidebar-text');
        const navTexts = document.querySelectorAll('.nav-text');
        const collapseIcon = document.getElementById('collapse-icon');
        const expandIcon = document.getElementById('expand-icon');

        // Check localStorage for sidebar state on load
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            collapseSidebar();
        }

        function toggleMobileSidebar() {
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }

        function toggleDesktopSidebar() {
            if (sidebar.classList.contains('lg:w-20')) {
                expandSidebar();
            } else {
                collapseSidebar();
            }
        }

        function collapseSidebar() {
            sidebar.classList.remove('lg:w-64');
            sidebar.classList.add('lg:w-20');

            // Hide text elements
            if (sidebarText) {
                sidebarText.classList.add('lg:hidden');
            }
            navTexts.forEach(text => text.classList.add('lg:hidden'));

            // Toggle icons: hide collapse, show expand
            collapseIcon.classList.add('hidden');
            expandIcon.classList.remove('hidden');

            localStorage.setItem('sidebarCollapsed', 'true');
        }

        function expandSidebar() {
            sidebar.classList.add('lg:w-64');
            sidebar.classList.remove('lg:w-20');

            // Show text elements
            if (sidebarText) {
                sidebarText.classList.remove('lg:hidden');
            }
            navTexts.forEach(text => text.classList.remove('lg:hidden'));

            // Toggle icons: show collapse, hide expand
            collapseIcon.classList.remove('hidden');
            expandIcon.classList.add('hidden');

            localStorage.setItem('sidebarCollapsed', 'false');
        }
    </script>
    @stack('scripts')
</body>

</html>