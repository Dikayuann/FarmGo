<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Baru - FarmGo</title>
</head>

<body
    style="font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #f8fafc; margin: 0; padding: 0;">
    <div
        style="max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <!-- Header -->
        <div
            style="background: linear-gradient(135deg, #84cc16 0%, #22c55e 100%); padding: 40px 32px; text-align: center;">
            <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: 800;">📬 Pesan Baru</h1>
            <p style="color: rgba(255, 255, 255, 0.9); margin: 8px 0 0 0; font-size: 16px;">dari form kontak FarmGo</p>
        </div>

        <!-- Content -->
        <div style="padding: 40px 32px;">
            <div
                style="background-color: #f0fdf4; border-left: 4px solid #84cc16; padding: 20px; border-radius: 8px; margin-bottom: 32px;">
                <p style="color: #15803d; margin: 0; font-size: 14px; font-weight: 600;">
                    ✅ Pesan baru telah diterima pada {{ $contact->created_at->format('d F Y, H:i') }} WIB
                </p>
            </div>

            <h2 style="color: #1e293b; font-size: 20px; font-weight: 700; margin: 0 0 24px 0;">Detail Pesan</h2>

            <div style="margin-bottom: 20px;">
                <p
                    style="color: #64748b; margin: 0 0 4px 0; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                    Nama</p>
                <p style="color: #1e293b; margin: 0; font-size: 16px; font-weight: 600;">{{ $contact->name }}</p>
            </div>

            <div style="margin-bottom: 20px;">
                <p
                    style="color: #64748b; margin: 0 0 4px 0; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                    Email</p>
                <p style="color: #1e293b; margin: 0; font-size: 16px;">
                    <a href="mailto:{{ $contact->email }}"
                        style="color: #84cc16; text-decoration: none; font-weight: 500;">{{ $contact->email }}</a>
                </p>
            </div>

            <div style="margin-bottom: 24px;">
                <p
                    style="color: #64748b; margin: 0 0 8px 0; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                    Pesan</p>
                <div style="background-color: #f8fafc; padding: 16px; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <p style="color: #334155; margin: 0; font-size: 15px; line-height: 1.6; white-space: pre-wrap;">
                        {{ $contact->message }}</p>
                </div>
            </div>

            <a href="mailto:{{ $contact->email }}"
                style="display: inline-block; background: linear-gradient(135deg, #84cc16 0%, #22c55e 100%); color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 8px; font-weight: 600; font-size: 15px; margin-top: 8px;">
                📧 Balas Pesan
            </a>
        </div>

        <!-- Footer -->
        <div style="background-color: #f8fafc; padding: 24px 32px; text-align: center; border-top: 1px solid #e2e8f0;">
            <p style="color: #64748b; margin: 0; font-size: 13px;">
                Email ini dikirim otomatis oleh sistem FarmGo
            </p>
            <p style="color: #94a3b8; margin: 8px 0 0 0; font-size: 12px;">
                © 2025 FarmGo. Solusi Digital Peternakan Indonesia.
            </p>
        </div>
    </div>
</body>

</html>