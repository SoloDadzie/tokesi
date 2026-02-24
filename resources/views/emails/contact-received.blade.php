<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form Received</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f5f5f5;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f5f5f5; padding: 40px 20px;">
        <tr>
            <td align="center">
                <!-- Main Container -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="max-width: 600px; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #d67a00 0%, #b56600 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #21263a; font-size: 28px; font-weight: 700; letter-spacing: -0.5px;">
                                Thank You for Reaching Out!
                            </h1>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin: 0 0 20px; color: #21263a; font-size: 16px; line-height: 1.6;">
                                Dear <strong>{{ $contact->name }}</strong>,
                            </p>

                            <p style="margin: 0 0 20px; color: #555555; font-size: 15px; line-height: 1.6;">
                                Thank you for contacting me! I've received your message and truly appreciate you taking the time to reach out. I'll review your inquiry and get back to you as soon as possible.
                            </p>

                            <!-- Message Summary Box -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 30px 0; background-color: #f8f9fa; border-left: 4px solid #d67a00; border-radius: 4px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 12px; color: #21263a; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                            Your Message
                                        </p>
                                        <p style="margin: 0 0 8px; color: #555555; font-size: 14px;">
                                            <strong style="color: #21263a;">Subject:</strong> {{ $contact->subject }}
                                        </p>
                                        <p style="margin: 0; color: #555555; font-size: 14px; line-height: 1.5;">
                                            <strong style="color: #21263a;">Message:</strong><br>
                                            {{ $contact->message }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 0 0 20px; color: #555555; font-size: 15px; line-height: 1.6;">
                                In the meantime, feel free to explore my books and stay connected through my social media channels.
                            </p>

                            <p style="margin: 0; color: #555555; font-size: 15px; line-height: 1.6;">
                                Warm regards,<br>
                                <strong style="color: #21263a;">Tokesi Akinola</strong><br>
                                <span style="color: #d67a00; font-size: 14px;">Children's Author</span>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #21263a; padding: 30px; text-align: center;">
                            <p style="margin: 0 0 15px; color: #ffffff; font-size: 14px; font-weight: 600;">
                                Stay Connected
                            </p>
                            
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center">
                                <tr>
                                    <td style="padding: 0 8px;">
                                        <a href="#" style="display: inline-block; width: 36px; height: 36px; background-color: #d67a00; border-radius: 50%; text-align: center; line-height: 36px; text-decoration: none;">
                                            <span style="color: #21263a; font-size: 16px;">f</span>
                                        </a>
                                    </td>
                                    <td style="padding: 0 8px;">
                                        <a href="#" style="display: inline-block; width: 36px; height: 36px; background-color: #d67a00; border-radius: 50%; text-align: center; line-height: 36px; text-decoration: none;">
                                            <span style="color: #21263a; font-size: 16px;">in</span>
                                        </a>
                                    </td>
                                    <td style="padding: 0 8px;">
                                        <a href="#" style="display: inline-block; width: 36px; height: 36px; background-color: #d67a00; border-radius: 50%; text-align: center; line-height: 36px; text-decoration: none;">
                                            <span style="color: #21263a; font-size: 16px;">ig</span>
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 20px 0 0; color: #999999; font-size: 12px; line-height: 1.5;">
                                © {{ date('Y') }} Tokesi Akinola. All rights reserved.<br>
                                Wigan Manchester, United Kingdom
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>