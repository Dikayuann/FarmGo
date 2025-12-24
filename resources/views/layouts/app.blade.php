<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FarmGo')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
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
                <div class="flex items-center gap-2 text-green-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                    <span id="sidebar-text"
                        class="text-xl font-bold tracking-wide text-green-600 whitespace-nowrap">FarmGo</span>
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
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                        </path>
                    </svg>
                    <span class="nav-text whitespace-nowrap ml-3">Manajemen Ternak</span>
                </a>

                <a href="{{ route('kesehatan.index') }}" title="Monitoring Kesehatan"
                    class="nav-item flex items-center px-4 py-2.5 rounded-lg transition group relative tooltip-trigger
                    {{ request()->routeIs('kesehatan*') ? 'bg-emerald-600 text-white font-semibold shadow-md' : 'text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 font-medium' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                        </path>
                    </svg>
                    <span class="nav-text whitespace-nowrap ml-3">Monitoring Kesehatan</span>
                </a>

                <a href="#" title="Catatan Reproduksi"
                    class="nav-item flex items-center px-4 py-2.5 text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 rounded-lg transition group relative font-medium tooltip-trigger">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                    <span class="nav-text whitespace-nowrap ml-3">Catatan Reproduksi</span>
                </a>

                <a href="#" title="Ekspor Data"
                    class="nav-item flex items-center px-4 py-2.5 text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 rounded-lg transition group relative font-medium tooltip-trigger">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    <span class="nav-text whitespace-nowrap ml-3">Ekspor Data</span>
                </a>

                <a href="#" title="Harga / Langganan"
                    class="nav-item flex items-center px-4 py-2.5 text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 rounded-lg transition group relative font-medium tooltip-trigger">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                        </path>
                    </svg>
                    <span class="nav-text whitespace-nowrap ml-3">Harga / Langganan</span>
                </a>

                <div class="pt-3 mt-3 border-t border-gray-200">
                    <a href="#" title="Pengaturan"
                        class="nav-item flex items-center px-4 py-2.5 text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 rounded-lg transition group relative font-medium tooltip-trigger">
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
                            @php
                                $notificationCount = Auth::user()->notifications()->where('status', '!=', 'dibaca')->count();
                            @endphp
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
                                @forelse(Auth::user()->notifications()->latest()->take(5)->get() as $notification)
                                    <a href="#" class="block p-4 hover:bg-gray-50 transition border-b border-gray-100">
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
                                                @else
                                                    <div
                                                        class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
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
                                                    class="text-sm {{ $notification->status != 'dibaca' ? 'font-semibold text-gray-900' : 'text-gray-600' }}">
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
                                    <a href="#" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Lihat Semua
                                        Notifikasi</a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- User Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" class="flex items-center gap-3 hover:opacity-80 transition">
                            @if(Auth::user()->profile_photo_url ?? false)
                                <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}"
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
                                <a href="#"
                                    class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                    Profil Saya
                                </a>
                                <a href="#"
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
            sidebarText.classList.add('lg:hidden');
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
            sidebarText.classList.remove('lg:hidden');
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