<!DOCTYPE html>
<html>
<head>
    <title>Moderator Details</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        td, th { border: 1px solid #ccc; padding: 8px; }
        th { background-color: #f5f5f5; text-align: left; }
    </style>
</head>
<body>
    <h2>Moderator Details</h2>
    <table>
        <tr><th>ID</th><td>{{ $moderator->id }}</td></tr>
        <tr><th>Name</th><td>{{ $moderator->name }}</td></tr>
        <tr><th>Email</th><td>{{ $moderator->email }}</td></tr>
        <tr><th>Department</th><td>{{ $moderator->department->name ?? 'N/A' }}</td></tr>
        <tr><th>Role</th><td>{{ ucfirst($moderator->role) }}</td></tr>
        <tr><th>Status</th><td>{{ ucfirst($moderator->status) }}</td></tr>
        <tr><th>Created At</th><td>{{ $moderator->created_at }}</td></tr>
        <tr><th>Updated At</th><td>{{ $moderator->updated_at }}</td></tr>
    </table>
</body>
</html>
