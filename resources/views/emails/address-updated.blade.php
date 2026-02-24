<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Address Updated</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #3f51b5 0%, #5c6bc0 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: 600;">📍 Delivery Address Updated</h1>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="color: #333333; font-size: 16px; line-height: 1.6; margin: 0 0 20px;">
                                Hello <strong>{{ $order->full_name }}</strong>,
                            </p>
                            
                            @if($emailType === 'old')
                            <p style="color: #555555; font-size: 15px; line-height: 1.6; margin: 0 0 30px;">
                                We're writing to inform you that the delivery address and email for order <strong>#{{ $order->order_number }}</strong> have been updated. This notification was sent to your previous email address for your records.
                            </p>
                            @elseif($emailType === 'new')
                            <p style="color: #555555; font-size: 15px; line-height: 1.6; margin: 0 0 30px;">
                                Welcome! The delivery information for order <strong>#{{ $order->order_number }}</strong> has been updated to use this email address. You will now receive all future updates about this order at this email.
                            </p>
                            @else
                            <p style="color: #555555; font-size: 15px; line-height: 1.6; margin: 0 0 30px;">
                                We're confirming that the delivery address for order <strong>#{{ $order->order_number }}</strong> has been updated successfully.
                            </p>
                            @endif

                            <!-- Security Notice -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #fff3e0; border-left: 4px solid #ff9800; border-radius: 6px; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h3 style="color: #e65100; font-size: 16px; margin: 0 0 10px; font-weight: 600;">🔒 Security Notice</h3>
                                        <p style="color: #555555; font-size: 14px; line-height: 1.5; margin: 0;">
                                            If you did not make this change, please contact us immediately at 
                                            <a href="mailto:{{ config('mail.from.address') }}" style="color: #ff6f00; text-decoration: none;">{{ config('mail.from.address') }}</a>
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Updated Information -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8f9fa; border-radius: 6px; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 25px;">
                                        <h2 style="color: #333333; font-size: 18px; margin: 0 0 20px; font-weight: 600;">Updated Delivery Information</h2>
                                        
                                        <table width="100%" cellpadding="8" cellspacing="0">
                                            <tr>
                                                <td style="color: #666666; font-size: 14px; padding: 8px 0;">Order Number:</td>
                                                <td style="color: #333333; font-size: 14px; font-weight: 600; text-align: right; padding: 8px 0;">{{ $order->order_number }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color: #666666; font-size: 14px; padding: 8px 0;">Contact Email:</td>
                                                <td style="color: #333333; font-size: 14px; font-weight: 600; text-align: right; padding: 8px 0;">{{ $order->email }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color: #666666; font-size: 14px; padding: 8px 0;">Updated On:</td>
                                                <td style="color: #333333; font-size: 14px; font-weight: 600; text-align: right; padding: 8px 0;">{{ now()->format('F j, Y g:i A') }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- New Shipping Address -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="border: 2px solid #3f51b5; border-radius: 6px; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h3 style="color: #3f51b5; font-size: 16px; margin: 0 0 15px; font-weight: 600;">📦 New Delivery Address</h3>
                                        <p style="color: #555555; font-size: 14px; line-height: 1.6; margin: 0;">
                                            {{ $order->full_name }}<br>
                                            {{ $order->shipping_address }}<br>
                                            {{ $order->city }}, {{ $order->state }} {{ $order->zipcode }}<br>
                                            {{ $order->country }}<br>
                                            <strong>Phone:</strong> {{ $order->phone }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Order Status -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="border: 1px solid #e0e0e0; border-radius: 6px; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h3 style="color: #333333; font-size: 16px; margin: 0 0 15px; font-weight: 600;">Current Order Status</h3>
                                        <table width="100%" cellpadding="5" cellspacing="0">
                                            <tr>
                                                <td style="color: #666666; font-size: 14px;">Status:</td>
                                                <td style="color: #333333; font-size: 14px; font-weight: 600; text-align: right; text-transform: capitalize;">{{ $order->status }}</td>
                                            </tr>
                                            <tr>
                                                <td style="color: #666666; font-size: 14px;">Total Amount:</td>
                                                <td style="color: #333333; font-size: 14px; font-weight: 600; text-align: right;">£{{ number_format($order->total, 2) }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Help Section -->
                            <div style="margin-top: 30px; padding: 20px; background-color: #e8eaf6; border-radius: 6px; text-align: center;">
                                <p style="color: #555555; font-size: 14px; margin: 0 0 10px;">Need to make changes or have questions?</p>
                                <p style="color: #666666; font-size: 13px; margin: 0;">
                                    Contact us at <a href="mailto:{{ config('mail.from.address') }}" style="color: #3f51b5; text-decoration: none;">{{ config('mail.from.address') }}</a>
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
                                This email was sent to {{ $recipientEmail }}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>