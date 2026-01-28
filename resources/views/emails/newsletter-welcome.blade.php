<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang - Newsletter FarmGo</title>
</head>

<body
    style="font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #f8fafc; margin: 0; padding: 0;">
    <div
        style="max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <!-- Header -->
        <div
            style="background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%); padding: 40px 32px; text-align: center;">
            <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: 800;">🎉 Selamat Datang!</h1>
            <p style="color: rgba(255, 255, 255, 0.9); margin: 8px 0 0 0; font-size: 16px;">Anda telah bergabung dengan
                Newsletter FarmGo</p>
        </div>

        <!-- Content -->
        <div style="padding: 40px 32px;">
            <p style="color: #334155; margin: 0 0 16px 0; font-size: 16px; line-height: 1.6;">
                Terima kasih telah berlangganan newsletter <strong style="color: #10b981;">FarmGo</strong>! 🚀
            </p>

            <p style="color: #334155; margin: 0 0 24px 0; font-size: 16px; line-height: 1.6;">
                Anda sekarang akan menerima informasi terbaru tentang:
            </p>

            <div
                style="background: linear-gradient(135deg, #ecfdf5 0%, #f0fdfa 100%); padding: 24px; border-radius: 12px; margin-bottom: 24px; border: 1px solid #d1fae5;">
                <ul style="color: #065f46; margin: 0; padding-left: 20px; font-size: 15px; line-height: 2;">
                    <li><strong>Tips Peternakan</strong> - Panduan praktis untuk meningkatkan produktivitas</li>
                    <li><strong>Update Fitur Terbaru</strong> - Fitur-fitur baru yang akan memudahkan Anda</li>
                    <li><strong>Best Practices</strong> - Cara terbaik mengelola ternak modern</li>
                    <li><strong>Promo Menarik</strong> - Penawaran khusus untuk pelanggan newsletter</li>
                </ul>
            </div>

            <div
                style="background-color: #fef3c7; border-left: 4px solid #f59e0b; padding: 20px; border-radius: 8px; margin-bottom: 24px;">
                <p style="color: #92400e; margin: 0; font-size: 14px; line-height: 1.6;">
                    <strong>💡 Tips Pertama:</strong> Mulai gunakan FarmGo sekarang untuk mengalami kemudahan manajemen
                    peternakan digital. Dapatkan trial gratis 7 hari!
                </p>
            </div>

            <div style="text-align: center; margin-top: 32px;">
                <a href="{{ config('app.url') }}/login"
                    style="display: inline-block; background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%); color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 8px; font-weight: 600; font-size: 15px; margin-bottom: 12px;">
                    🚀 Mulai Sekarang
                </a>
                <p style="color: #64748b; margin: 8px 0 0 0; font-size: 13px;">
                    Gratis untuk 7 hari pertama!
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div style="background-color: #f8fafc; padding: 24px 32px; text-align: center; border-top: 1px solid #e2e8f0;">
            <p style="color: #64748b; margin: 0 0 8px 0; font-size: 13px;">
                Email terkirim ke: <strong>{{ $subscription->email }}</strong>
            </p>
            <p style="color: #94a3b8; margin: 8px 0 0 0; font-size: 12px;">
                © 2025 FarmGo. Solusi Digital Peternakan Indonesia.
            </p>
        </div>
    </div>
</body>

</html>