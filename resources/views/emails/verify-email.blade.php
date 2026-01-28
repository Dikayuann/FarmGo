<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - FarmGo</title>
</head>

<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f3f4f6;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
        style="background-color: #f3f4f6;">
        <tr>
            <td style="padding: 40px 20px;">
                <!-- Main Container -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600"
                    style="margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">

                    <!-- Header with Gradient -->
                    <tr>
                        <td
                            style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: bold;">
                                Verifikasi Email Anda
                            </h1>
                            <p style="margin: 10px 0 0 0; color: rgba(255, 255, 255, 0.9); font-size: 14px;">
                                Satu langkah lagi untuk memulai perjalanan Anda di FarmGo
                            </p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin: 0 0 20px 0; color: #374151; font-size: 16px; line-height: 1.6;">
                                Halo, <strong>{{ $userName }}</strong>!
                            </p>

                            <p style="margin: 0 0 30px 0; color: #6b7280; font-size: 14px; line-height: 1.6;">
                                Terima kasih telah mendaftar di <strong>FarmGo</strong>! Kami sangat senang Anda
                                bergabung dengan platform manajemen peternakan kami. Untuk melanjutkan, silakan
                                verifikasi alamat email Anda dengan mengklik tombol di bawah ini:
                            </p>

                            <!-- CTA Button -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td style="text-align: center; padding: 20px 0;">
                                        <a href="{{ $verificationUrl }}"
                                            style="display: inline-block; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #ffffff; text-decoration: none; padding: 16px 40px; border-radius: 8px; font-size: 16px; font-weight: bold; box-shadow: 0 4px 6px rgba(16, 185, 129, 0.3);">
                                            Verifikasi Email Saya
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Benefits -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                                style="margin-top: 30px; background-color: #f9fafb; border-radius: 8px; padding: 20px;">
                                <tr>
                                    <td>
                                        <p
                                            style="margin: 0 0 15px 0; color: #111827; font-size: 15px; font-weight: bold;">
                                            Apa yang terjadi setelah verifikasi?
                                        </p>

                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0"
                                            width="100%">
                                            <tr>
                                                <td style="padding: 8px 0;">
                                                    <p style="margin: 0; color: #059669; font-size: 14px;">
                                                        <strong>✓ Akses Penuh</strong>
                                                    </p>
                                                    <p style="margin: 5px 0 0 0; color: #6b7280; font-size: 13px;">
                                                        Dapatkan akses ke semua fitur manajemen ternak, kesehatan, dan
                                                        reproduksi
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0;">
                                                    <p style="margin: 0; color: #059669; font-size: 14px;">
                                                        <strong>✓ Dashboard Personal</strong>
                                                    </p>
                                                    <p style="margin: 5px 0 0 0; color: #6b7280; font-size: 13px;">
                                                        Kelola data peternakan Anda dengan dashboard yang intuitif
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0;">
                                                    <p style="margin: 0; color: #059669; font-size: 14px;">
                                                        <strong>✓ Notifikasi & Reminder</strong>
                                                    </p>
                                                    <p style="margin: 5px 0 0 0; color: #6b7280; font-size: 13px;">
                                                        Terima pengingat penting untuk vaksinasi dan jadwal breeding
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Warning -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                                style="margin-top: 20px; background-color: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; border-radius: 4px;">
                                <tr>
                                    <td>
                                        <p style="margin: 0; color: #92400e; font-size: 13px; font-weight: bold;">
                                            ⚠️ Penting!
                                        </p>
                                        <p style="margin: 5px 0 0 0; color: #78350f; font-size: 12px;">
                                            Link verifikasi ini akan kadaluarsa dalam <strong>24 jam</strong>. Pastikan
                                            Anda memverifikasi email secepatnya.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Alternative Link -->
                            <p style="margin: 30px 0 0 0; color: #9ca3af; font-size: 12px; line-height: 1.6;">
                                Jika tombol tidak berfungsi, salin dan tempel link berikut ke browser Anda:<br>
                                <a href="{{ $verificationUrl }}"
                                    style="color: #10b981; word-break: break-all;">{{ $verificationUrl }}</a>
                            </p>

                            <p style="margin: 20px 0 0 0; color: #9ca3af; font-size: 12px;">
                                Jika Anda tidak mendaftar akun FarmGo, abaikan email ini.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td
                            style="background-color: #f9fafb; padding: 30px; text-align: center; border-top: 1px solid #e5e7eb;">
                            <p style="margin: 0 0 10px 0; color: #374151; font-size: 14px; font-weight: bold;">
                                Butuh bantuan?
                            </p>
                            <p style="margin: 0 0 15px 0; color: #6b7280; font-size: 12px;">
                                Tim support kami siap membantu Anda 24/7
                            </p>

                            <p style="margin: 0; color: #9ca3af; font-size: 11px;">
                                © {{ date('Y') }} FarmGo. All rights reserved.<br>
                                Platform Manajemen Peternakan Modern
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>

</html>