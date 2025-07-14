<!DOCTYPE html>
<html>
<head>
    <title>Student Details</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        td, th { border: 1px solid #ccc; padding: 8px; }
        th { background-color: #f5f5f5; text-align: left; }
    </style>
</head>
<body>
    <h2>Student Details</h2>
    <table>
        <tr><th>ID</th><td>{{ $student->id }}</td></tr>
        <tr><th>Name</th><td>{{ $student->name }}</td></tr>
        <tr><th>Email</th><td>{{ $student->email }}</td></tr>
        <tr><th>Student Number</th><td>{{ $student->student_number }}</td></tr>
        <tr><th>Department</th><td>{{ $student->department->name ?? 'N/A' }}</td></tr>
        <tr><th>Role</th><td>{{ ucfirst($student->role) }}</td></tr>
        <tr><th>Status</th><td>{{ ucfirst($student->status) }}</td></tr>
        <tr><th>Email Verified At</th><td>{{ $student->email_verified_at ?? 'Not Verified' }}</td></tr>
        <tr><th>Created At</th><td>{{ $student->created_at }}</td></tr>
        <tr><th>Updated At</th><td>{{ $student->updated_at }}</td></tr>
    </table>
</body>
</html>
