@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Cars Performance Report</h1>

    <!-- Date Range Filter -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Date Range</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reports.cars-performance') }}" class="form-inline">
                <div class="form-group mb-2 mr-2">
                    <label for="start_date" class="sr-only">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" required>
                </div>
                <div class="form-group mb-2 mr-2">
                    <label for="end_date" class="sr-only">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" required>
                </div>
                <button type="submit" class="btn btn-primary mb-2">Apply Date Range</button>
            </form>
        </div>
    </div>

    <!-- Export Options -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Export Options</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.reports.export-pdf') }}" class="d-inline">
                @csrf
                <input type="hidden" name="report_type" value="cars">
                <input type="hidden" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
                <input type="hidden" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-file-pdf mr-1"></i> Export as PDF
                </button>
            </form>
            <form method="POST" action="{{ route('admin.reports.export-csv') }}" class="d-inline ml-2">
                @csrf
                <input type="hidden" name="report_type" value="cars">
                <input type="hidden" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
                <input type="hidden" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-file-csv mr-1"></i> Export as CSV
                </button>
            </form>
        </div>
    </div>

    <!-- Cars Performance Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Cars Performance</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="carsPerformanceTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Car</th>
                            <th>License Plate</th>
                            <th>Category</th>
                            <th>Rentals</th>
                            <th>Revenue</th>
                            <th>Utilization Rate</th>
                            <th>Revenue per Day</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($carsPerformance as $car)
                        <tr>
                            <td>
                                <a href="{{ route('admin.cars.show', $car->id) }}">
                                    {{ $car->make }} {{ $car->model }} ({{ $car->year }})
                                </a>
                            </td>
                            <td>{{ $car->license_plate }}</td>
                            <td>{{ $car->category }}</td>
                            <td>{{ $car->rentals_count }}</td>
                            <td>${{ number_format($car->rentals_sum_total_amount, 2) }}</td>
                            <td>
                                <div class="progress mb-1">
                                    <div class="progress-bar bg-{{ $car->utilization_rate < 30 ? 'danger' : ($car->utilization_rate < 60 ? 'warning' : 'success') }}"
                                         role="progressbar"
                                         style="width: {{ $car->utilization_rate }}%"
                                         aria-valuenow="{{ $car->utilization_rate }}"
                                         aria-valuemin="0"
                                         aria-valuemax="100">
                                    </div>
                                </div>
                                {{ number_format($car->utilization_rate, 1) }}%
                            </td>
                            <td>${{ number_format($car->revenue_per_day, 2) }}</td>
                            <td>
                                <a href="{{ route('admin.cars.show', $car->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $carsPerformance->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    <!-- Top Performing Cars Chart -->
    <div class="row">
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top 10 Cars by Revenue</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="topCarsByRevenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top 10 Cars by Utilization Rate</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="topCarsByUtilizationChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Prepare data for charts
    var cars = {!! json_encode($carsPerformance->take(10)->map(function($car) {
        return [
            'label' => $car->make . ' ' . $car->model,
            'revenue' => $car->rentals_sum_total_amount,
            'utilization' => $car->utilization_rate
        ];
    })) !!};

    // Sort cars by revenue and utilization
    var carsByRevenue = [...cars].sort((a, b) => b.revenue - a.revenue).slice(0, 10);
    var carsByUtilization = [...cars].sort((a, b) => b.utilization - a.utilization).slice(0, 10);

    // Top Cars by Revenue Chart
    var ctxRevenue = document.getElementById('topCarsByRevenueChart').getContext('2d');
    var topCarsByRevenueChart = new Chart(ctxRevenue, {
        type: 'bar',
        data: {
            labels: carsByRevenue.map(car => car.label),
            datasets: [{
                label: 'Revenue ($)',
                data: carsByRevenue.map(car => car.revenue),
                backgroundColor: 'rgba(78, 115, 223, 0.8)',
                borderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 1
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value;
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return '$' + context.parsed.y.toFixed(2);
                        }
                    }
                }
            }
        }
    });

    // Top Cars by Utilization Chart
    var ctxUtilization = document.getElementById('topCarsByUtilizationChart').getContext('2d');
    var topCarsByUtilizationChart = new Chart(ctxUtilization, {
        type: 'bar',
        data: {
            labels: carsByUtilization.map(car => car.label),
            datasets: [{
                label: 'Utilization Rate (%)',
                data: carsByUtilization.map(car => car.utilization),
                backgroundColor: 'rgba(28, 200, 138, 0.8)',
                borderColor: 'rgba(28, 200, 138, 1)',
                borderWidth: 1
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y.toFixed(1) + '%';
                        }
                    }
                }
            }
        }
    });

    // Initialize DataTable
    $(document).ready(function() {
        $('#carsPerformanceTable').DataTable({
            "paging": false,
            "ordering": true,
            "info": false,
            "searching": true
        });
    });
</script>
@endsection
