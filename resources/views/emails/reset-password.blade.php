<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - FarmGo</title>
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
                            style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: bold;">
                                Reset Password
                            </h1>
                            <p style="margin: 10px 0 0 0; color: rgba(255, 255, 255, 0.9); font-size: 14px;">
                                Buat password baru untuk akun FarmGo Anda
                            </p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin: 0 0 20px 0; color: #374151; font-size: 16px; line-height: 1.6;">
                                Halo!
                            </p>

                            <p style="margin: 0 0 30px 0; color: #6b7280; font-size: 14px; line-height: 1.6;">
                                Kami menerima permintaan untuk mereset password akun FarmGo Anda. Klik tombol di bawah
                                ini untuk membuat password baru.
                            </p>

                            <!-- CTA Button -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td style="text-align: center; padding: 20px 0;">
                                        <a href="{{ $resetUrl }}"
                                            style="display: inline-block; background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color: #ffffff; text-decoration: none; padding: 16px 40px; border-radius: 8px; font-size: 16px; font-weight: bold; box-shadow: 0 4px 6px rgba(37, 99, 235, 0.3);">
                                            Reset Password Saya
                                        </a>
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
                                            Link reset password ini akan kadaluarsa dalam <strong>60 menit</strong>.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Info Box -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                                style="margin-top: 20px; background-color: #f3f4f6; border-left: 4px solid #6b7280; padding: 15px; border-radius: 4px;">
                                <tr>
                                    <td>
                                        <p style="margin: 0; color: #374151; font-size: 13px; font-weight: bold;">
                                            Tidak meminta reset password?
                                        </p>
                                        <p style="margin: 5px 0 0 0; color: #6b7280; font-size: 12px;">
                                            Jika Anda tidak meminta reset password, abaikan email ini. Akun Anda tetap
                                            aman dan tidak ada perubahan yang akan dilakukan.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Alternative Link -->
                            <p style="margin: 30px 0 0 0; color: #9ca3af; font-size: 12px; line-height: 1.6;">
                                Jika tombol tidak berfungsi, salin dan tempel link berikut ke browser Anda:<br>
                                <a href="{{ $resetUrl }}"
                                    style="color: #2563eb; word-break: break-all;">{{ $resetUrl }}</a>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td
                            style="background-color: #f9fafb; padding: 30px; text-align: center; border-top: 1px solid #e5e7eb;">
                            <p style="margin: 0 0 5px 0; color: #9ca3af; font-size: 12px;">
                                Email ini dikirim secara otomatis, mohon tidak membalas.
                            </p>
                            <p style="margin: 0; color: #9ca3af; font-size: 11px;">
                                © {{ date('Y') }} FarmGo. All rights reserved.<br>
                                Sistem Manajemen Peternakan Modern
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>

</html>