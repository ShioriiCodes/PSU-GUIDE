<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Student Password Reset Request</title>
</head>
<body>
    <h2>Password Reset Request</h2>
    <p>A student has requested a password reset.</p>

    <p>
        <strong>Email:</strong> {{ $email }} <br>
        <strong>Requested At:</strong> {{ now()->toDayDateTimeString() }}
    </p>

    <p>Please log in to PSU Guide admin panel to handle this request.</p>

    <br>
    <p>— PSU Guide System</p>
</body>
</html>
