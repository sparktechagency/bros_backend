<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Support Request</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0; padding:0; background-color:#f1f3f7; font-family:'Segoe UI', 'Roboto', sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" bgcolor="#f1f3f7" style="padding: 30px 0;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 8px 24px rgba(0,0,0,0.07);">

                    <!-- Header -->
                    <tr>
                        <td align="center" style="background-color: #4a90e2; padding: 40px 20px;">
                            <img src="{{ url('logo.png') }}" alt="App Logo" style="display: block; max-width: 150px; margin-bottom: 20px;">
                            <h1 style="margin: 0; font-size: 26px; color: #ffffff;">New Support Request</h1>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 30px 25px; color: #444; font-size: 16px; line-height: 1.7;">
                            <p style="margin-bottom: 12px;"><strong>👤 Full Name:</strong> {{ $support_message['full_name'] }}</p>
                            <p style="margin-bottom: 12px;"><strong>📧 Email:</strong> {{ $support_message['subject'] }}</p>
                            <p style="margin-bottom: 10px;"><strong>📝 Message:</strong></p>
                            <div style="background-color: #f7f9fc; padding: 20px; border-left: 4px solid #4a90e2; border-radius: 8px; color: #333;">
                                {{ $support_message['message'] }}
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="padding: 25px; font-size: 13px; color: #999;">
                            <p style="margin: 0;">This message was submitted from your website support form.</p>
                            <p style="margin: 5px 0;">&copy; {{ date('Y') }} <a href="#" style="color: #4a90e2; text-decoration: none;">{{ config('app.name') }}</a>. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>
</html>
