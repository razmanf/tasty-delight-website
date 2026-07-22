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
        .code-box { background-color: #f3f4f6; border-radius: 8px; padding: 20px; text-align: center; margin: 30px 0; font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #111827; }
        .btn { display: inline-block; padding: 12px 24px; background-color: #DD6625; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; margin-top: 20px; }
        
        @media (prefers-color-scheme: dark) {
            body { background-color: #111827 !important; color: #f9fafb !important; }
            .container { background-color: #1f2937 !important; border-color: #374151 !important; }
            h2, h3, .code-box { color: #f9fafb !important; }
            .feature-item, p, th, td { color: #d1d5db !important; }
            .code-box { background-color: #374151 !important; }
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
        
        <h2 style="text-align: center; color: #111827;">Verify Your Registration</h2>
        <p>Hi there,</p>
        <p>Thank you for registering an account with TastyDelight! Please use the following 6-digit One-Time Password (OTP) to complete your registration:</p>
        
        <div class="code-box">
            {{ $code }}
        </div>
        
        <p>This code will expire in 10 minutes. If you did not request this code, you can safely ignore this email.</p>
        
        <p style="margin-top: 30px; font-size: 12px; color: #6b7280; text-align: center;">
            Need help? Please contact our support team.
        </p>
    </div>
</body>
</html>
