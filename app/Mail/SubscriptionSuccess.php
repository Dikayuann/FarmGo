<?php

namespace App\Mail;

use App\Models\Langganan;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionSuccess extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $subscription;
    public $transaction;

    /**
     * Create a new message instance.
     */
    public function __construct($user, Langganan $subscription, Transaction $transaction)
    {
        $this->user = $user;
        $this->subscription = $subscription;
        $this->transaction = $transaction;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $packageName = $this->subscription->paket_langganan === 'premium_monthly'
            ? 'Premium Bulanan'
            : 'Premium Tahunan';

        return $this->subject('🎉 Langganan FarmGo ' . $packageName . ' Berhasil!')
            ->view('emails.subscription-success');
    }
}
