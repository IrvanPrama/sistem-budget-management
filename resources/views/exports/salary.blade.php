<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Salary</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 4px; }
    </style>
</head>
<body>
    <h2>Slip Gaji</h2>
     <table>
        <thead>
            <tr>
                <th>Proyek</th>
                <th>Client</th>
                <th>Tgl Transaksi</th>
                <th>ID</th>
                <th>Nama</th>
                <th>Fee</th>
            </tr>
        </thead>
        <tbody>
                @foreach($salary as $s)
                <tr>
                    <td>{{ $s->project_name }}</td>
                    <td>{{ $s->client }}</td>
                    <td>{{ $s->transaction_date }}</td>
                    <td>{{ $s->employee_id }}</td>
                    <td>{{ $s->employee_name }}</td>
                    <td>Rp {{ number_format($s->addon, 0, ',', '.') }}</td>
                </tr>
                @endforeach
        </tbody>
    </table>
</body>
</html>