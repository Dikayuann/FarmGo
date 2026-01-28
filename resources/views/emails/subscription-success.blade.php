<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Langganan Berhasil - FarmGo</title>
</head>

<body
    style="font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #f8fafc; margin: 0; padding: 0;">
    <div
        style="max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <!-- Header -->
        <div
            style="background: linear-gradient(135deg, #84cc16 0%, #22c55e 100%); padding: 40px 32px; text-align: center;">
            <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: 800;">🎉 Pembayaran Berhasil!</h1>
            <p style="color: rgba(255, 255, 255, 0.9); margin: 8px 0 0 0; font-size: 16px;">Langganan Anda telah aktif
            </p>
        </div>

        <!-- Content -->
        <div style="padding: 40px 32px;">
            <p style="color: #334155; margin: 0 0 16px 0; font-size: 16px; line-height: 1.6;">
                Halo <strong>{{ $user->name }}</strong>,
            </p>

            <p style="color: #334155; margin: 0 0 16px 0; font-size: 16px; line-height: 1.6;">
                Terima kasih telah berlangganan <strong style="color: #84cc16;">FarmGo Premium</strong>! 🚀
            </p>

            <p style="color: #334155; margin: 0 0 24px 0; font-size: 16px; line-height: 1.6;">
                Pembayaran Anda telah berhasil diproses dan langganan Anda sudah aktif. Sekarang Anda dapat menikmati
                semua fitur premium FarmGo tanpa batasan!
            </p>

            <!-- Transaction Details -->
            <div
                style="background-color: #f0fdf4; border-left: 4px solid #84cc16; padding: 20px; border-radius: 8px; margin-bottom: 24px;">
                <p style="color: #15803d; margin: 0 0 12px 0; font-size: 14px; font-weight: 600;">
                    📋 Detail Transaksi
                </p>
                <div style="margin-top: 12px;">
                    <p style="color: #166534; margin: 0 0 6px 0; font-size: 13px;">
                        <strong>Paket:</strong>
                        {{ $subscription->paket_langganan === 'premium_monthly' ? 'Premium Bulanan' : 'Premium Tahunan' }}
                    </p>
                    <p style="color: #166534; margin: 0 0 6px 0; font-size: 13px;">
                        <strong>Harga:</strong> Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}
                    </p>
                    <p style="color: #166534; margin: 0 0 6px 0; font-size: 13px;">
                        <strong>Order ID:</strong> {{ $transaction->order_id }}
                    </p>
                    <p style="color: #166534; margin: 0 0 6px 0; font-size: 13px;">
                        <strong>Metode Pembayaran:</strong> {{ $transaction->payment_method }}
                    </p>
                    <p style="color: #166534; margin: 0 0 6px 0; font-size: 13px;">
                        <strong>Tanggal Mulai:</strong> {{ $subscription->tanggal_mulai->format('d F Y') }}
                    </p>
                    <p style="color: #166534; margin: 0; font-size: 13px;">
                        <strong>Berlaku Hingga:</strong> {{ $subscription->tanggal_berakhir->format('d F Y') }}
                    </p>
                </div>
            </div>

            <!-- Premium Features -->
            <div style="background-color: #eff6ff; padding: 24px; border-radius: 12px; margin-bottom: 24px;">
                <h3 style="color: #1e40af; margin: 0 0 12px 0; font-size: 16px; font-weight: 700;">
                    ✨ Fitur Premium yang Anda Dapatkan
                </h3>
                <ul style="color: #1e40af; margin: 0; padding-left: 20px; font-size: 14px; line-height: 1.8;">
                    <li>Unlimited jumlah ternak</li>
                    <li>Unlimited monitoring kesehatan</li>
                    <li>Unlimited catatan reproduksi</li>
                    <li>Ekspor data lengkap (Excel, PDF)</li>
                    <li>AI Assistant untuk konsultasi</li>
                    <li>Support prioritas dari tim FarmGo</li>
                </ul>
            </div>

            <div style="text-align: center; margin-top: 32px;">
                <a href="{{ config('app.url') }}/dashboard"
                    style="display: inline-block; background: linear-gradient(135deg, #84cc16 0%, #22c55e 100%); color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 8px; font-weight: 600; font-size: 15px;">
                    🚀 Mulai Gunakan FarmGo
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div style="background-color: #f8fafc; padding: 24px 32px; text-align: center; border-top: 1px solid #e2e8f0;">
            <p style="color: #64748b; margin: 0 0 8px 0; font-size: 13px;">
                Butuh bantuan? Hubungi kami di <a href="mailto:{{ config('mail.from.address') }}"
                    style="color: #84cc16; text-decoration: none;">{{ config('mail.from.address') }}</a>
            </p>
            <p style="color: #94a3b8; margin: 8px 0 0 0; font-size: 12px;">
                © 2025 FarmGo. Solusi Digital Peternakan Indonesia.
            </p>
        </div>
    </div>
</body>

</html>