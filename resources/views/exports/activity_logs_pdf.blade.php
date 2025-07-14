<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 6px; }
    </style>
</head>
<body>
    <h2>Activity Logs</h2>
    <table>
        <thead>
            <tr>
                <th>Timestamp</th>
                <th>User</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logs as $log)
                <tr>
                    <td>{{ $log->timestamp }}</td>
                    <td>{{ $log->user->name ?? 'Guest' }}</td>
                    <td>{{ ucfirst($log->user->role ?? 'Public') }}</td>
                    <td>{{ $log->action }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
