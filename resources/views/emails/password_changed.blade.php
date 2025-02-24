<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Change Notification</title>
</head>
<body>
    <h1>Password Changed Successfully</h1>
    <p>Your password has been changed on {{ $details['time'] }}.</p>
    <p>If this was not you, please contact our support team immediately.</p>
    <p>IP Address: {{ $details['ip'] }}</p>
</body>
</html>
