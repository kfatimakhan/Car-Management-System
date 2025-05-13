<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Car Fleet Report</title>
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
            width: 22%;
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
            font-size: 11px;
        }
        th {
            background-color: #f8f9fc;
            color: #4e73df;
        }
        tr:nth-child(even) {
            background-color: #f8f9fc;
        }
        .status-available {
            color: #1cc88a;
            font-weight: bold;
        }
        .status-rented {
            color: #e74a3b;
            font-weight: bold;
        }
        .status-maintenance {
            color: #f6c23e;
            font-weight: bold;
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
        .brand-header {
            background-color: #eaecf4;
            font-weight: bold;
            color: #4e73df;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Car Fleet Report</h1>
        <p>Report Type: {{ $reportType ?? 'Complete Fleet' }}</p>
        <p>Generated on: {{ now()->format('F d, Y h:i A') }}</p>
    </div>

    <div class="summary">
        <div class="summary-box">
            <h3>Total Cars</h3>
            <p>{{ $totalCars ?? 0 }}</p>
        </div>
        <div class="summary-box">
            <h3>Available Cars</h3>
            <p>{{ $availableCars ?? 0 }}</p>
        </div>
        <div class="summary-box">
            <h3>Rented Cars</h3>
            <p>{{ $rentedCars ?? 0 }}</p>
        </div>
        <div class="summary-box">
            <h3>In Maintenance</h3>
            <p>{{ $maintenanceCars ?? 0 }}</p>
        </div>
    </div>

    <h2>Fleet Overview by brand</h2>
    @forelse($carsBybrand ?? [] as $brand => $cars)
    <h3>{{ $brand }}</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Make & Model</th>
                <th>Year</th>
                <th>License Plate</th>
                <th>Color</th>
                <th>Daily Rate</th>
                <th>Status</th>
                <th>Last Maintenance</th>
                <th>Next Maintenance</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cars as $car)
            <tr>
                <td>{{ $car->id ?? 'N/A' }}</td>
                <td>{{ $car->make ?? '' }} {{ $car->model ?? '' }}</td>
                <td>{{ $car->year ?? 'N/A' }}</td>
                <td>{{ $car->license_plate ?? 'N/A' }}</td>
                <td>{{ $car->color ?? 'N/A' }}</td>
                <td>${{ number_format($car->daily_rate ?? 0, 2) }}</td>
                <td class="status-{{ strtolower($car->status ?? 'unknown') }}">
                    {{ $car->status ?? 'Unknown' }}
                </td>
                <td>{{ isset($car->last_maintenance) ? date('M d, Y', strtotime($car->last_maintenance)) : 'N/A' }}</td>
                <td>{{ isset($car->next_maintenance) ? date('M d, Y', strtotime($car->next_maintenance)) : 'N/A' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center;">No cars in this brand</td>
            </tr>
            @endforelse
        </tbody>
    </table>
@empty
    <p style="text-align: center;">No car data available</p>
@endforelse

    <h2>Top Performing Cars</h2>
    <table>
        <thead>
            <tr>
                <th>Car</th>
                <th>brand</th>
                <th>License Plate</th>
                <th>Total Bookings</th>
                <th>Total Revenue</th>
                <th>Utilization Rate</th>
                <th>Avg. Rating</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topCars ?? [] as $car)
            <tr>
                <td>{{ is_object($car) ? ($car->model ?? '') . ' (' . ($car->year ?? '') . ')' : 'N/A' }}</td>
                <td>{{ $car->brand ?? 'N/A' }}</td>
                <td>{{ $car->license_plate ?? 'N/A' }}</td>
                <td>{{ $car->booking_count ?? 0 }}</td>
                <td>${{ number_format($car->total_revenue ?? 0, 2) }}</td>
                <td>{{ isset($car->utilization_rate) ? number_format($car->utilization_rate, 1) . '%' : '0%' }}</td>
                <td>{{ isset($car->avg_rating) ? number_format($car->avg_rating, 1) . '/5' : 'N/A' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center;">No data available</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="page-break"></div>

    <h2>Complete Car Inventory</h2>

    @forelse($carsBybrand ?? [] as $brand => $cars)
        <h3>{{ $brand }}</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Make & Model</th>
                    <th>Year</th>
                    <th>License Plate</th>
                    <th>Color</th>
                    <th>Daily Rate</th>
                    <th>Status</th>
                    <th>Last Maintenance</th>
                    <th>Next Maintenance</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cars as $car)
                <tr>
                    <td>{{ $car->id ?? 'N/A' }}</td>
                    <td>{{ $car->make ?? '' }} {{ $car->model ?? '' }}</td>
                    <td>{{ $car->year ?? 'N/A' }}</td>
                    <td>{{ $car->license_plate ?? 'N/A' }}</td>
                    <td>{{ $car->color ?? 'N/A' }}</td>
                    <td>${{ number_format($car->daily_rate ?? 0, 2) }}</td>
                    <td class="status-{{ strtolower($car->status ?? 'unknown') }}">
                        {{ $car->status ?? 'Unknown' }}
                    </td>
                    <td>{{ isset($car->last_maintenance) ? date('M d, Y', strtotime($car->last_maintenance)) : 'N/A' }}</td>
                    <td>{{ isset($car->next_maintenance) ? date('M d, Y', strtotime($car->next_maintenance)) : 'N/A' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align: center;">No cars in this brand</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    @empty
        <p style="text-align: center;">No car data available</p>
    @endforelse

    <div class="page-break"></div>

    <h2>Maintenance Schedule (Next 30 Days)</h2>
    <table>
        <thead>
            <tr>
                <th>Car</th>
                <th>License Plate</th>
                <th>Maintenance Type</th>
                <th>Scheduled Date</th>
                <th>Estimated Cost</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @forelse($upcomingMaintenance ?? [] as $maintenance)
            <tr>
                <td>{{ isset($maintenance->car) && is_object($maintenance->car) ? ($maintenance->car->model ?? '') : 'N/A' }}</td>
                <td>{{ $maintenance->car->license_plate ?? 'N/A' }}</td>
                <td>{{ $maintenance->type ?? 'Regular Maintenance' }}</td>
                <td>{{ is_object($maintenance) && isset($maintenance->scheduled_date) ? date('M d, Y', strtotime($maintenance->scheduled_date)) : 'N/A' }}</td>
                <td>${{ number_format($maintenance->estimated_cost ?? 0, 2) }}</td>
                <td>{{ $maintenance->notes ?? 'N/A' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">No upcoming maintenance scheduled</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <h2>Recent Acquisitions (Last 90 Days)</h2>
    <table>
        <thead>
            <tr>
                <th>Car</th>
                <th>License Plate</th>
                <th>Acquisition Date</th>
                <th>Purchase Price</th>
                <th>Supplier</th>
                <th>Current Value</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentAcquisitions ?? [] as $acquisition)
            <tr>
                <td>{{ $acquisition->car->model ?? '' }}</td>
                <td>{{ $acquisition->car->license_plate ?? 'N/A' }}</td>
                <td>{{ isset($acquisition->acquisition_date) ? date('M d, Y', strtotime($acquisition->acquisition_date)) : 'N/A' }}</td>
                <td>${{ number_format($acquisition->purchase_price ?? 0, 2) }}</td>
                <td>{{ $acquisition->supplier ?? 'N/A' }}</td>
                <td>${{ number_format($acquisition->current_value ?? 0, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">No recent acquisitions</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>© {{ date('Y') }} {{ config('app.name') }} - Confidential Fleet Report</p>
        <p>This report contains confidential business information and should not be shared without authorization.</p>
    </div>
</body>
</html>
