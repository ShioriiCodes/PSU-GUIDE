<!DOCTYPE html>
<html>
<head>
    <title>Faculty Details</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        td, th { border: 1px solid #ccc; padding: 8px; }
        th { background-color: #f5f5f5; text-align: left; }
    </style>
</head>
<body>
    <h2>Faculty Details</h2>
    <table>
        <tr><th>ID</th><td>{{ $faculty->id }}</td></tr>
        <tr><th>Name</th><td>{{ $faculty->name }}</td></tr>
        <tr><th>Email</th><td>{{ $faculty->email }}</td></tr>
        <tr><th>Department</th><td>{{ $faculty->department->name ?? 'N/A' }}</td></tr>
        <tr><th>Role</th><td>{{ ucfirst($faculty->role) }}</td></tr>
        <tr><th>Status</th><td>{{ ucfirst($faculty->status) }}</td></tr>
        <tr><th>Created At</th><td>{{ $faculty->created_at }}</td></tr>
        <tr><th>Updated At</th><td>{{ $faculty->updated_at }}</td></tr>
    </table>
</body>
</html>
