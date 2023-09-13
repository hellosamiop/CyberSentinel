<!DOCTYPE html>
<html>
<head>
    <title>Laravel Logs</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<h1>Laravel Logs</h1>
<form action="{{route('clear-logs')}}" method="get">
    <button type="submit" style="float: right; background-color: #cc3e3e">Clear Logs</button>
</form>
<table>
    <thead>
    <tr>
        <th>Log Entry</th>
    </tr>
    </thead>
    <tbody>
    @foreach($logEntries as $entry)
        <tr>
            <td>{{ $entry }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
