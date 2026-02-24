<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Shipped</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 40px 0;">
        <tr>
            <td align="center">
                <!-- Main Container -->
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: 600;">Your Order is On Its Way! 🚚</h1>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="color: #333333; font-size: 16px; line-height: 1.6; margin: 0 0 20px;">
                                Hello <strong>{{ $order->full_name }}</strong>,
                            </p>
                            
                            <p style="color: #555555; font-size: 15px; line-height: 1.6; margin: 0 0 30px;">
                                Great news! Your order <strong>#{{ $order->order_number }}</strong> has been shipped and is on its way to you.
                            </p>

                            <!-- Order Details Box -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8f9fa; border-radius: 6px; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 25px;">
                                        <h2 style="color: #333333; font-size: 18px; margin: 0 0 20px; font-weight: 600;">Shipping Details</h2>
                                        
                                        <table width="100%" cellpadding="8" cellspacing="0">
                                            @if($order->shipping_company)
                                            <tr>
                                                <td style="color: #666666; font-size: 14px; padding: 8px 0;">Carrier:</td>
                                                <td style="color: #333333; font-size: 14px; font-weight: 600; text-align: right; padding: 8px 0;">{{ $order->shipping_company }}</td>
                                            </tr>
                                            @endif

                                            @if($order->tracking_number)
                                            <tr>
                                                <td style="color: #666666; font-size: 14px; padding: 8px 0;">Tracking Number:</td>
                                                <td style="color: #333333; font-size: 14px; font-weight: 600; text-align: right; padding: 8px 0;">{{ $order->tracking_number }}</td>
                                            </tr>
                                            @endif

                                            <tr>
                                                <td style="color: #666666; font-size: 14px; padding: 8px 0;">Shipping Method:</td>
                                                <td style="color: #333333; font-size: 14px; font-weight: 600; text-align: right; padding: 8px 0;">{{ $order->shipping_method }}</td>
                                            </tr>

                                            <tr>
                                                <td style="color: #666666; font-size: 14px; padding: 8px 0;">Shipped On:</td>
                                                <td style="color: #333333; font-size: 14px; font-weight: 600; text-align: right; padding: 8px 0;">{{ $order->shipped_at->format('F j, Y g:i A') }}</td>
                                            </tr>
                                        </table>

                                        @if($order->tracking_link)
                                        <div style="margin-top: 25px; text-align: center;">
                                            <a href="{{ $order->tracking_link }}" style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 6px; font-weight: 600; font-size: 15px;">
                                                Track Your Package
                                            </a>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                            </table>

                            <!-- Shipping Address -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="border: 1px solid #e0e0e0; border-radius: 6px; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h3 style="color: #333333; font-size: 16px; margin: 0 0 15px; font-weight: 600;">Delivery Address</h3>
                                        <p style="color: #555555; font-size: 14px; line-height: 1.6; margin: 0;">
                                            {{ $order->full_name }}<br>
                                            {{ $order->shipping_address }}<br>
                                            {{ $order->city }}, {{ $order->state }} {{ $order->zipcode }}<br>
                                            {{ $order->country }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Order Items -->
                            <h3 style="color: #333333; font-size: 16px; margin: 0 0 15px; font-weight: 600;">Order Items</h3>
                            @foreach($order->items as $item)
                            <table width="100%" cellpadding="0" cellspacing="0" style="border-bottom: 1px solid #e0e0e0; padding: 15px 0;">
                                <tr>
                                    <td width="80" style="padding-right: 15px;">
                                        @php
                                            $snapshot = $item->getProductSnapshot();
                                            $imagePath = $snapshot['image'] ?? 'default-product.png';
                                        @endphp
                                        <img src="{{ asset('storage/' . $imagePath) }}" alt="{{ $item->product_name }}" style="width: 70px; height: 70px; object-fit: cover; border-radius: 4px;">
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
                                    <td style="color: #333333; font-size: 16px; font-weight: 700; padding: 15px 0 5px;">Total:</td>
                                    <td style="color: #333333; font-size: 16px; font-weight: 700; text-align: right; padding: 15px 0 5px;">£{{ number_format($order->total, 2) }}</td>
                                </tr>
                            </table>

                            <!-- Help Section -->
                            <div style="margin-top: 40px; padding: 20px; background-color: #f8f9fa; border-radius: 6px; text-align: center;">
                                <p style="color: #555555; font-size: 14px; margin: 0 0 10px;">Questions about your order?</p>
                                <p style="color: #666666; font-size: 13px; margin: 0;">
                                    Contact us at <a href="mailto:{{ config('mail.from.address') }}" style="color: #667eea; text-decoration: none;">{{ config('mail.from.address') }}</a>
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