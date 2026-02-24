<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Cancelled - Admin Notification</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #263238 0%, #455a64 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: 600;">⚠️ Order Cancellation Alert</h1>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="color: #333333; font-size: 16px; line-height: 1.6; margin: 0 0 20px;">
                                <strong>Admin Notification:</strong>
                            </p>
                            
                            <p style="color: #555555; font-size: 15px; line-height: 1.6; margin: 0 0 30px;">
                                Order <strong>#{{ $order->order_number }}</strong> has been cancelled by the customer.
                            </p>

                            <!-- Quick Summary -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #fff3e0; border-left: 4px solid #ff6f00; border-radius: 6px; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h3 style="color: #e65100; font-size: 16px; margin: 0 0 15px; font-weight: 600;">Cancellation Summary</h3>
                                        <table width="100%" cellpadding="5" cellspacing="0">
                                            <tr>
                                                <td style="color: #666666; font-size: 14px;">Customer:</td>
                                                <td style="color: #333333; font-size: 14px; font-weight: 600; text-align: right;">{{ $order->full_name }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color: #666666; font-size: 14px;">Email:</td>
                                                <td style="color: #333333; font-size: 14px; font-weight: 600; text-align: right;">{{ $order->email }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color: #666666; font-size: 14px;">Order Total:</td>
                                                <td style="color: #333333; font-size: 14px; font-weight: 600; text-align: right;">£{{ number_format($order->total, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color: #666666; font-size: 14px;">Refund Status:</td>
                                                <td style="color: #333333; font-size: 14px; font-weight: 600; text-align: right; text-transform: capitalize;">{{ $order->payment_status }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Order Details -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8f9fa; border-radius: 6px; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 25px;">
                                        <h2 style="color: #333333; font-size: 18px; margin: 0 0 20px; font-weight: 600;">Order Information</h2>
                                        
                                        <table width="100%" cellpadding="8" cellspacing="0">
                                            <tr>
                                                <td style="color: #666666; font-size: 14px; padding: 8px 0;">Order Number:</td>
                                                <td style="color: #333333; font-size: 14px; font-weight: 600; text-align: right; padding: 8px 0;">{{ $order->order_number }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color: #666666; font-size: 14px; padding: 8px 0;">Order Date:</td>
                                                <td style="color: #333333; font-size: 14px; font-weight: 600; text-align: right; padding: 8px 0;">{{ $order->created_at->format('F j, Y g:i A') }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color: #666666; font-size: 14px; padding: 8px 0;">Cancellation Date:</td>
                                                <td style="color: #333333; font-size: 14px; font-weight: 600; text-align: right; padding: 8px 0;">{{ now()->format('F j, Y g:i A') }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color: #666666; font-size: 14px; padding: 8px 0;">Payment Method:</td>
                                                <td style="color: #333333; font-size: 14px; font-weight: 600; text-align: right; padding: 8px 0; text-transform: capitalize;">{{ $order->payment_method }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Customer Information -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="border: 1px solid #e0e0e0; border-radius: 6px; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h3 style="color: #333333; font-size: 16px; margin: 0 0 15px; font-weight: 600;">Customer Details</h3>
                                        <p style="color: #555555; font-size: 14px; line-height: 1.6; margin: 0;">
                                            <strong>Name:</strong> {{ $order->full_name }}<br>
                                            <strong>Email:</strong> {{ $order->email }}<br>
                                            <strong>Phone:</strong> {{ $order->phone }}<br><br>
                                            <strong>Shipping Address:</strong><br>
                                            {{ $order->shipping_address }}<br>
                                            {{ $order->city }}, {{ $order->state }} {{ $order->zipcode }}<br>
                                            {{ $order->country }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Cancelled Items -->
                            <h3 style="color: #333333; font-size: 16px; margin: 0 0 15px; font-weight: 600;">Cancelled Items</h3>
                            @foreach($order->items as $item)
                            <table width="100%" cellpadding="0" cellspacing="0" style="border-bottom: 1px solid #e0e0e0; padding: 15px 0;">
                                <tr>
                                    <td>
                                        <p style="color: #333333; font-size: 14px; font-weight: 600; margin: 0 0 5px;">{{ $item->product_name }}</p>
                                        <p style="color: #666666; font-size: 13px; margin: 0;">
                                            SKU: {{ $item->product_sku }} | Quantity: {{ $item->quantity }}
                                        </p>
                                    </td>
                                    <td style="text-align: right; color: #333333; font-size: 14px; font-weight: 600;">
                                        £{{ number_format($item->subtotal, 2) }}
                                    </td>
                                </tr>
                            </table>
                            @endforeach

                            <!-- Order Summary -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 25px; padding-top: 20px; border-top: 2px solid #e0e0e0;">
                                <tr>
                                    <td style="color: #666666; font-size: 14px; padding: 5px 0;">Subtotal:</td>
                                    <td style="color: #333333; font-size: 14px; text-align: right; padding: 5px 0;">£{{ number_format($order->subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td style="color: #666666; font-size: 14px; padding: 5px 0;">Shipping:</td>
                                    <td style="color: #333333; font-size: 14px; text-align: right; padding: 5px 0;">£{{ number_format($order->shipping_cost, 2) }}</td>
                                </tr>
                                @if($order->discount_amount > 0)
                                <tr>
                                    <td style="color: #28a745; font-size: 14px; padding: 5px 0;">Discount:</td>
                                    <td style="color: #28a745; font-size: 14px; text-align: right; padding: 5px 0;">-£{{ number_format($order->discount_amount, 2) }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td style="color: #333333; font-size: 16px; font-weight: 700; padding: 15px 0 5px;">Total Refunded:</td>
                                    <td style="color: #e53935; font-size: 16px; font-weight: 700; text-align: right; padding: 15px 0 5px;">£{{ number_format($order->total, 2) }}</td>
                                </tr>
                            </table>

                            <!-- Action Required -->
                            <div style="margin-top: 30px; padding: 20px; background-color: #e3f2fd; border-left: 4px solid #1976d2; border-radius: 6px;">
                                <h3 style="color: #0d47a1; font-size: 16px; margin: 0 0 10px; font-weight: 600;">📋 Action Required</h3>
                                <p style="color: #555555; font-size: 14px; line-height: 1.5; margin: 0;">
                                    • Update inventory if needed<br>
                                    • Review cancellation reason (if provided)<br>
                                    • Monitor refund processing<br>
                                    • Update your records accordingly
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