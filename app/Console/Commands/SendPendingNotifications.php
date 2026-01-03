<?php

namespace App\Console\Commands;

use App\Models\Notifikasi;
use Illuminate\Console\Command;

class SendPendingNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:send-pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send all pending notifications that are due';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for pending notifications...');

        // Get all pending notifications where tanggal_kirim is now or in the past
        $pendingNotifications = Notifikasi::where('status', 'pending')
            ->where('tanggal_kirim', '<=', now())
            ->get();

        if ($pendingNotifications->isEmpty()) {
            $this->info('No pending notifications to send.');
            return 0;
        }

        $count = 0;
        foreach ($pendingNotifications as $notification) {
            // Update status to belum_dibaca (sent but not read yet)
            $notification->update([
                'status' => 'belum_dibaca',
            ]);

            $count++;

            $this->line("✓ Sent notification #{$notification->id} to user #{$notification->user_id}");
        }

        $this->info("Successfully sent {$count} notification(s).");
        return 0;
    }
}

