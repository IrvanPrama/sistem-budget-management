<!DOCTYPE html>
<html>
<head>
    <title>Export Budget</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 4px; }
    </style>
</head>
<body>
    <h2>Data Budget</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Proyek</th>
                <th>Client</th>
                <th>Pengeluaran</th>
                <th>Estimasi</th>
                <th>Realisasi</th>
            </tr>
        </thead>
        <tbody>
                @foreach($budgets as $b)
                <tr>
                    <td>{{ $loop->iteration}}</td>
                    <td>{{ $b->project_name }}</td>
                    <td>{{ $b->client }}</td>
                    <td>{{ $b->expense_name }}</td>
                    <td>Rp {{ number_format($b->estimate, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($b->expenses, 0, ',', '.') }}</td>
                </tr>
                @endforeach
        </tbody>
    </table>
</body>
</html>