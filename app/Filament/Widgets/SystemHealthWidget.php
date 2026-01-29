<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SystemHealthWidget extends BaseWidget
{
    protected static ?int $sort = 8;

    protected function getStats(): array
    {
        // Database size (approximate)
        $databaseSize = $this->getDatabaseSize();

        // Storage usage
        $storageUsed = $this->getStorageUsage();

        // Total records in database
        $totalRecords = $this->getTotalRecords();

        return [
            Stat::make('Database Size', $databaseSize)
                ->description('Total database storage')
                ->descriptionIcon('heroicon-m-circle-stack')
                ->color('info'),

            Stat::make('Storage Used', $storageUsed)
                ->description('Public storage (avatars, etc)')
                ->descriptionIcon('heroicon-m-folder')
                ->color('warning'),

            Stat::make('Total Records', number_format($totalRecords))
                ->description('All database records')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success'),
        ];
    }

    private function getDatabaseSize(): string
    {
        try {
            $database = config('database.connections.mysql.database');

            $size = DB::select("
                SELECT 
                    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
                FROM information_schema.TABLES
                WHERE table_schema = ?
            ", [$database]);

            $sizeMB = $size[0]->size_mb ?? 0;

            if ($sizeMB >= 1024) {
                return number_format($sizeMB / 1024, 2) . ' GB';
            }

            return number_format($sizeMB, 2) . ' MB';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    private function getStorageUsage(): string
    {
        try {
            $publicPath = storage_path('app/public');

            if (!is_dir($publicPath)) {
                return '0 MB';
            }

            $size = 0;
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($publicPath, \RecursiveDirectoryIterator::SKIP_DOTS)
            );

            foreach ($files as $file) {
                if ($file->isFile()) {
                    $size += $file->getSize();
                }
            }

            $sizeMB = $size / 1024 / 1024;

            if ($sizeMB >= 1024) {
                return number_format($sizeMB / 1024, 2) . ' GB';
            }

            return number_format($sizeMB, 2) . ' MB';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    private function getTotalRecords(): int
    {
        try {
            $database = config('database.connections.mysql.database');

            $result = DB::select("
                SELECT 
                    SUM(table_rows) AS total_rows
                FROM information_schema.TABLES
                WHERE table_schema = ?
            ", [$database]);

            return (int) ($result[0]->total_rows ?? 0);
        } catch (\Exception $e) {
            return 0;
        }
    }
}
