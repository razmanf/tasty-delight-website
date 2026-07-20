<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Inter', Arial, sans-serif; background-color: #f9fafb; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; padding: 30px; border: 1px solid #e5e7eb; }
        .header { text-align: center; border-bottom: 2px solid #DD6625; padding-bottom: 20px; margin-bottom: 20px; }
        .logo { color: #DD6625; font-size: 24px; font-weight: bold; }
        .code-box { background-color: #f3f4f6; border-radius: 8px; padding: 20px; text-align: center; margin: 30px 0; font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #111827; }
        .btn { display: inline-block; padding: 12px 24px; background-color: #DD6625; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <img src="{{ asset('storage/favicons/favicon-96x96.png') }}" alt="TastyDelight Logo" style="vertical-align: middle; height: 32px; margin-right: 10px; display: inline-block;">
                <span style="vertical-align: middle;">TastyDelight</span>
            </div>
            <h2>Verify Your Registration</h2>
        </div>
        <p>Hi there,</p>
        <p>Thank you for registering an account with TastyDelight! Please use the following 6-digit One-Time Password (OTP) to complete your registration:</p>
        
        <div class="code-box">
            {{ $code }}
        </div>
        
        <p>This code will expire in 10 minutes. If you did not request this code, you can safely ignore this email.</p>
        
        <p style="margin-top: 30px; font-size: 12px; color: #6b7280; text-align: center;">
            Need help? Reply to this email or contact our support team.
        </p>
    </div>
</body>
</html>
