<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Perkawinan;
use App\Models\Notifikasi;
use Carbon\Carbon;

class SendBirthReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'birth:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications for upcoming animal births';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for upcoming births...');

        $today = Carbon::today();
        $sevenDaysFromNow = Carbon::today()->addDays(7);

        // Get reproduction records with upcoming births (status bunting)
        $upcomingBirths = Perkawinan::with(['betina'])
            ->where('status_reproduksi', 'bunting')
            ->whereNotNull('estimasi_kelahiran')
            ->where('estimasi_kelahiran', '>=', $today)
            ->where('estimasi_kelahiran', '<=', $sevenDaysFromNow)
            ->get();

        $notificationsSent = 0;

        foreach ($upcomingBirths as $perkawinan) {
            $birthDate = Carbon::parse($perkawinan->estimasi_kelahiran);
            $daysUntil = $today->diffInDays($birthDate);

            // Determine notification message based on days until birth
            if ($daysUntil == 0) {
                $message = "🚨 Hari Ini! {$perkawinan->betina->kode_hewan} - {$perkawinan->betina->nama_hewan} diperkirakan akan melahirkan hari ini. Pastikan persiapan kelahiran sudah siap.";
            } elseif ($daysUntil == 1) {
                $message = "⏰ Besok! {$perkawinan->betina->kode_hewan} - {$perkawinan->betina->nama_hewan} diperkirakan akan melahirkan besok ({$birthDate->format('d M Y')}). Persiapkan kelahiran.";
            } elseif ($daysUntil == 3) {
                $message = "📅 3 Hari Lagi: {$perkawinan->betina->kode_hewan} - {$perkawinan->betina->nama_hewan} diperkirakan akan melahirkan pada {$birthDate->format('d M Y')}. Mulai persiapkan kelahiran.";
            } elseif ($daysUntil == 7) {
                $message = "📋 7 Hari Lagi: {$perkawinan->betina->kode_hewan} - {$perkawinan->betina->nama_hewan} diperkirakan akan melahirkan pada {$birthDate->format('d M Y')}.";
            } else {
                continue; // Skip other days
            }

            // Check if notification already sent for this perkawinan and date
            $existingNotification = Notifikasi::where('perkawinan_id', $perkawinan->id)
                ->where('jenis_notifikasi', 'reproduksi')
                ->whereDate('tanggal_kirim', $today)
                ->where('pesan', 'like', '%' . $birthDate->format('d M Y') . '%')
                ->first();

            if ($existingNotification) {
                continue; // Skip if already notified today
            }

            // Create notification
            Notifikasi::create([
                'user_id' => $perkawinan->betina->user_id,
                'animal_id' => $perkawinan->betina_id,
                'perkawinan_id' => $perkawinan->id,
                'jenis_notifikasi' => 'reproduksi',
                'pesan' => $message,
                'tanggal_kirim' => now(),
                'status' => 'belum_dibaca',
            ]);

            $notificationsSent++;
            $this->info("Notification sent for {$perkawinan->betina->kode_hewan} - birth in {$daysUntil} day(s)");
        }

        $this->info("Total notifications sent: {$notificationsSent}");

        return Command::SUCCESS;
    }
}
