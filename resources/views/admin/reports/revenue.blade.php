@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Revenue Report</h1>

    <!-- Date Range Filter -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Date Range</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reports.revenue') }}" class="form-inline">
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
                <input type="hidden" name="report_type" value="revenue">
                <input type="hidden" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
                <input type="hidden" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-file-pdf mr-1"></i> Export as PDF
                </button>
            </form>
            <form method="POST" action="{{ route('admin.reports.export-csv') }}" class="d-inline ml-2">
                @csrf
                <input type="hidden" name="report_type" value="revenue">
                <input type="hidden" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
                <input type="hidden" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-file-csv mr-1"></i> Export as CSV
                </button>
            </form>
        </div>
    </div>

    <!-- Revenue Summary -->
    <div class="row">
        <!-- Total Revenue Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Revenue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">\${{ number_format($totalRevenue, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Average Daily Revenue Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Average Daily Revenue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($avgDailyRevenue, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Date Range Card -->
        <div class="col-xl-6 col-md-12 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Date Range</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }}
                                <span class="text-xs text-gray-600 ml-2">({{ $startDate->diffInDays($endDate) + 1 }} days)</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Charts -->
    <div class="row">
        <!-- Daily Revenue Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Daily Revenue</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="dailyRevenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue by brand Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Revenue by Car brand</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4">
                        <canvas id="revenueBybrandChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        @foreach($brandRevenue as $index => $brand)
                            <span class="mr-2">
                                <i class="fas fa-circle" style="color: {{ 'hsl(' . (360 * $index / count($brandRevenue)) . ', 70%, 50%)' }}"></i> {{ $brand->brand }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue by brand Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Revenue by Car brand</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>brand</th>
                            <th>Number of Rentals</th>
                            <th>Total Revenue</th>
                            <th>Average Revenue per Rental</th>
                            <th>Percentage of Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($brandRevenue as $brand)
                        <tr>
                            <td>{{ $brand->brand }}</td>
                            <td>{{ $brand->count }}</td>
                            <td>${{ number_format($brand->revenue, 2) }}</td>
                            <td>${{ number_format($brand->count > 0 ? $brand->revenue / $brand->count : 0, 2) }}</td>
                            <td>{{ number_format(($brand->revenue / $totalRevenue) * 100, 1) }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Daily Revenue Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daily Revenue</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dailyRevenueTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dailyRevenue as $data)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($data->date)->format('M d, Y') }}</td>
                            <td>${{ number_format($data->revenue, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Daily Revenue Chart
    var ctx = document.getElementById('dailyRevenueChart').getContext('2d');
    var dailyRevenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($dailyRevenue->pluck('date')->map(function($date) { return \Carbon\Carbon::parse($date)->format('M d'); })) !!},
            datasets: [{
                label: 'Revenue ($)',
                data: {!! json_encode($dailyRevenue->pluck('revenue')) !!},
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderColor: 'rgba(78, 115, 223, 1)',
                pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 2,
                fill: true
            }]
        },
        options: {
            maintainAspectRatio: false,
            layout: {
                padding: {
                    left: 10,
                    right: 25,
                    top: 25,
                    bottom: 0
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: "rgb(234, 236, 244)",
                        zeroLineColor: "rgb(234, 236, 244)",
                        drawBorder: false,
                        borderDash: [2],
                        zeroLineBorderDash: [2]
                    },
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

    // Revenue by brand Chart
    var ctxPie = document.getElementById('revenueBybrandChart').getContext('2d');
    var revenueBybrandChart = new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($brandRevenue->pluck('brand')) !!},
            datasets: [{
                data: {!! json_encode($brandRevenue->pluck('revenue')) !!},
                backgroundColor: [
                    @foreach($brandRevenue as $index => $brand)
                        'hsl({{ 360 * $index / count($brandRevenue) }}, 70%, 50%)',
                    @endforeach
                ],
                hoverBackgroundColor: [
                    @foreach($brandRevenue as $index => $brand)
                        'hsl({{ 360 * $index / count($brandRevenue) }}, 70%, 40%)',
                    @endforeach
                ],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return '$' + context.parsed.toFixed(2);
                        }
                    }
                },
                legend: {
                    position: 'bottom',
                    display: false
                }
            },
            cutoutPercentage: 80,
        }
    });

    // Initialize DataTable
    $(document).ready(function() {
        $('#dailyRevenueTable').DataTable({
            "order": [[ 0, "desc" ]],
            "pageLength": 10
        });
    });
</script>
@endsection
