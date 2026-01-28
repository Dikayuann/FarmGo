<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'langganan_id',
        'order_id',
        'transaction_id',
        'gross_amount',
        'payment_type',
        'payment_code',
        'bank',
        'status',
        'midtrans_response',
        'paid_at',
        'expired_at',
    ];

    protected $casts = [
        'midtrans_response' => 'array',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Langganan
     */
    public function langganan()
    {
        return $this->belongsTo(Langganan::class);
    }

    /**
     * Check if transaction is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if transaction is successful
     */
    public function isSuccess(): bool
    {
        return $this->status === 'settlement';
    }

    /**
     * Check if transaction is expired
     */
    public function isExpired(): bool
    {
        return $this->status === 'expire';
    }

    /**
     * Update transaction from Midtrans response
     */
    public function updateFromMidtrans($response)
    {
        // Ensure response is array
        if (is_object($response)) {
            $response = json_decode(json_encode($response), true);
        }

        $updateData = [
            'transaction_id' => $response['transaction_id'] ?? $this->transaction_id,
            'status' => $this->mapMidtransStatus($response['transaction_status'] ?? 'pending'),
            'payment_type' => $response['payment_type'] ?? $this->payment_type,
            'midtrans_response' => $response,
        ];

        // Extract payment-specific details
        if (isset($response['payment_type'])) {
            switch ($response['payment_type']) {
                case 'bank_transfer':
                    // Bank Transfer / VA
                    if (isset($response['va_numbers'][0])) {
                        $updateData['bank'] = $response['va_numbers'][0]['bank'] ?? null;
                        $updateData['payment_code'] = $response['va_numbers'][0]['va_number'] ?? null;
                    }
                    break;

                case 'echannel':
                    // Mandiri Bill
                    $updateData['bank'] = 'mandiri';
                    $updateData['payment_code'] = $response['bill_key'] ?? null;
                    break;

                case 'credit_card':
                    // Credit Card
                    $updateData['bank'] = $response['bank'] ?? null;
                    $updateData['payment_code'] = $response['masked_card'] ?? null;
                    break;

                case 'gopay':
                case 'shopeepay':
                    // E-Wallet
                    $updateData['payment_code'] = $response['transaction_id'] ?? null;
                    break;

                case 'qris':
                    // QRIS
                    $updateData['payment_code'] = $response['acquirer'] ?? $response['transaction_id'] ?? null;
                    break;
            }
        }

        $this->update($updateData);

        \Log::info('Transaction updated from Midtrans', [
            'order_id' => $this->order_id,
            'status' => $updateData['status'],
            'payment_type' => $updateData['payment_type'],
        ]);

        return $this;
    }

    /**
     * Map Midtrans transaction status to our status
     */
    protected function mapMidtransStatus($midtransStatus)
    {
        $statusMap = [
            'capture' => 'settlement',  // Credit card capture
            'settlement' => 'settlement',
            'pending' => 'pending',
            'deny' => 'failed',
            'expire' => 'expired',
            'cancel' => 'cancelled',
            'refund' => 'refunded',
            'partial_refund' => 'refunded',
        ];

        return $statusMap[$midtransStatus] ?? 'pending';
    }

    /**
     * Get formatted payment method name
     */
    public function getPaymentMethodAttribute()
    {
        $paymentType = $this->payment_type;
        $bank = strtoupper($this->bank ?? '');

        switch ($paymentType) {
            case 'bank_transfer':
                return $bank ? "{$bank} Virtual Account" : 'Virtual Account';

            case 'echannel':
                return 'Mandiri Bill Payment';

            case 'credit_card':
                return $bank ? "Kartu Kredit {$bank}" : 'Kartu Kredit';

            case 'gopay':
                return 'GoPay';

            case 'shopeepay':
                return 'ShopeePay';

            case 'qris':
                return 'QRIS';

            case 'cstore':
                return $bank ? ucfirst($bank) : 'Convenience Store';

            case 'akulaku':
                return 'Akulaku';

            default:
                return ucfirst(str_replace('_', ' ', $paymentType ?? 'Midtrans'));
        }
    }
}

