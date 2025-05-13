
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Revenue Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
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
        }
        .summary-box {
            display: inline-block;
            width: 30%;
            margin-right: 3%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
        }
        .summary-box h3 {
            margin: 0;
            font-size: 14px;
            color: #666;
        }
        .summary-box p {
            margin: 5px 0 0;
            font-size: 18px;
            font-weight: bold;
            color: #4e73df;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fc;
            color: #4e73df;
        }
        tr:nth-child(even) {
            background-color: #f8f9fc;
        }
        .chart-container {
            margin-bottom: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 10px;
            color: #666;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Revenue Report</h1>
        <p>Period: {{ ucfirst($period ?? 'Monthly') }}</p>
        <p>{{ $startDate ?? date('Y-m-d') }} to {{ $endDate ?? date('Y-m-d') }}</p>
        <p>Generated on: {{ now()->format('F d, Y h:i A') }}</p>
    </div>

    <div class="summary">
        <div class="summary-box">
            <h3>Total Bookings</h3>
            <p>{{ $totalBookings ?? 0 }}</p>
        </div>
        <div class="summary-box">
            <h3>Total Revenue</h3>
            <p>${{ number_format($totalRevenue ?? 0, 2) }}</p>
        </div>
        <div class="summary-box">
            <h3>Average Booking Value</h3></h3>
            <p>${{ isset($totalBookings) && isset($totalRevenue) && $totalBookings > 0 ? number_format($totalRevenue / $totalBookings, 2) : '0.00' }}</p>
        </div>
    </div>

    <h2>Revenue by Category</h2>
    <table>
        <thead>
            <tr>
                <th>Category</th>
                <th>Bookings</th>
                <th>Revenue</th>
                <th>% of Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($revenueByCategory ?? [] as $category => $data)
            <tr>
                <td>{{ $category }}</td>
                <td>{{ $data['count'] }}</td>
                <td>${{ number_format($data['revenue'], 2) }}</td>
                <td>{{ isset($totalRevenue) && $totalRevenue > 0 ? number_format(($data['revenue'] / $totalRevenue) * 100, 1) : '0' }}%</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">No data available</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <h2>Top Performing Cars</h2>
    <table>
        <thead>
            <tr>
                <th>Car</th>
                <th>Category</th>
                <th>Bookings</th>
                <th>Revenue</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topCars ?? [] as $car)
            <tr>
                <td>{{ is_object($car) && property_exists($car, 'model') ? $car->model : 'N/A' }} ({{ is_object($car) && property_exists($car, 'year') ? $car->year : 'N/A' }})</td>
                <td>{{ is_object($car) && property_exists($car, 'category') ? $car->category : 'N/A' }}</td>
                <td>{{ is_object($car) && property_exists($car, 'booking_count') ? $car->booking_count : 0 }}</td>
                <td>${{ number_format($car->total_revenue ?? 0, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">No data available</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="page-break"></div>

    <h2>Top Customers</h2>
    <table>
        <thead>
            <tr>
                <th>Customer</th>
                <th>Email</th>
                <th>Bookings</th>
                <th>Total Spent</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topCustomers ?? [] as $customer)
            <tr>
                <td>{{ is_object($customer) && property_exists($customer, 'name') ? $customer->name : 'N/A' }}</td>
                <td>{{ $customer->email ?? 'N/A' }}</td>
                <td>{{ $customer->booking_count ?? 0 }}</td>
                <td>${{ number_format($customer->total_spent ?? 0, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">No data available</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <h2>Recent Bookings</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Car</th>
                <th>Dates</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse(array_slice($bookings ?? [], 0, 10) as $booking)
            <tr>
                <td>{{ is_object($booking) && property_exists($booking, 'id') ? $booking->id : 'N/A' }}</td>
                <td>{{ $booking->user->name ?? 'N/A' }}</td>
                <td>{{ $booking->car->make ?? 'N/A' }} {{ $booking->car->model ?? 'N/A' }}</td>
                <td>{{ isset($booking->start_date) ? $booking->start_date->format('M d, Y') : 'N/A' }} - {{ isset($booking->end_date) ? $booking->end_date->format('M d, Y') : 'N/A' }}</td>
                <td>${{ number_format($booking->total_price ?? 0, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">No data available</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>© {{ date('Y') }} {{ config('app.name') }} - Confidential Revenue Report</p>
        <p>This report contains confidential business information and should not be shared without authorization.</p>
    </div>
</body>
</html>
