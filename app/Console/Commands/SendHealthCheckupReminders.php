<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\HealthRecord;
use App\Models\Notifikasi;
use Carbon\Carbon;

class SendHealthCheckupReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'health:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications for upcoming health checkups';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for upcoming health checkups...');

        $today = Carbon::today();
        $threeDaysFromNow = Carbon::today()->addDays(3);
        $tomorrow = Carbon::today()->addDay();

        // Get health records with upcoming checkups
        $upcomingCheckups = HealthRecord::with('animal')
            ->whereNotNull('pemeriksaan_berikutnya')
            ->where('pemeriksaan_berikutnya', '>=', $today)
            ->where('pemeriksaan_berikutnya', '<=', $threeDaysFromNow)
            ->get();

        $notificationsSent = 0;

        foreach ($upcomingCheckups as $record) {
            $checkupDate = Carbon::parse($record->pemeriksaan_berikutnya);
            $daysUntil = $today->diffInDays($checkupDate);

            // Determine notification type based on days until checkup
            $notificationType = 'pemeriksaan_berikutnya';

            // Check if notification already sent for this record and date
            $existingNotification = Notifikasi::where('animal_id', $record->animal_id)
                ->where('jenis_notifikasi', $notificationType)
                ->whereDate('tanggal_kirim', $today)
                ->where('pesan', 'like', '%' . $checkupDate->format('d/m/Y') . '%')
                ->first();

            if ($existingNotification) {
                continue; // Skip if already notified today
            }

            // Create notification message based on urgency
            if ($daysUntil == 0) {
                $message = "📅 Hari Ini! Pemeriksaan kesehatan untuk {$record->animal->kode_hewan} - {$record->animal->nama_hewan} dijadwalkan hari ini ({$checkupDate->format('d/m/Y')}). Jangan lupa untuk melakukan pemeriksaan.";
                $urgency = 'tinggi';
            } elseif ($daysUntil == 1) {
                $message = "⏰ Besok! Pemeriksaan kesehatan untuk {$record->animal->kode_hewan} - {$record->animal->nama_hewan} dijadwalkan besok ({$checkupDate->format('d/m/Y')}). Persiapkan pemeriksaan.";
                $urgency = 'sedang';
            } else {
                $message = "📋 Pengingat: Pemeriksaan kesehatan untuk {$record->animal->kode_hewan} - {$record->animal->nama_hewan} dijadwalkan dalam {$daysUntil} hari ({$checkupDate->format('d/m/Y')}).";
                $urgency = 'rendah';
            }

            // Create notification
            Notifikasi::create([
                'user_id' => $record->animal->user_id,
                'animal_id' => $record->animal_id,
                'perkawinan_id' => null,
                'jenis_notifikasi' => $notificationType,
                'pesan' => $message,
                'tanggal_kirim' => now(),
                'status' => 'belum_dibaca',
            ]);

            $notificationsSent++;
            $this->info("Notification sent for {$record->animal->kode_hewan} - checkup in {$daysUntil} day(s)");
        }

        $this->info("Total notifications sent: {$notificationsSent}");

        return Command::SUCCESS;
    }
}
