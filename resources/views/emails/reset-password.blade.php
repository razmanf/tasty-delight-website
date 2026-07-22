<!DOCTYPE html>
<html>
<head>
    <meta name="color-scheme" content="light dark">
    <meta name="supported-color-schemes" content="light dark">
    <style>
        body { font-family: 'Inter', Arial, sans-serif; background-color: #f9fafb; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; padding: 30px; border: 1px solid #e5e7eb; }
        .header { text-align: center; border-bottom: 2px solid #DD6625; padding-bottom: 20px; margin-bottom: 20px; }
        .logo { color: #DD6625; font-size: 24px; font-weight: bold; }
        .btn { display: inline-block; padding: 12px 24px; background-color: #DD6625; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; margin-top: 20px; text-align: center; }
        .features { margin: 30px 0; padding: 20px; background-color: #f8fafc; border-radius: 8px; }
        .feature-item { margin-bottom: 10px; font-size: 15px; color: #4b5563; }
        h3 { color: #1f2937; margin-top: 0; }
        
        @media (prefers-color-scheme: dark) {
            body { background-color: #111827 !important; color: #f9fafb !important; }
            .container { background-color: #1f2937 !important; border-color: #374151 !important; }
            h2, h3 { color: #f9fafb !important; }
            .feature-item, p, th, td { color: #d1d5db !important; }
            .features { background-color: #374151 !important; }
        }
    </style>
</head>
<body>
    <div class="container">
        <table width="100%" cellpadding="0" cellspacing="0" style="border-bottom: 2px solid #DD6625; margin-bottom: 20px; padding-bottom: 20px;">
            <tr>
                <td style="text-align: center;">
                    <a href="{{ config('app.url') }}" style="text-decoration: none; border: none;">
                        <img src="{{ asset('images/tasty-delight-logo.png') }}" alt="TastyDelight" style="height: 60px; vertical-align: middle; margin-right: 10px; display: inline-block; border: none;">
                    </a>
                    <span class="logo" style="vertical-align: middle;">TastyDelight</span>
                </td>
            </tr>
        </table>
        
        <h2 style="text-align: center; color: #111827;">Reset Your Password</h2>
        
        <p>Hello!</p>
        <p>You are receiving this email because we received a password reset request for your account.</p>
        
        <div style="text-align:center; margin: 30px 0;">
            <a href="{{ $url }}" class="btn" style="color: #ffffff; text-decoration: none;">Reset Password</a>
        </div>
        
        <p>This password reset link will expire in {{ config('auth.passwords.'.config('auth.defaults.passwords').'.expire') }} minutes.</p>
        <p>If you did not request a password reset, no further action is required.</p>
        
        <p style="margin-top: 40px; font-size: 13px; color: #6b7280; text-align: center;">
            If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:<br>
            <a href="{{ $url }}" style="color: #DD6625; word-break: break-all;">{{ $url }}</a>
        </p>
    </div>
</body>
</html>
