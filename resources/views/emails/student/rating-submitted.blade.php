<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Rating Submitted - ITENAS Units</title>
    <style>
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        body { margin: 0 !important; padding: 0 !important; width: 100% !important; background-color: #f4f4f4; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        @media screen and (max-width: 600px) {
            .container { width: 100% !important; max-width: 100% !important; }
            .content-padding { padding: 24px 20px !important; }
            .h1 { font-size: 24px !important; line-height: 1.2 !important; }
            .h2 { font-size: 20px !important; }
            .body-text { font-size: 15px !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f4; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;">
    <div style="display: none; max-height: 0; overflow: hidden; opacity: 0; color: transparent;">Rating Anda untuk unit berhasil dikirim</div>

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f4f4f4;">
        <tr>
            <td align="center" style="padding: 40px 20px;">

                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" class="container" style="max-width: 600px; width: 100%;">

                    <tr>
                        <td align="center" style="padding: 24px 0; font-family: 'Inter', sans-serif;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td style="font-size: 24px; font-weight: 700; letter-spacing: 0.06em; color: #0a0a0a;">ITENAS</td>
                                    <td style="font-size: 24px; font-weight: 700; letter-spacing: 0.06em; color: #E8520A; padding-left: 4px;">UNITS</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">

                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td style="height: 4px; background: linear-gradient(90deg, #E8520A 0%, #c04408 100%);"></td>
                                </tr>
                            </table>

                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" class="content-padding">
                                <tr>
                                    <td style="padding: 48px 48px 24px 48px;" class="content-padding">
                                        <h1 class="h1" style="margin: 0 0 16px; font-size: 28px; font-weight: 700; color: #0a0a0a; letter-spacing: -0.01em; line-height: 1.2;">Rating Berhasil Dikirim</h1>
<p class="body-text" style="margin: 0 0 24px; font-size: 16px; line-height: 1.6; color: #444444;">
    Halo <strong>{{ $studentName }}</strong>,
</p>
<p class="body-text" style="margin: 0 0 24px; font-size: 16px; line-height: 1.6; color: #444444;">
    Terima kasih telah memberikan rating untuk <strong>{{ $unitName }}</strong>. Feedback Anda sangat berarti untuk meningkatkan kualitas unit.
</p>
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 24px 0; background: #f9f9f9; border-radius: 6px;">
    <tr>
        <td style="padding: 24px; font-family: 'Inter', sans-serif; text-align: center;">
            <p style="margin: 0 0 8px; font-size: 12px; color: #888888; text-transform: uppercase; letter-spacing: 0.1em;">Your Rating</p>
            <p style="margin: 0; font-size: 48px; font-weight: 700; color: #E8520A; line-height: 1;">{{ number_format($overallScore, 1) }}</p>
            <p style="margin: 8px 0 0; font-size: 14px; color: #888888;">out of 5.0</p>
        </td>
    </tr>
</table>
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 16px 0; background: #f9f9f9; border-radius: 6px;">
    <tr>
        <td style="padding: 16px 20px; font-family: 'Inter', sans-serif;">
            <p style="margin: 0 0 4px; font-size: 12px; color: #888888; text-transform: uppercase; letter-spacing: 0.1em;">Tracking Code</p>
            <p style="margin: 0; font-size: 16px; color: #0a0a0a; font-weight: 600; font-family: 'Courier New', monospace;">{{ $trackingCode }}</p>
        </td>
    </tr>
</table>
<p class="body-text" style="margin: 24px 0 0; font-size: 14px; line-height: 1.6; color: #666666;">
    Anda dapat mengubah rating Anda dalam waktu 7 hari jika diperlukan.
</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 0 48px 48px 48px;" class="content-padding">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 32px auto;">
    <tr>
        <td style="border-radius: 6px; background: #E8520A;">
            <a href="{{ $viewUrl }}" target="_blank" style="display: inline-block; padding: 14px 32px; font-family: 'Inter', sans-serif; font-size: 14px; font-weight: 600; color: #ffffff; text-decoration: none; border-radius: 6px; letter-spacing: 0.05em; text-transform: uppercase;">View Rating</a>
        </td>
    </tr>
</table>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding: 32px 20px 16px; font-family: 'Inter', sans-serif;">
                            <p style="margin: 0; font-size: 12px; color: #888888; line-height: 1.6;">
                                &copy; {{ date('Y') }} Institut Teknologi Nasional. All rights reserved.<br>
                                Jl. PHH. Mustapa No.23, Bandung, Jawa Barat, Indonesia
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding: 0 20px 24px; font-family: 'Inter', sans-serif;">
                            <p style="margin: 0; font-size: 11px; color: #aaaaaa;">
                                This is an automated message. Please do not reply to this email.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>
</body>
</html>