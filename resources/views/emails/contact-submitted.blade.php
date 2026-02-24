<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Submission</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f5f5f5;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f5f5f5; padding: 40px 20px;">
        <tr>
            <td align="center">
                <!-- Main Container -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="max-width: 600px; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #21263a 0%, #2d3449 100%); padding: 40px 30px; text-align: center;">
                            <div style="background-color: #d67a00; width: 60px; height: 60px; border-radius: 50%; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center;">
                                <span style="color: #21263a; font-size: 28px; font-weight: bold;">✉</span>
                            </div>
                            <h1 style="margin: 0; color: #ffffff; font-size: 26px; font-weight: 700; letter-spacing: -0.5px;">
                                New Contact Form Submission
                            </h1>
                            <p style="margin: 10px 0 0; color: #d67a00; font-size: 14px; font-weight: 500;">
                                {{ $contact->created_at->format('F j, Y \a\t g:i A') }}
                            </p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            
                            <!-- Contact Information -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-bottom: 25px;">
                                <tr>
                                    <td style="padding: 15px; background-color: #f8f9fa; border-radius: 6px; border-left: 3px solid #d67a00;">
                                        <p style="margin: 0 0 10px; color: #21263a; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                            Contact Details
                                        </p>
                                        <p style="margin: 0 0 8px; color: #555555; font-size: 15px;">
                                            <strong style="color: #21263a;">Name:</strong> {{ $contact->name }}
                                        </p>
                                        <p style="margin: 0; color: #555555; font-size: 15px;">
                                            <strong style="color: #21263a;">Email:</strong> 
                                            <a href="mailto:{{ $contact->email }}" style="color: #d67a00; text-decoration: none;">{{ $contact->email }}</a>
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Subject -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-bottom: 25px;">
                                <tr>
                                    <td style="padding: 15px; background-color: #f8f9fa; border-radius: 6px; border-left: 3px solid #d67a00;">
                                        <p style="margin: 0 0 10px; color: #21263a; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                            Subject
                                        </p>
                                        <p style="margin: 0; color: #21263a; font-size: 16px; font-weight: 600;">
                                            {{ $contact->subject }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Message -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 20px; background-color: #f8f9fa; border-radius: 6px; border-left: 3px solid #d67a00;">
                                        <p style="margin: 0 0 15px; color: #21263a; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                            Message
                                        </p>
                                        <p style="margin: 0; color: #555555; font-size: 15px; line-height: 1.7; white-space: pre-wrap;">{{ $contact->message }}</p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Additional Info -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td style="padding: 15px; background-color: #f8f9fa; border-radius: 6px;">
                                        <p style="margin: 0 0 8px; color: #777777; font-size: 13px;">
                                            <strong style="color: #21263a;">IP Address:</strong> {{ $contact->ip_address ?? 'N/A' }}
                                        </p>
                                        <p style="margin: 0; color: #777777; font-size: 13px;">
                                            <strong style="color: #21263a;">Submitted:</strong> {{ $contact->created_at->diffForHumans() }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Action Button -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-top: 30px;">
                                <tr>
                                    <td align="center">
                                        <a href="mailto:{{ $contact->email }}?subject=Re: {{ $contact->subject }}" style="display: inline-block; padding: 14px 32px; background: linear-gradient(135deg, #d67a00 0%, #b56600 100%); color: #21263a; font-size: 15px; font-weight: 600; text-decoration: none; border-radius: 6px; box-shadow: 0 2px 4px rgba(214, 122, 0, 0.3);">
                                            Reply to {{ $contact->name }}
                                        </a>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #21263a; padding: 25px 30px; text-align: center;">
                            <p style="margin: 0; color: #999999; font-size: 12px; line-height: 1.5;">
                                This is an automated notification from your website contact form.<br>
                                © {{ date('Y') }} Tokesi Akinola. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>