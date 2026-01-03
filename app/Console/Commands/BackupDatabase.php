<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup {--name= : Custom backup filename}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup the database to storage/app/backups';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filename = $this->option('name') ?: 'backup-' . Carbon::now()->format('Y-m-d-His') . '.sql';
        $path = storage_path('app/backups');

        // Create directory if not exists
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');
        $dbHost = config('database.connections.mysql.host');

        $fullPath = $path . '/' . $filename;

        $this->info('Starting database backup...');
        $this->info("Database: {$dbName}");
        $this->info("File: {$filename}");

        // Use mysqldump
        if ($dbPass) {
            $command = sprintf(
                'mysqldump -h%s -u%s -p%s %s > %s 2>&1',
                escapeshellarg($dbHost),
                escapeshellarg($dbUser),
                escapeshellarg($dbPass),
                escapeshellarg($dbName),
                escapeshellarg($fullPath)
            );
        } else {
            $command = sprintf(
                'mysqldump -h%s -u%s %s > %s 2>&1',
                escapeshellarg($dbHost),
                escapeshellarg($dbUser),
                escapeshellarg($dbName),
                escapeshellarg($fullPath)
            );
        }

        exec($command, $output, $returnVar);

        if ($returnVar === 0 && file_exists($fullPath)) {
            $size = filesize($fullPath);
            $sizeInMB = round($size / 1024 / 1024, 2);

            $this->info('✓ Backup successful!');
            $this->info("Size: {$sizeInMB} MB");
            $this->info("Location: {$fullPath}");

            return Command::SUCCESS;
        } else {
            $this->error('✗ Backup failed!');
            if (!empty($output)) {
                $this->error('Output: ' . implode("\n", $output));
            }

            return Command::FAILURE;
        }
    }
}
