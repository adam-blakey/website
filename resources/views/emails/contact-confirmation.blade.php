<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Thank you for contacting Adam Blakey</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .content {
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px 5px 5px 5px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 0.8em;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="content">
        <p>Howdy {{ $contact->name }},</p>

        <p>Thank you for contacting me. I have received your message and will get back to you as soon as possible.</p>

        <p>Here's a copy of your message:</p>

        <blockquote style="background-color: #f9f9f9; padding: 15px; border-left: 5px solid #2d3748;">
            {{ $contact->message }}
        </blockquote>

        <p>Adam Blakey</p>
    </div>

    <div class="footer">
        <p><em>This is an automated message. Please do not reply to this email.</em></p>
    </div>
</body>
</html>
