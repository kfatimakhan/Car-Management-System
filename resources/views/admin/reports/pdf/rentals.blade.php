<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rentals Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #4e73df;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .summary {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8f9fc;
            border-radius: 5px;
        }
        .summary-item {
            display: inline-block;
            width: 24%;
            text-align: center;
        }
        .summary-item h3 {
            margin: 0;
            font-size: 14px;
            color: #4e73df;
        }
        .summary-item p {
            margin: 5px 0;
            font-size: 18px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #d1d3e2;
        }
        th {
            background-color: #4e73df;
            color: white;
            font-weight: bold;
            text-align: left;
            padding: 8px;
        }
        td {
            padding: 8px;
        }
        tr:nth-child(even) {
            background-color: #f8f9fc;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
            color: #666;
        }
        .status-active {
            color: #1cc88a;
            font-weight: bold;
        }
        .status-pending {
            color: #f6c23e;
            font-weight: bold;
        }
        .status-completed {
            color: #4e73df;
            font-weight: bold;
        }
        .status-cancelled {
            color: #e74a3b;
            font-weight: bold;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rentals Report</h1>
        <p>Period: {{ $start_date->format('M d, Y') }} - {{ $end_date->format('M d, Y') }}</p>
        <p>Generated: {{ $generated_at->format('M d, Y H:i:s') }}</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <h3>Total Rentals</h3>
            <p>{{ $total_count }}</p>
        </div>
        <div class="summary-item">
            <h3>Active Rentals</h3>
            <p>{{ $status_counts['active'] ?? 0 }}</p>
        </div>
        <div class="summary-item">
            <h3>Completed Rentals</h3>
            <p>{{ $status_counts['completed'] ?? 0 }}</p>
        </div>
        <div class="summary-item">
            <h3>Total Revenue</h3>
            <p>${{ number_format($total_revenue, 2.00) }}</p>
        </div>
    </div>

    <h2>Rentals by Status</h2>
    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th>Count</th>
                <th>Percentage</th>
            </tr>
        </thead>
        <tbody>
            @foreach($status_counts as $status => $count)
            <tr>
                <td>{{ ucfirst($status) }}</td>
                <td>{{ $count }}</td>
                <td>{{ number_format(($count / $total_count) * 100, 1) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Rental Details</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Car</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rentals as $rental)
            <tr>
                <td>{{ $rental->id }}</td>
                <td>{{ $rental->user->name }}</td>
                <td>{{ $rental->car->make }} {{ $rental->car->model }}</td>
                <td>{{ $rental->start_date->format('M d, Y') }}</td>
                <td>{{ $rental->end_date->format('M d, Y') }}</td>
                <td class="status-{{ $rental->status }}">{{ ucfirst($rental->status) }}</td>
                <td>${{ number_format($rental->total_cost, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Car Rental System &copy; {{ date('Y') }} | Confidential Report</p>
    </div>
</body>
</html>
