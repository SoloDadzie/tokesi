<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 20px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #f5f5f5;">
    <div style="max-width: 600px; margin: 0 auto; background: #fff; border-radius: 4px; padding: 30px;">
        <h2 style="margin: 0 0 20px; color: #333;">New Order Received</h2>
        
        <div style="padding: 15px; background: #f0f9ff; border-left: 3px solid #667eea; margin-bottom: 20px;">
            <p style="margin: 0; font-size: 18px; font-weight: 600; color: #333;">Order: {{ $order->order_number }}</p>
            <p style="margin: 5px 0 0; color: #666;">Total: £{{ number_format($order->total, 2) }}</p>
        </div>

        <h3 style="margin: 20px 0 10px; color: #333; font-size: 16px;">Customer Information</h3>
        <p style="margin: 0; color: #666; line-height: 1.6;">
            <strong>Name:</strong> {{ $order->full_name }}<br>
            <strong>Email:</strong> {{ $order->email }}<br>
            <strong>Phone:</strong> {{ $order->phone }}
        </p>

        <h3 style="margin: 20px 0 10px; color: #333; font-size: 16px;">Order Items</h3>
        @foreach($order->items as $item)
        <div style="padding: 10px 0; border-bottom: 1px solid #eee;">
            <p style="margin: 0; color: #333;">{{ $item->product_name }} × {{ $item->quantity }}</p>
            <p style="margin: 5px 0 0; color: #666;">£{{ number_format($item->subtotal, 2) }}</p>
        </div>
        @endforeach

        <p style="margin: 20px 0 0; padding: 15px; background: #f9f9f9; border-radius: 4px;">
            <a href="{{ url('/admin/orders') }}" style="color: #667eea; text-decoration: none; font-weight: 600;">View Order in Admin Panel →</a>
        </p>
    </div>
</body>
</html>