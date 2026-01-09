<?php

namespace App\Http\Controllers;

use App\Models\Langganan;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class LanggananController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Display subscription page
     */
    public function index()
    {
        $user = Auth::user();

        // Get active subscription if exists
        $activeSubscription = Langganan::where('user_id', $user->id)
            ->active()
            ->first();

        // Get pending transactions
        $pendingTransaction = Transaction::where('user_id', $user->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        return view('langganan', compact('user', 'activeSubscription', 'pendingTransaction'));
    }

    /**
     * Show checkout page for selected package
     */
    public function showCheckout($package)
    {
        $user = Auth::user();

        // Validate package
        if (!in_array($package, ['trial', 'premium_monthly', 'premium_yearly'])) {
            return redirect()->route('langganan')->with('error', 'Paket tidak valid');
        }

        // Define package details
        $packageDetails = [
            'trial' => [
                'name' => 'Trial Gratis 7 Hari',
                'price' => 0,
                'duration' => 7,
                'duration_unit' => 'hari',
                'features' => [
                    'Maksimal 10 ternak',
                    'Catatan kesehatan dasar',
                    'QR Code untuk setiap ternak',
                    'Tracking reproduksi (max 5)',
                    'Deteksi birahi',
                    'AI Assistant',
                    'Akses penuh 7 hari',
                ],
            ],
            'premium_monthly' => [
                'name' => 'FarmGo Premium - Bulanan',
                'price' => 50000,
                'duration' => 1,
                'duration_unit' => 'bulan',
                'features' => [
                    'Unlimited jumlah ternak',
                    'Unlimited monitoring kesehatan',
                    'Unlimited catatan reproduksi',
                    'Ekspor data lengkap',
                    'Support prioritas',
                ],
            ],
            'premium_yearly' => [
                'name' => 'FarmGo Premium - Tahunan',
                'price' => 500000,
                'duration' => 12,
                'duration_unit' => 'bulan',
                'savings' => 100000,
                'features' => [
                    'Semua fitur Premium Bulanan',
                    'Hemat Rp 100.000 per tahun',
                    'Tanpa khawatir perpanjangan',
                    'Prioritas support tertinggi',
                    'Akses early features',
                ],
            ],
        ];

        $details = $packageDetails[$package];
        $details['package_type'] = $package;

        return view('langganan.checkout', compact('user', 'details'));
    }

    /**
     * Create payment and get Snap token
     * Enhanced flow: Only create transaction, langganan created after payment success
     */
    public function createPayment(Request $request)
    {
        $request->validate([
            'package' => 'required|in:trial,premium_monthly,premium_yearly',
        ]);

        $user = Auth::user();
        $package = $request->package;

        // Handle trial activation
        if ($package === 'trial') {
            // Check if user already used trial
            if ($user->trial_ends_at) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah pernah menggunakan  trial period',
                ], 400);
            }

            $user->startTrial(7); // 7 days trial

            // Create notification for trial activation
            \App\Models\Notifikasi::create([
                'user_id' => $user->id,
                'animal_id' => null,
                'perkawinan_id' => null,
                'jenis_notifikasi' => 'langganan',
                'pesan' => '🎉 Selamat! Trial 7 hari Anda telah aktif. Nikmati semua fitur FarmGo secara gratis hingga ' . $user->trial_ends_at->format('d M Y') . '!',
                'tanggal_kirim' => now(),
                'status' => 'belum_dibaca',
            ]);

            return response()->json([
                'success' => true,
                'is_trial' => true,
                'message' => 'Trial berhasil diaktifkan',
            ]);
        }

        // Define package details for premium
        $packageDetails = [
            'premium_monthly' => [
                'name' => 'FarmGo Premium - Bulanan',
                'price' => 50000,
                'duration' => 1, // months
            ],
            'premium_yearly' => [
                'name' => 'FarmGo Premium - Tahunan',
                'price' => 500000,
                'duration' => 12, // months
            ],
        ];

        $details = $packageDetails[$package];

        try {
            DB::beginTransaction();

            // Create transaction record ONLY (no langganan yet)
            $orderId = 'FRMGO-' . time() . '-' . $user->id;
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'langganan_id' => null, // Will be set after payment success
                'order_id' => $orderId,
                'gross_amount' => $details['price'],
                'status' => 'pending',
                'expired_at' => now()->addHours(24), // 24 hour expiry
            ]);

            // Store package info in transaction for later
            $transaction->update([
                'midtrans_response' => [
                    'package_type' => $package,
                    'package_duration' => $details['duration'],
                ],
            ]);

            // Prepare Midtrans transaction params
            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $details['price'],
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone ?? '',
                ],
                'item_details' => [
                    [
                        'id' => $package,
                        'price' => $details['price'],
                        'quantity' => 1,
                        'name' => $details['name'],
                    ],
                ],
                'callbacks' => [
                    'finish' => route('langganan.pending', $orderId),
                ],
                'expiry' => [
                    'unit' => 'hour',
                    'duration' => 24,
                ],
            ];

            // Get Snap token
            $snapToken = Snap::getSnapToken($params);

            DB::commit();

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'order_id' => $orderId,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat transaksi: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Handle Midtrans callback notification with security validation
     * Create langganan only after successful payment
     */
    public function handleCallback(Request $request)
    {
        try {
            // Get notification data from Midtrans
            $notification = new Notification();

            // SECURITY: Validate signature key
            $orderId = $notification->order_id;
            $statusCode = $notification->status_code;
            $grossAmount = $notification->gross_amount;
            $serverKey = config('midtrans.server_key');

            $signatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

            if ($signatureKey !== $notification->signature_key) {
                \Log::warning('Midtrans webhook: Invalid signature', [
                    'order_id' => $orderId,
                    'expected' => $signatureKey,
                    'received' => $notification->signature_key,
                ]);
                return response()->json(['message' => 'Invalid signature key'], 403);
            }

            // Find transaction - CHECK EXISTENCE
            $transaction = Transaction::where('order_id', $orderId)->first();

            if (!$transaction) {
                \Log::error('Midtrans webhook: Transaction not found', [
                    'order_id' => $orderId,
                ]);
                return response()->json(['message' => 'Transaction not found'], 404);
            }

            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status ?? null;

            // Log webhook received
            \Log::info('Midtrans webhook received', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'payment_type' => $notification->payment_type ?? null,
            ]);

            // Update transaction with Midtrans response
            $transaction->updateFromMidtrans($notification->getResponse());

            // HANDLE ALL TRANSACTION STATUSES
            switch ($transactionStatus) {
                case 'capture':
                    // Credit card capture
                    if ($fraudStatus == 'accept') {
                        $this->activateSubscriptionFromTransaction($transaction);
                        \Log::info('Subscription activated from capture', ['order_id' => $orderId]);
                    } elseif ($fraudStatus == 'challenge') {
                        // Transaction is challenged by FDS, wait for manual review
                        \Log::warning('Transaction challenged by FDS', ['order_id' => $orderId]);
                    } elseif ($fraudStatus == 'deny') {
                        // Transaction denied by FDS
                        if ($transaction->langganan) {
                            $transaction->langganan->update(['status' => 'dibatalkan']);
                        }
                        \Log::warning('Transaction denied by FDS', ['order_id' => $orderId]);
                    }
                    break;

                case 'settlement':
                    // Payment settled
                    $this->activateSubscriptionFromTransaction($transaction);
                    \Log::info('Subscription activated from settlement', ['order_id' => $orderId]);
                    break;

                case 'pending':
                    // Payment pending (waiting for user to complete)
                    \Log::info('Transaction pending', ['order_id' => $orderId]);
                    break;

                case 'deny':
                    // Payment denied
                    if ($transaction->langganan) {
                        $transaction->langganan->update(['status' => 'dibatalkan']);
                    }
                    \Log::warning('Transaction denied', ['order_id' => $orderId]);
                    break;

                case 'expire':
                    // Transaction expired (not paid within time limit)
                    if ($transaction->langganan) {
                        $transaction->langganan->update(['status' => 'dibatalkan']);
                    }
                    \Log::info('Transaction expired', ['order_id' => $orderId]);
                    break;

                case 'cancel':
                    // Transaction canceled by user
                    if ($transaction->langganan) {
                        $transaction->langganan->update(['status' => 'dibatalkan']);
                    }
                    \Log::info('Transaction canceled', ['order_id' => $orderId]);
                    break;

                case 'refund':
                case 'partial_refund':
                    // Transaction refunded
                    if ($transaction->langganan) {
                        $transaction->langganan->update(['status' => 'dibatalkan']);
                        // Downgrade user if was premium
                        $transaction->langganan->user->update([
                            'role' => \App\Models\User::ROLE_TRIAL,
                            'status_langganan' => 'trial',
                        ]);
                    }
                    \Log::info('Transaction refunded', ['order_id' => $orderId]);
                    break;

                default:
                    \Log::warning('Unknown transaction status', [
                        'order_id' => $orderId,
                        'status' => $transactionStatus,
                    ]);
                    break;
            }

            // Return 200 OK to acknowledge receipt
            return response()->json([
                'message' => 'Webhook processed successfully',
                'order_id' => $orderId,
                'status' => $transactionStatus,
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Midtrans webhook error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Webhook processing failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Activate subscription from successful transaction
     */
    protected function activateSubscriptionFromTransaction($transaction)
    {
        // Skip if already has langganan
        if ($transaction->langganan_id) {
            \Log::info('Subscription already activated', [
                'order_id' => $transaction->order_id,
                'langganan_id' => $transaction->langganan_id,
            ]);
            return;
        }

        // Get package info from midtrans_response
        $response = $transaction->midtrans_response;

        // Try to get package info from different possible locations
        $packageType = null;
        $packageDuration = null;

        if (isset($response['custom_field1'])) {
            // From custom field (if we stored it there)
            $packageType = $response['custom_field1'];
        } elseif (isset($response['item_details'][0]['name'])) {
            // From item details
            $itemName = $response['item_details'][0]['name'];
            if (str_contains($itemName, 'Bulanan')) {
                $packageType = 'premium_monthly';
            } elseif (str_contains($itemName, 'Tahunan')) {
                $packageType = 'premium_yearly';
            }
        }

        // Fallback: Infer from amount
        if (!$packageType) {
            $amount = $transaction->gross_amount;
            if ($amount == 50000) {
                $packageType = 'premium_monthly';
                $packageDuration = 1;
            } elseif ($amount == 500000) {
                $packageType = 'premium_yearly';
                $packageDuration = 12;
            } else {
                \Log::error('Cannot determine package type', [
                    'order_id' => $transaction->order_id,
                    'amount' => $amount,
                ]);
                throw new \Exception('Package information not found in transaction');
            }
        }

        // Set duration if not set
        if (!$packageDuration) {
            $packageDuration = $packageType === 'premium_monthly' ? 1 : 12;
        }


        // Check if user has active subscription(s)
        $activeSubscriptions = Langganan::where('user_id', $transaction->user_id)
            ->where('status', 'aktif')
            ->orderBy('tanggal_mulai', 'asc')
            ->get();

        if ($activeSubscriptions->count() > 0) {
            // Get the primary (oldest) active subscription
            $primarySubscription = $activeSubscriptions->first();

            \Log::info('Extending existing subscription', [
                'order_id' => $transaction->order_id,
                'primary_langganan_id' => $primarySubscription->id,
                'active_count' => $activeSubscriptions->count(),
                'current_end_date' => $primarySubscription->tanggal_berakhir->format('Y-m-d'),
            ]);

            // If there are multiple active subscriptions (bug from before), consolidate them
            if ($activeSubscriptions->count() > 1) {
                \Log::warning('Multiple active subscriptions found, consolidating', [
                    'user_id' => $transaction->user_id,
                    'count' => $activeSubscriptions->count(),
                ]);

                // Deactivate all except the primary
                $activeSubscriptions->skip(1)->each(function ($sub) {
                    $sub->update(['status' => 'kadaluarsa']);
                });
            }

            // IMPORTANT: Use copy() to avoid mutating the original Carbon instance
            $currentEndDate = $primarySubscription->tanggal_berakhir->copy();
            $newEndDate = $currentEndDate->addMonths($packageDuration);

            $primarySubscription->update([
                'tanggal_berakhir' => $newEndDate,
            ]);

            // Link transaction to primary langganan
            $transaction->update(['langganan_id' => $primarySubscription->id]);

            // Create notification for subscription extension
            \App\Models\Notifikasi::create([
                'user_id' => $transaction->user_id,
                'animal_id' => null,
                'perkawinan_id' => null,
                'jenis_notifikasi' => 'langganan',
                'pesan' => '✅ Langganan Anda telah diperpanjang! Masa aktif baru hingga ' . $newEndDate->format('d M Y') . '. Terima kasih atas kepercayaan Anda!',
                'tanggal_kirim' => now(),
                'status' => 'belum_dibaca',
            ]);

            \Log::info('Subscription extended successfully', [
                'order_id' => $transaction->order_id,
                'langganan_id' => $primarySubscription->id,
                'old_end_date' => $currentEndDate->format('Y-m-d'),
                'new_end_date' => $newEndDate->format('Y-m-d'),
                'months_added' => $packageDuration,
            ]);

            return $primarySubscription;
        }

        // CREATE new subscription (first time or after expiry)
        // First, deactivate any old subscriptions
        Langganan::where('user_id', $transaction->user_id)
            ->where('status', 'aktif')
            ->update(['status' => 'kadaluarsa']);

        \Log::info('Creating new langganan', [
            'order_id' => $transaction->order_id,
            'package_type' => $packageType,
            'duration' => $packageDuration,
        ]);
        $langganan = Langganan::create([
            'user_id' => $transaction->user_id,
            'paket_langganan' => $packageType,
            'tanggal_mulai' => now(),
            'tanggal_berakhir' => now()->addMonths($packageDuration),
            'status' => 'aktif', // Set directly to aktif
            'harga' => $transaction->gross_amount,
            'metode_pembayaran' => 'midtrans',
            'auto_renew' => false,
        ]);

        // Link transaction to langganan
        $transaction->update(['langganan_id' => $langganan->id]);

        // Update user role to premium with explicit logging
        $user = $transaction->user;
        $oldRole = $user->role;

        $user->update([
            'role' => \App\Models\User::ROLE_PREMIUM,
            'status_langganan' => 'premium',  // Fix: should be 'premium' not 'aktif'
        ]);

        \Log::info('User role updated to premium', [
            'user_id' => $user->id,
            'old_role' => $oldRole,
            'new_role' => $user->fresh()->role,
            'subscription_id' => $langganan->id
        ]);

        // Create notification for successful subscription
        \App\Models\Notifikasi::create([
            'user_id' => $transaction->user_id,
            'animal_id' => null,
            'perkawinan_id' => null,
            'jenis_notifikasi' => 'langganan',
            'pesan' => '🎉 Selamat! Langganan ' . ($packageType === 'premium_monthly' ? 'Premium Bulanan' : 'Premium Tahunan') . ' Anda telah aktif. Nikmati semua fitur premium FarmGo!',
            'tanggal_kirim' => now(),
            'status' => 'belum_dibaca',
        ]);

        \Log::info('Subscription activated successfully', [
            'order_id' => $transaction->order_id,
            'langganan_id' => $langganan->id,
            'user_id' => $transaction->user_id,
        ]);

        return $langganan;
    }

    /**
     * Show pending payment details
     */
    public function showPendingPayment($orderId)
    {
        $user = Auth::user();
        $transaction = Transaction::where('order_id', $orderId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Fetch latest status from Midtrans
        try {
            $midtransStatus = \Midtrans\Transaction::status($orderId);

            // Update transaction with latest data from Midtrans
            $statusArray = json_decode(json_encode($midtransStatus), true);
            $transaction->updateFromMidtrans($statusArray);

            // Refresh transaction from database
            $transaction->refresh();
        } catch (\Exception $e) {
            \Log::warning('Failed to fetch Midtrans status', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
            ]);
        }

        return view('langganan.pending-payment', compact('transaction'));
    }

    /**
     * Check payment status from Midtrans
     */
    public function checkPaymentStatus($orderId)
    {
        $user = Auth::user();
        $transaction = Transaction::where('order_id', $orderId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        try {
            // Query Midtrans for latest status
            $midtransStatus = \Midtrans\Transaction::status($orderId);

            // Convert object to array
            $statusArray = json_decode(json_encode($midtransStatus), true);

            \Log::info('Midtrans status response', [
                'order_id' => $orderId,
                'raw_response' => $statusArray,
            ]);

            // Update local transaction
            $transaction->updateFromMidtrans($statusArray);

            // Refresh from database to get updated values
            $transaction->refresh();

            \Log::info('Transaction after update', [
                'order_id' => $orderId,
                'status' => $transaction->status,
                'payment_type' => $transaction->payment_type,
                'langganan_id' => $transaction->langganan_id,
            ]);

            // Check if payment settled (use our mapped status)
            if ($transaction->status === 'settlement') {

                // Activate subscription if not already activated
                if (!$transaction->langganan_id) {
                    \Log::info('Activating subscription', ['order_id' => $orderId]);
                    $this->activateSubscriptionFromTransaction($transaction);
                    $transaction->refresh();
                }

                return response()->json([
                    'success' => true,
                    'paid' => true,
                    'status' => 'settlement',
                    'message' => 'Pembayaran berhasil!',
                ]);
            }

            return response()->json([
                'success' => true,
                'paid' => false,
                'status' => $transaction->status,
                'message' => 'Pembayaran masih ' . $transaction->status,
            ]);

        } catch (\Exception $e) {
            \Log::error('Check payment status error', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'paid' => false,
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Activate trial from UI
     */
    public function activateTrial()
    {
        $user = Auth::user();

        // Check if user already used trial
        if ($user->trial_ends_at) {
            return back()->with('error', 'Anda sudah pernah menggunakan trial period');
        }

        $user->startTrial(7); // 7 days trial

        // Create notification for trial activation
        \App\Models\Notifikasi::create([
            'user_id' => $user->id,
            'animal_id' => null,
            'perkawinan_id' => null,
            'jenis_notifikasi' => 'langganan',
            'pesan' => '🎉 Selamat! Trial 7 hari Anda telah aktif. Nikmati semua fitur FarmGo secara gratis hingga ' . $user->trial_ends_at->format('d M Y') . '!',
            'tanggal_kirim' => now(),
            'status' => 'belum_dibaca',
        ]);

        return redirect()->route('dashboard')->with('success', 'Trial 7 hari berhasil diaktifkan!');
    }

    /**
     * Show payment history
     */
    public function paymentHistory()
    {
        $user = Auth::user();

        // Get all successful transactions (these are the actual payments)
        $transactions = Transaction::where('user_id', $user->id)
            ->where('status', 'settlement')
            ->with('langganan')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get current active subscription
        $currentSubscription = Langganan::where('user_id', $user->id)
            ->where('status', 'aktif')
            ->first();

        return view('langganan.history', compact('transactions', 'currentSubscription'));
    }

    /**
     * Cancel subscription auto-renewal
     */
    public function cancelSubscription(Request $request)
    {
        $user = Auth::user();

        $subscription = Langganan::where('user_id', $user->id)
            ->where('id', $request->subscription_id)
            ->first();

        if (!$subscription) {
            return back()->with('error', 'Langganan tidak ditemukan');
        }

        $subscription->cancel();

        return back()->with('success', 'Perpanjangan otomatis berhasil dibatalkan');
    }
}
