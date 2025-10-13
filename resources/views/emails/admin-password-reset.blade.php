<!DOCTYPE html>
<html>
<head>
    <title>Password Reset Request</title>
</head>
<body>
    <h2>Password Reset Request</h2>
    <p>Hello Admin,</p>
    <p>User <strong>{{ $user->name }}</strong> ({{ $user->email }}) has requested a password reset.</p>
    <p>Please log in to the admin dashboard to reset their password.</p>
    <p>Thank you!</p>
</body>
</html>
