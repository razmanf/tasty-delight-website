<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Inter', Arial, sans-serif; background-color: #f9fafb; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; padding: 30px; border: 1px solid #e5e7eb; }
        .header { text-align: center; border-bottom: 2px solid #DD6625; padding-bottom: 20px; margin-bottom: 20px; }
        .logo { color: #DD6625; font-size: 24px; font-weight: bold; }
        .total { font-size: 20px; font-weight: bold; color: #DD6625; margin-top: 20px; text-align: right; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px 0; border-bottom: 1px solid #e5e7eb; text-align: left; }
        .btn { display: inline-block; padding: 12px 24px; background-color: #DD6625; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <img src="{{ asset('storage/favicons/favicon-96x96.png') }}" alt="TastyDelight Logo" style="vertical-align: middle; height: 32px; margin-right: 10px; display: inline-block;">
                <span style="vertical-align: middle;">TastyDelight</span>
            </div>
            <h2>Order Receipt #{{ $order->id }}</h2>
        </div>
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
            <a href="{{ url('/user/orders') }}" class="btn">View Order History</a>
        </div>
        
        <p style="margin-top: 30px; font-size: 12px; color: #6b7280; text-align: center;">
            If you have any questions, reply to this email or contact our support team.
        </p>
    </div>
</body>
</html>
