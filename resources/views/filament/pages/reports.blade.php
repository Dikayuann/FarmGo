<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Overview Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Total Users</p>
                        <p class="text-3xl font-bold mt-2">{{ \App\Models\User::count() }}</p>
                    </div>
                    <x-filament::icon
                        icon="heroicon-o-users"
                        class="w-12 h-12 opacity-50"
                    />
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Total Hewan</p>
                        <p class="text-3xl font-bold mt-2">{{ \App\Models\Animal::count() }}</p>
                    </div>
                    <x-filament::icon
                        icon="heroicon-o-rectangle-stack"
                        class="w-12 h-12 opacity-50"
                    />
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Langganan Aktif</p>
                        <p class="text-3xl font-bold mt-2">{{ \App\Models\Langganan::where('status', 'aktif')->count() }}</p>
                    </div>
                    <x-filament::icon
                        icon="heroicon-o-credit-card"
                        class="w-12 h-12 opacity-50"
                    />
                </div>
            </div>

            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Notifikasi</p>
                        <p class="text-3xl font-bold mt-2">{{ \App\Models\Notifikasi::count() }}</p>
                    </div>
                    <x-filament::icon
                        icon="heroicon-o-bell"
                        class="w-12 h-12 opacity-50"
                    />
                </div>
            </div>
        </div>

        {{-- Export Options --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Laporan Hewan --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    📊 Laporan Data Hewan
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    Export data semua hewan termasuk informasi lengkap (kode, nama, jenis, ras, tanggal lahir, berat, status).
                </p>
                <div class="space-y-2">
                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                        <x-filament::icon icon="heroicon-o-check-circle" class="w-4 h-4 mr-2 text-green-500" />
                        Kode & Nama Hewan
                    </div>
                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                        <x-filament::icon icon="heroicon-o-check-circle" class="w-4 h-4 mr-2 text-green-500" />
                        Jenis & Ras
                    </div>
                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                        <x-filament::icon icon="heroicon-o-check-circle" class="w-4 h-4 mr-2 text-green-500" />
                        Data Lengkap & Pemilik
                    </div>
                </div>
            </div>

            {{-- Laporan Lengkap --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    📋 Laporan Komprehensif
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    Export semua data sistem dalam satu file Excel dengan multiple sheets (Hewan, Vaksinasi, Reproduksi, Kesehatan).
                </p>
                <div class="space-y-2">
                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                        <x-filament::icon icon="heroicon-o-check-circle" class="w-4 h-4 mr-2 text-green-500" />
                        Multiple Sheets
                    </div>
                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                        <x-filament::icon icon="heroicon-o-check-circle" class="w-4 h-4 mr-2 text-green-500" />
                        Data Lengkap
                    </div>
                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                        <x-filament::icon icon="heroicon-o-check-circle" class="w-4 h-4 mr-2 text-green-500" />
                        Siap Print
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistics by Type --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                Statistik Hewan per Jenis
            </h3>

            @php
                $stats = \App\Models\Animal::selectRaw('jenis_hewan, COUNT(*) as total')
                    ->groupBy('jenis_hewan')
                    ->get();
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($stats as $stat)
                    <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600 dark:text-gray-400">{{ ucfirst($stat->jenis_hewan) }}</span>
                            <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stat->total }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                Aktivitas Terbaru
            </h3>

            @php
                $recentAnimals = \App\Models\Animal::latest()->take(5)->get();
            @endphp

            <div class="space-y-3">
                @foreach($recentAnimals as $animal)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div>
                            <div class="font-medium text-gray-900 dark:text-white">{{ $animal->nama_hewan }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ ucfirst($animal->jenis_hewan) }} • Ditambahkan {{ $animal->created_at->diffForHumans() }}
                            </div>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full
                            @if($animal->status_ternak === 'sehat') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @elseif($animal->status_ternak === 'sakit') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                            @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                            @endif">
                            {{ ucfirst($animal->status_ternak) }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-filament-panels::page>

