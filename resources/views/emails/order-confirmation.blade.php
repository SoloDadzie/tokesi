<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background-color: #f5f5f5;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f5f5f5; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 4px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 600;">Order Confirmed!</h1>
                            <p style="margin: 10px 0 0; color: #ffffff; font-size: 16px; opacity: 0.9;">Thank you for your purchase</p>
                        </td>
                    </tr>

                    <!-- Order Number -->
                    <tr>
                        <td style="padding: 30px 30px 20px;">
                            <p style="margin: 0 0 10px; color: #666; font-size: 14px;">Order Number:</p>
                            <h2 style="margin: 0; color: #333; font-size: 24px; font-weight: 600;">{{ $order->order_number }}</h2>
                        </td>
                    </tr>

                    <!-- Order Items -->
                    <tr>
                        <td style="padding: 0 30px 20px;">
                            <h3 style="margin: 0 0 15px; color: #333; font-size: 18px; font-weight: 600;">Order Details</h3>
                            
                            @foreach($order->items as $item)
                            <div style="padding: 15px 0; border-bottom: 1px solid #eee;">
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style="vertical-align: top;">
                                            <p style="margin: 0 0 5px; color: #333; font-size: 16px; font-weight: 500;">{{ $item->product_name }}</p>
                                            <p style="margin: 0; color: #999; font-size: 14px;">SKU: {{ $item->product_sku }}</p>
                                        </td>
                                        <td style="text-align: right; vertical-align: top;">
                                            <p style="margin: 0 0 5px; color: #333; font-size: 16px;">£{{ number_format($item->price, 2) }} × {{ $item->quantity }}</p>
                                            <p style="margin: 0; color: #666; font-size: 14px; font-weight: 600;">£{{ number_format($item->subtotal, 2) }}</p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            @endforeach
                        </td>
                    </tr>

                    <!-- Order Summary -->
                    <tr>
                        <td style="padding: 20px 30px; background-color: #f9f9f9;">
                            <table width="100%" cellpadding="5" cellspacing="0">
                                <tr>
                                    <td style="color: #666; font-size: 14px;">Subtotal:</td>
                                    <td style="text-align: right; color: #333; font-size: 14px;">£{{ number_format($order->subtotal, 2) }}</td>
                                </tr>
                                @if($order->discount_amount > 0)
                                <tr>
                                    <td style="color: #666; font-size: 14px;">Discount ({{ $order->coupon_code }}):</td>
                                    <td style="text-align: right; color: #22c55e; font-size: 14px;">-£{{ number_format($order->discount_amount, 2) }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td style="color: #666; font-size: 14px;">Shipping:</td>
                                    <td style="text-align: right; color: #333; font-size: 14px;">£{{ number_format($order->shipping_cost, 2) }}</td>
                                </tr>
                                <tr style="border-top: 2px solid #ddd;">
                                    <td style="padding-top: 10px; color: #333; font-size: 18px; font-weight: 600;">Total:</td>
                                    <td style="padding-top: 10px; text-align: right; color: #667eea; font-size: 20px; font-weight: 700;">£{{ number_format($order->total, 2) }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Shipping Info -->
                    <tr>
                        <td style="padding: 20px 30px;">
                            <h3 style="margin: 0 0 15px; color: #333; font-size: 18px; font-weight: 600;">Shipping Information</h3>
                            <p style="margin: 0 0 5px; color: #666; font-size: 14px; line-height: 1.6;">
                                <strong style="color: #333;">{{ $order->full_name }}</strong><br>
                                {{ $order->shipping_address }}<br>
                                {{ $order->city }}, {{ $order->state }} {{ $order->zipcode }}<br>
                                {{ $order->country }}<br>
                                Phone: {{ $order->phone }}
                            </p>
                            <p style="margin: 15px 0 0; padding: 12px; background-color: #f0f9ff; border-left: 3px solid #667eea; color: #666; font-size: 14px;">
                                <strong style="color: #333;">Shipping Method:</strong> {{ $order->shipping_method }}
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 30px; background-color: #f9f9f9; text-align: center; border-top: 1px solid #eee;">
                            <p style="margin: 0 0 10px; color: #666; font-size: 14px;">Need help? Contact us at <a href="mailto:support@example.com" style="color: #667eea; text-decoration: none;">support@example.com</a></p>
                            <p style="margin: 0; color: #999; font-size: 12px;">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>