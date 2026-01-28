<?php

namespace App\Http\Controllers;

use App\Mail\NewsletterWelcome;
use App\Models\NewsletterSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    /**
     * Subscribe to newsletter.
     */
    public function subscribe(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Check if email already subscribed
            $existing = NewsletterSubscription::where('email', $request->email)->first();

            if ($existing) {
                if ($existing->is_active) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Email ini sudah terdaftar di newsletter kami.'
                    ], 422);
                } else {
                    // Reactivate subscription
                    $existing->update([
                        'is_active' => true,
                        'subscribed_at' => now()
                    ]);
                    $subscription = $existing;
                }
            } else {
                // Create new subscription
                $subscription = NewsletterSubscription::create([
                    'email' => $request->email,
                ]);
            }

            // Send welcome email
            Mail::to($subscription->email)->send(new NewsletterWelcome($subscription));

            return response()->json([
                'success' => true,
                'message' => 'Terima kasih! Anda sekarang berlangganan newsletter FarmGo. Cek email Anda untuk konfirmasi.'
            ]);

        } catch (\Exception $e) {
            \Log::error('Newsletter subscription error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. Silakan coba lagi nanti.'
            ], 500);
        }
    }
}
