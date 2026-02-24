<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Address Update - Admin Alert</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #263238 0%, #455a64 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: 600;">📍 Address Update Alert</h1>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="color: #333333; font-size: 16px; line-height: 1.6; margin: 0 0 20px;">
                                <strong>Admin Notification:</strong>
                            </p>
                            
                            <p style="color: #555555; font-size: 15px; line-height: 1.6; margin: 0 0 30px;">
                                The delivery information for order <strong>#{{ $order->order_number }}</strong> has been updated by the customer.
                            </p>

                            <!-- Quick Summary -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #e3f2fd; border-left: 4px solid #1976d2; border-radius: 6px; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h3 style="color: #0d47a1; font-size: 16px; margin: 0 0 15px; font-weight: 600;">Update Summary</h3>
                                        <table width="100%" cellpadding="5" cellspacing="0">
                                            <tr>
                                                <td style="color: #666666; font-size: 14px;">Order Number:</td>
                                                <td style="color: #333333; font-size: 14px; font-weight: 600; text-align: right;">{{ $order->order_number }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color: #666666; font-size: 14px;">Customer:</td>
                                                <td style="color: #333333; font-size: 14px; font-weight: 600; text-align: right;">{{ $order->full_name }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color: #666666; font-size: 14px;">Updated:</td>
                                                <td style="color: #333333; font-size: 14px; font-weight: 600; text-align: right;">{{ now()->format('F j, Y g:i A') }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color: #666666; font-size: 14px;">Order Status:</td>
                                                <td style="color: #333333; font-size: 14px; font-weight: 600; text-align: right; text-transform: capitalize;">{{ $order->status }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Email Change (if applicable) -->
                            @if($oldEmail !== $order->email)
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #fff3e0; border-left: 4px solid #ff9800; border-radius: 6px; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h3 style="color: #e65100; font-size: 16px; margin: 0 0 15px; font-weight: 600;">📧 Email Address Changed</h3>
                                        <table width="100%" cellpadding="5" cellspacing="0">
                                            <tr>
                                                <td style="color: #666666; font-size: 14px;">Previous Email:</td>
                                                <td style="color: #333333; font-size: 14px; font-weight: 600; text-align: right;">{{ $oldEmail }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color: #666666; font-size: 14px;">New Email:</td>
                                                <td style="color: #1976d2; font-size: 14px; font-weight: 600; text-align: right;">{{ $order->email }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            @endif

                            <!-- New Delivery Address -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8f9fa; border-radius: 6px; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 25px;">
                                        <h2 style="color: #333333; font-size: 18px; margin: 0 0 20px; font-weight: 600;">📦 Updated Delivery Address</h2>
                                        <p style="color: #555555; font-size: 14px; line-height: 1.8; margin: 0;">
                                            <strong>Name:</strong> {{ $order->full_name }}<br>
                                            <strong>Email:</strong> {{ $order->email }}<br>
                                            <strong>Phone:</strong> {{ $order->phone }}<br><br>
                                            <strong>Address:</strong><br>
                                            {{ $order->shipping_address }}<br>
                                            {{ $order->city }}, {{ $order->state }} {{ $order->zipcode }}<br>
                                            {{ $order->country }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Order Details -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="border: 1px solid #e0e0e0; border-radius: 6px; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h3 style="color: #333333; font-size: 16px; margin: 0 0 15px; font-weight: 600;">Order Information</h3>
                                        <table width="100%" cellpadding="5" cellspacing="0">
                                            <tr>
                                                <td style="color: #666666; font-size: 14px;">Order Total:</td>
                                                <td style="color: #333333; font-size: 14px; font-weight: 600; text-align: right;">£{{ number_format($order->total, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color: #666666; font-size: 14px;">Payment Status:</td>
                                                <td style="color: #333333; font-size: 14px; font-weight: 600; text-align: right; text-transform: capitalize;">{{ $order->payment_status }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color: #666666; font-size: 14px;">Shipping Method:</td>
                                                <td style="color: #333333; font-size: 14px; font-weight: 600; text-align: right;">{{ $order->shipping_method }}</td>
                                            </tr>
                                            @if($order->tracking_number)
                                            <tr>
                                                <td style="color: #666666; font-size: 14px;">Tracking Number:</td>
                                                <td style="color: #333333; font-size: 14px; font-weight: 600; text-align: right;">{{ $order->tracking_number }}</td>
                                            </tr>
                                            @endif
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Order Items Summary -->
                            <h3 style="color: #333333; font-size: 16px; margin: 0 0 15px; font-weight: 600;">Order Items ({{ $order->items->count() }})</h3>
                            @foreach($order->items as $item)
                            <table width="100%" cellpadding="0" cellspacing="0" style="border-bottom: 1px solid #e0e0e0; padding: 10px 0;">
                                <tr>
                                    <td>
                                        <p style="color: #333333; font-size: 14px; font-weight: 600; margin: 0;">{{ $item->product_name }}</p>
                                        <p style="color: #666666; font-size: 13px; margin: 5px 0 0;">Qty: {{ $item->quantity }}</p>
                                    </td>
                                    <td style="text-align: right; color: #333333; font-size: 14px; font-weight: 600;">
                                        £{{ number_format($item->subtotal, 2) }}
                                    </td>
                                </tr>
                            </table>
                            @endforeach

                            <!-- Action Required -->
                            <div style="margin-top: 30px; padding: 20px; background-color: #fff9e6; border-left: 4px solid #ffc107; border-radius: 6px;">
                                <h3 style="color: #f57f17; font-size: 16px; margin: 0 0 10px; font-weight: 600;">⚠️ Action May Be Required</h3>
                                <p style="color: #555555; font-size: 14px; line-height: 1.5; margin: 0;">
                                    • Verify the new address is valid<br>
                                    • Update shipping label if order hasn't shipped yet<br>
                                    • Check if shipping costs need adjustment<br>
                                    • Update your order management system
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 30px; text-align: center; border-top: 1px solid #e0e0e0;">
                            <p style="color: #999999; font-size: 12px; margin: 0;">
                                This is an automated admin notification from {{ config('app.name') }}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>