<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New contact via the website</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #2d3748;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 20px;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }

    </style>
</head>
<body>
    <div class="header">
        Contact from {{ $contact->name }}
    </div>
    <div class="content">
        {{ $contact->message }}
    </div>
</body>
</html>
