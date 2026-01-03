
<x-filament-panels::page>
    <div class="space-y-6">
        {{-- System Information Card --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                Informasi Sistem
            </h3>

            <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="border-l-4 border-blue-500 pl-4">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Laravel Version</dt>
                    <dd class="text-lg font-semibold text-gray-900 dark:text-white">{{ app()->version() }}</dd>
                </div>

                <div class="border-l-4 border-green-500 pl-4">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">PHP Version</dt>
                    <dd class="text-lg font-semibold text-gray-900 dark:text-white">{{ PHP_VERSION }}</dd>
                </div>

                <div class="border-l-4 border-purple-500 pl-4">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Database</dt>
                    <dd class="text-lg font-semibold text-gray-900 dark:text-white">{{ config('database.connections.mysql.database') }}</dd>
                </div>

                <div class="border-l-4 border-yellow-500 pl-4">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Environment</dt>
                    <dd class="text-lg font-semibold text-gray-900 dark:text-white">{{ app()->environment() }}</dd>
                </div>
            </dl>
        </div>

        {{-- Database Statistics --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                Statistik Database
            </h3>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                        {{ \App\Models\User::count() }}
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Users</div>
                </div>

                <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <div class="text-3xl font-bold text-green-600 dark:text-green-400">
                        {{ \App\Models\Animal::count() }}
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Hewan</div>
                </div>

                <div class="text-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                    <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">
                        {{ \App\Models\Langganan::count() }}
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Langganan</div>
                </div>

                <div class="text-center p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                    <div class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">
                        {{ \App\Models\Notifikasi::count() }}
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Notifikasi</div>
                </div>
            </div>
        </div>

        {{-- Recent Backups --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                Backup Terakhir
            </h3>

            @php
                $backupPath = storage_path('app/backups');
                $backups = [];
                if (file_exists($backupPath)) {
                    $files = glob($backupPath . '/*.sql');
                    rsort($files);
                    $backups = array_slice($files, 0, 5);
                }
            @endphp

            @if(count($backups) > 0)
                <div class="space-y-2">
                    @foreach($backups as $backup)
                        @php
                            $filename = basename($backup);
                            $size = filesize($backup);
                            $sizeInMB = round($size / 1024 / 1024, 2);
                            $date = filemtime($backup);
                        @endphp
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <x-filament::icon
                                    icon="heroicon-o-circle-stack"
                                    class="w-5 h-5 text-blue-500"
                                />
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $filename }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::createFromTimestamp($date)->diffForHumans() }} • {{ $sizeInMB }} MB
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('filament.admin.download-backup', ['file' => $filename]) }}"
                               class="text-blue-600 hover:text-blue-800 dark:text-blue-400 text-sm font-medium">
                                Download
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400 text-sm">Belum ada backup.</p>
            @endif
        </div>

        {{-- Quick Actions --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                Quick Actions
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="/admin/animals" class="block p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg hover:border-blue-500 dark:hover:border-blue-500 transition">
                    <div class="flex items-center space-x-3">
                        <x-filament::icon
                            icon="heroicon-o-rectangle-stack"
                            class="w-6 h-6 text-blue-500"
                        />
                        <span class="font-medium text-gray-900 dark:text-white">Kelola Hewan</span>
                    </div>
                </a>

                <a href="/admin/users" class="block p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg hover:border-green-500 dark:hover:border-green-500 transition">
                    <div class="flex items-center space-x-3">
                        <x-filament::icon
                            icon="heroicon-o-users"
                            class="w-6 h-6 text-green-500"
                        />
                        <span class="font-medium text-gray-900 dark:text-white">Kelola Users</span>
                    </div>
                </a>

                <a href="/admin/langganans" class="block p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg hover:border-purple-500 dark:hover:border-purple-500 transition">
                    <div class="flex items-center space-x-3">
                        <x-filament::icon
                            icon="heroicon-o-credit-card"
                            class="w-6 h-6 text-purple-500"
                        />
                        <span class="font-medium text-gray-900 dark:text-white">Kelola Langganan</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-filament-panels::page>

