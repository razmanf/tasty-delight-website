<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Inter', Arial, sans-serif; background-color: #f9fafb; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; padding: 30px; border: 1px solid #e5e7eb; }
        .header { text-align: center; border-bottom: 2px solid #DD6625; padding-bottom: 20px; margin-bottom: 20px; }
        .logo { color: #DD6625; font-size: 24px; font-weight: bold; }
        .btn { display: inline-block; padding: 12px 24px; background-color: #DD6625; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; margin-top: 20px; text-align: center; }
        .features { margin: 30px 0; padding: 20px; background-color: #f8fafc; border-radius: 8px; }
        .feature-item { margin-bottom: 10px; font-size: 15px; color: #4b5563; }
        h3 { color: #1f2937; margin-top: 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <img src="{{ asset('storage/favicons/favicon-96x96.png') }}" alt="TastyDelight Logo" style="vertical-align: middle; height: 32px; margin-right: 10px; display: inline-block;">
                <span style="vertical-align: middle;">TastyDelight</span>
            </div>
            <h2>Welcome to the Family!</h2>
        </div>
        
        <p>Hi {{ $user->name }},</p>
        <p>We're absolutely thrilled to have you join TastyDelight! Your account is fully set up, verified, and ready to go.</p>
        
        <div class="features">
            <h3>Why you'll love TastyDelight:</h3>
            <div class="feature-item">🚀 <strong>Lightning Fast Delivery</strong> - Your food, hot and fresh, delivered in minutes.</div>
            <div class="feature-item">🍔 <strong>Endless Choices</strong> - Browse hundreds of top-rated local restaurants.</div>
            <div class="feature-item">⭐ <strong>Save Favorites</strong> - Keep track of the meals you love the most for easy re-ordering.</div>
            <div class="feature-item">💳 <strong>Secure Checkout</strong> - Fast, safe, and seamless payments.</div>
        </div>
        
        <p>Ready to discover your next favorite meal?</p>
        
        <div style="text-align:center">
            <a href="{{ url('/') }}" class="btn">Explore the Menu</a>
        </div>
        
        <p style="margin-top: 40px; font-size: 13px; color: #6b7280; text-align: center;">
            Have questions? Our support team is always here for you. Just hit reply!
        </p>
    </div>
</body>
</html>
