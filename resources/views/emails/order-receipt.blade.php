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
        .total { font-size: 20px; font-weight: bold; color: #DD6625; margin-top: 20px; text-align: right; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px 0; border-bottom: 1px solid #e5e7eb; text-align: left; }
        .btn { display: inline-block; padding: 12px 24px; background-color: #DD6625; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; margin-top: 30px; }
        
        @media (prefers-color-scheme: dark) {
            body { background-color: #111827 !important; color: #f9fafb !important; }
            .container { background-color: #1f2937 !important; border-color: #374151 !important; }
            h2, h3, .total { color: #f9fafb !important; }
            .feature-item, p, th, td { color: #d1d5db !important; }
            th, td { border-bottom-color: #374151 !important; }
        }
    </style>
</head>
<body>
    <div class="container">
        <table width="100%" cellpadding="0" cellspacing="0" style="border-bottom: 2px solid #DD6625; margin-bottom: 20px; padding-bottom: 20px;">
            <tr>
                <td style="text-align: center;">
                    <img src="{{ asset('images/tasty-delight-logo.png') }}" alt="TastyDelight" style="height: 40px; vertical-align: middle; margin-right: 10px; display: inline-block;">
                    <span class="logo" style="vertical-align: middle;">TastyDelight</span>
                </td>
            </tr>
        </table>
        
        <h2 style="text-align: center; color: #111827;">Order Receipt #{{ $order->id }}</h2>
        <p>Hi {{ $order->user->name }},</p>
        <p>Thank you for your order! Your payment has been successfully processed. Here is your receipt:</p>
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th style="text-align:right">Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->pivot->quantity }}</td>
                    <td style="text-align:right">$ {{ number_format($product->pivot->price * $product->pivot->quantity, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="total">Total Paid: $ {{ number_format($order->total_amount, 2) }}</div>
        
        <div style="text-align:center">
            <a href="{{ url('/user/orders') }}" class="btn" style="color: #ffffff; text-decoration: none;">View Order History</a>
        </div>
        
        <p style="margin-top: 30px; font-size: 12px; color: #6b7280; text-align: center;">
            If you have any questions, please contact our support team.
        </p>
    </div>
</body>
</html>
