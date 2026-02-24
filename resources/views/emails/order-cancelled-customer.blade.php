<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Cancelled</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #e53935 0%, #e35d5b 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: 600;">Order Cancelled</h1>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="color: #333333; font-size: 16px; line-height: 1.6; margin: 0 0 20px;">
                                Hello <strong>{{ $order->full_name }}</strong>,
                            </p>
                            
                            <p style="color: #555555; font-size: 15px; line-height: 1.6; margin: 0 0 30px;">
                                We're writing to confirm that your order <strong>#{{ $order->order_number }}</strong> has been cancelled as requested.
                            </p>

                            <!-- Refund Notice -->
                            @if($order->payment_status === 'refunded')
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #e8f5e9; border-left: 4px solid #4caf50; border-radius: 6px; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h3 style="color: #2e7d32; font-size: 16px; margin: 0 0 10px; font-weight: 600;">💳 Refund Processed</h3>
                                        <p style="color: #555555; font-size: 14px; line-height: 1.5; margin: 0;">
                                            A refund of <strong>£{{ number_format($order->total, 2) }}</strong> has been processed to your original payment method. Please allow 5-10 business days for the refund to appear in your account.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            @endif

                            <!-- Order Details -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8f9fa; border-radius: 6px; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 25px;">
                                        <h2 style="color: #333333; font-size: 18px; margin: 0 0 20px; font-weight: 600;">Cancelled Order Details</h2>
                                        
                                        <table width="100%" cellpadding="8" cellspacing="0">
                                            <tr>
                                                <td style="color: #666666; font-size: 14px; padding: 8px 0;">Order Number:</td>
                                                <td style="color: #333333; font-size: 14px; font-weight: 600; text-align: right; padding: 8px 0;">{{ $order->order_number }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color: #666666; font-size: 14px; padding: 8px 0;">Order Date:</td>
                                                <td style="color: #333333; font-size: 14px; font-weight: 600; text-align: right; padding: 8px 0;">{{ $order->created_at->format('F j, Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color: #666666; font-size: 14px; padding: 8px 0;">Total Amount:</td>
                                                <td style="color: #333333; font-size: 14px; font-weight: 600; text-align: right; padding: 8px 0;">£{{ number_format($order->total, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color: #666666; font-size: 14px; padding: 8px 0;">Payment Method:</td>
                                                <td style="color: #333333; font-size: 14px; font-weight: 600; text-align: right; padding: 8px 0; text-transform: capitalize;">{{ $order->payment_method }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Order Items -->
                            <h3 style="color: #333333; font-size: 16px; margin: 0 0 15px; font-weight: 600;">Items in Cancelled Order</h3>
                            @foreach($order->items as $item)
                            <table width="100%" cellpadding="0" cellspacing="0" style="border-bottom: 1px solid #e0e0e0; padding: 15px 0;">
                                <tr>
                                    <td width="80" style="padding-right: 15px;">
                                        @php
                                            $snapshot = $item->getProductSnapshot();
                                            $imagePath = $snapshot['image'] ?? 'default-product.png';
                                        @endphp
                                        <img src="{{ asset('storage/' . $imagePath) }}" alt="{{ $item->product_name }}" style="width: 70px; height: 70px; object-fit: cover; border-radius: 4px; opacity: 0.6;">
                                    </td>
                                    <td>
                                        <p style="color: #333333; font-size: 14px; font-weight: 600; margin: 0 0 5px;">{{ $item->product_name }}</p>
                                        <p style="color: #666666; font-size: 13px; margin: 0;">Quantity: {{ $item->quantity }}</p>
                                    </td>
                                    <td style="text-align: right; color: #333333; font-size: 14px; font-weight: 600;">
                                        £{{ number_format($item->subtotal, 2) }}
                                    </td>
                                </tr>
                            </table>
                            @endforeach

                            <!-- Help Section -->
                            <div style="margin-top: 40px; padding: 20px; background-color: #f8f9fa; border-radius: 6px; text-align: center;">
                                <p style="color: #555555; font-size: 14px; margin: 0 0 10px;">We're sorry to see you go!</p>
                                <p style="color: #666666; font-size: 13px; margin: 0 0 15px;">
                                    If you have any questions or need assistance, we're here to help.
                                </p>
                                <p style="color: #666666; font-size: 13px; margin: 0;">
                                    Contact us at <a href="mailto:{{ config('mail.from.address') }}" style="color: #e53935; text-decoration: none;">{{ config('mail.from.address') }}</a>
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 30px; text-align: center; border-top: 1px solid #e0e0e0;">
                            <p style="color: #999999; font-size: 12px; margin: 0 0 10px;">
                                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                            </p>
                            <p style="color: #999999; font-size: 12px; margin: 0;">
                                This email was sent to {{ $order->email }}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>