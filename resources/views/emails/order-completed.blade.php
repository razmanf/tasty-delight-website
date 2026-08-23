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
        .btn { display: inline-block; padding: 12px 24px; background-color: #DD6625; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; margin-top: 30px; text-align: center; }
        .details-card { background-color: #f8fafc; border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; margin-top: 20px; }
        
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: 8px 0; text-align: left; }
        .table-item-name { color: #111827; font-weight: 500; font-size: 15px; }
        .table-item-desc { color: #6b7280; font-size: 13px; }
        .table-price { text-align: right; color: #111827; font-weight: 500; }
        
        .divider { border-top: 1px dashed #d1d5db; margin: 15px 0; }
        
        .summary-row { display: table; width: 100%; margin-bottom: 8px; font-size: 14px; color: #4b5563; }
        .summary-label { display: table-cell; text-align: right; padding-right: 20px; width: 75%; }
        .summary-value { display: table-cell; text-align: right; width: 25%; }
        
        .summary-total { font-size: 18px; font-weight: bold; color: #DD6625; margin-top: 15px; border-top: 1px solid #e5e7eb; padding-top: 15px; }
        
        .address-box { margin-top: 20px; padding: 15px; background-color: #fffaf0; border: 1px solid #fbd38d; border-radius: 8px; }
        .address-title { font-weight: bold; font-size: 12px; text-transform: uppercase; color: #DD6625; letter-spacing: 1px; margin-bottom: 5px; }
        
        .badge { background-color: #bbf7d0; color: #166534; padding: 4px 10px; border-radius: 9999px; font-size: 12px; font-weight: bold; display: inline-block; margin-bottom: 15px; }

        @media (prefers-color-scheme: dark) {
            body { background-color: #111827 !important; color: #f9fafb !important; }
            .container { background-color: #1f2937 !important; border-color: #374151 !important; }
            h2, h3, .table-item-name, .table-price { color: #f9fafb !important; }
            p, .table-item-desc, .summary-row, .summary-label { color: #d1d5db !important; }
            .details-card { background-color: #374151 !important; border-color: #4b5563 !important; }
            .divider, .summary-total { border-color: #4b5563 !important; }
            .address-box { background-color: #422006 !important; border-color: #9a3412 !important; }
            .badge { background-color: #166534 !important; color: #bbf7d0 !important; }
        }
        
        /* Hide Gmail Image Download Icon */
        img + div { display: none !important; }
    </style>
</head>
<body>
    <div class="container">
        <table width="100%" cellpadding="0" cellspacing="0" style="border-bottom: 2px solid #DD6625; margin-bottom: 20px; padding-bottom: 20px;">
            <tr>
                <td style="text-align: center;">
                    <a href="{{ config('app.url') }}" style="text-decoration: none; border: none;">
                        <img src="{{ asset('images/tasty-delight-logo.webp') }}" alt="TastyDelight" style="height: 60px; vertical-align: middle; margin-right: 10px; display: inline-block; border: none;">
                    </a>
                    <span class="logo" style="vertical-align: middle;">TastyDelight</span>
                </td>
            </tr>
        </table>
        
        <div style="text-align: center;">
            <div class="badge">COMPLETED</div>
            <h2 style="margin-top: 0; color: #111827;">Your order is complete!</h2>
            <h3 style="margin-top: 5px; color: #6b7280; font-weight: normal;">Order Receipt #{{ $order->id }}</h3>
        </div>
        
        <p>Hi {{ $order->user->name }},</p>
        
        @if(strtolower($order->order_type) === 'delivery')
            <p>Great news! Your order is now complete and has been handed over to our delivery driver. It is on its way to you right now. Enjoy your TastyDelight!</p>
        @else
            <p>Great news! Your order is freshly prepared and is now ready for pickup at our counter. See you soon!</p>
        @endif
        
        <div class="details-card">
            <h3 style="margin-top:0; font-size: 14px; text-transform:uppercase; letter-spacing:1px; color:#6b7280; border-bottom:1px solid #e5e7eb; padding-bottom:10px; margin-bottom:15px;">Order Items</h3>
            <table class="table">
                @foreach($order->products as $product)
                <tr>
                    <td>
                        <div class="table-item-name">{{ $product->pivot->quantity }}x {{ $product->name }}</div>
                        <div class="table-item-desc">(${{ number_format($product->pivot->price, 2) }} each)</div>
                    </td>
                    <td class="table-price">$ {{ number_format($product->pivot->price * $product->pivot->quantity, 2) }}</td>
                </tr>
                @endforeach
            </table>
            
            @if($order->preparation_note)
            <div style="margin-top: 15px; padding: 12px; background-color: #fffbeb; border-left: 3px solid #f59e0b; border-radius: 4px; font-size: 13px; color: #92400e;">
                <strong style="text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; display: block; margin-bottom: 3px;">Prep Note</strong>
                {{ $order->preparation_note }}
            </div>
            @endif

            <div class="divider"></div>
            
            <div class="summary-row">
                <div class="summary-label">Subtotal</div>
                <div class="summary-value">${{ number_format($order->total_amount - $order->tax_amount - $order->delivery_fee + $order->discount_amount, 2) }}</div>
            </div>
            @if($order->discount_amount > 0)
            <div class="summary-row">
                <div class="summary-label" style="color: #16a34a;">Discount ({{ $order->promo_code }})</div>
                <div class="summary-value" style="color: #16a34a;">-${{ number_format($order->discount_amount, 2) }}</div>
            </div>
            @endif
            <div class="summary-row">
                <div class="summary-label">Tax (8%)</div>
                <div class="summary-value">${{ number_format($order->tax_amount, 2) }}</div>
            </div>
            <div class="summary-row">
                <div class="summary-label">Delivery Fee</div>
                <div class="summary-value">${{ number_format($order->delivery_fee, 2) }}</div>
            </div>
            
            <div class="summary-row summary-total">
                <div class="summary-label" style="color: #DD6625;">Total Paid</div>
                <div class="summary-value">${{ number_format($order->total_amount, 2) }}</div>
            </div>
            <div class="summary-row" style="margin-top: 15px; border-top: 1px dashed #d1d5db; padding-top: 15px;">
                <div class="summary-label" style="color: #6b7280; font-size: 12px; font-weight: normal;">Order Type</div>
                <div class="summary-value" style="color: #6b7280; font-size: 12px; font-weight: bold; text-transform: uppercase;">{{ $order->order_type }}</div>
            </div>
            <div class="summary-row" style="margin-top: 5px;">
                <div class="summary-label" style="color: #6b7280; font-size: 12px; font-weight: normal;">Payment Method</div>
                <div class="summary-value" style="color: #6b7280; font-size: 12px; font-weight: bold; text-transform: uppercase;">{{ $order->payment_method ?? 'Card' }}</div>
            </div>
        </div>

        @if(strtolower($order->order_type) === 'delivery' && $order->delivery_address)
        <div class="address-box">
            <div class="address-title">📍 Delivery Address</div>
            <div style="font-size: 14px; line-height: 1.5; color: #4b5563; margin-bottom: {{ $order->delivery_note ? '10px' : '15px' }};">{{ $order->delivery_address }}</div>
            
            @if($order->delivery_note)
            <div style="margin-bottom: 15px; font-size: 13px; color: #6b7280; font-style: italic; background-color: #ffffff; padding: 10px; border-radius: 6px; border: 1px dashed #d1d5db;">
                <strong>Note to Rider:</strong> {{ $order->delivery_note }}
            </div>
            @endif
            
            <a href="https://www.google.com/maps/search/?api=1&query={{ $order->delivery_lat && $order->delivery_lng ? $order->delivery_lat . ',' . $order->delivery_lng : urlencode($order->delivery_address) }}" target="_blank" style="display: block; width: 100%; text-align: center; background-color: #f3f4f6; color: #4b5563; padding: 12px 0; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: bold; border: 1px solid #e5e7eb;">
                🗺️ Open in Google Maps
            </a>
        </div>
        @endif
        
        <div style="text-align:center">
            <a href="{{ url('/user/orders') }}" class="btn" style="color: #ffffff; text-decoration: none;">View Order in Dashboard</a>
        </div>
        
        <p style="margin-top: 30px; font-size: 12px; color: #6b7280; text-align: center;">
            Enjoy your food! If you have any questions, please contact our support team.
        </p>
    </div>
</body>
</html>
