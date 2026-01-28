<?php

namespace App\Jobs;

use App\Mail\SubscriptionSuccess;
use App\Models\Langganan;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendSubscriptionEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $subscription;
    public $transaction;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, Langganan $subscription, Transaction $transaction)
    {
        $this->user = $user;
        $this->subscription = $subscription;
        $this->transaction = $transaction;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->user->email)->send(
                new SubscriptionSuccess($this->user, $this->subscription, $this->transaction)
            );

            Log::info('Subscription email sent successfully', [
                'user_id' => $this->user->id,
                'order_id' => $this->transaction->order_id,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send subscription email', [
                'user_id' => $this->user->id,
                'order_id' => $this->transaction->order_id,
                'error' => $e->getMessage(),
            ]);

            // Re-throw to trigger retry
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Subscription email job failed after all retries', [
            'user_id' => $this->user->id,
            'order_id' => $this->transaction->order_id,
            'error' => $exception->getMessage(),
        ]);
    }
}
