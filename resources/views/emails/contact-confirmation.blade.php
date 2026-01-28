<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terima Kasih - FarmGo</title>
</head>

<body
    style="font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #f8fafc; margin: 0; padding: 0;">
    <div
        style="max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <!-- Header -->
        <div
            style="background: linear-gradient(135deg, #84cc16 0%, #22c55e 100%); padding: 40px 32px; text-align: center;">
            <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: 800;">✉️ Terima Kasih!</h1>
            <p style="color: rgba(255, 255, 255, 0.9); margin: 8px 0 0 0; font-size: 16px;">Pesan Anda telah kami terima
            </p>
        </div>

        <!-- Content -->
        <div style="padding: 40px 32px;">
            <p style="color: #334155; margin: 0 0 16px 0; font-size: 16px; line-height: 1.6;">
                Halo <strong>{{ $contact->name }}</strong>,
            </p>

            <p style="color: #334155; margin: 0 0 16px 0; font-size: 16px; line-height: 1.6;">
                Terima kasih telah menghubungi <strong style="color: #84cc16;">FarmGo</strong>! 🎉
            </p>

            <p style="color: #334155; margin: 0 0 24px 0; font-size: 16px; line-height: 1.6;">
                Kami telah menerima pesan Anda dan akan merespons sesegera mungkin. Tim kami biasanya membalas dalam
                waktu 1-2 hari kerja.
            </p>

            <div
                style="background-color: #f0fdf4; border-left: 4px solid #84cc16; padding: 20px; border-radius: 8px; margin-bottom: 24px;">
                <p style="color: #15803d; margin: 0 0 8px 0; font-size: 14px; font-weight: 600;">
                    📝 Ringkasan Pesan Anda
                </p>
                <div style="margin-top: 12px;">
                    <p style="color: #166534; margin: 0 0 4px 0; font-size: 13px;"><strong>Dari:</strong>
                        {{ $contact->email }}</p>
                    <p style="color: #166534; margin: 0; font-size: 13px;"><strong>Dikirim:</strong>
                        {{ $contact->created_at->format('d F Y, H:i') }} WIB</p>
                </div>
            </div>

            <div style="background-color: #eff6ff; padding: 24px; border-radius: 12px; margin-bottom: 24px;">
                <h3 style="color: #1e40af; margin: 0 0 12px 0; font-size: 16px; font-weight: 700;">
                    💡 Sementara menunggu...
                </h3>
                <p style="color: #1e40af; margin: 0 0 16px 0; font-size: 14px; line-height: 1.6;">
                    Kenali lebih lanjut fitur-fitur FarmGo yang dapat membantu mengelola peternakan Anda:
                </p>
                <ul style="color: #1e40af; margin: 0; padding-left: 20px; font-size: 14px; line-height: 1.8;">
                    <li>Manajemen data ternak secara digital</li>
                    <li>Monitoring kesehatan & vaksinasi</li>
                    <li>Pencatatan reproduksi otomatis</li>
                    <li>Notifikasi & reminder penting</li>
                </ul>
            </div>

            <div style="text-align: center; margin-top: 32px;">
                <a href="{{ config('app.url') }}"
                    style="display: inline-block; background: linear-gradient(135deg, #84cc16 0%, #22c55e 100%); color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 8px; font-weight: 600; font-size: 15px;">
                    🚀 Kunjungi FarmGo
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div style="background-color: #f8fafc; padding: 24px 32px; text-align: center; border-top: 1px solid #e2e8f0;">
            <p style="color: #64748b; margin: 0 0 8px 0; font-size: 13px;">
                Butuh bantuan lebih lanjut? Hubungi kami di <a href="mailto:{{ config('mail.from.address') }}"
                    style="color: #84cc16; text-decoration: none;">{{ config('mail.from.address') }}</a>
            </p>
            <p style="color: #94a3b8; margin: 8px 0 0 0; font-size: 12px;">
                © 2025 FarmGo. Solusi Digital Peternakan Indonesia.
            </p>
        </div>
    </div>
</body>

</html>