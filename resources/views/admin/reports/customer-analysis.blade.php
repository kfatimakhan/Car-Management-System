@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Customer Analysis Report</h1>

    <!-- Export Options -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Export Options</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.reports.export-pdf') }}" class="d-inline">
                @csrf
                <input type="hidden" name="report_type" value="customers">
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-file-pdf mr-1"></i> Export as PDF
                </button>
            </form>
            <form method="POST" action="{{ route('admin.reports.export-csv') }}" class="d-inline ml-2">
                @csrf
                <input type="hidden" name="report_type" value="customers">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-file-csv mr-1"></i> Export as CSV
                </button>
            </form>
        </div>
    </div>

    <!-- Customer Stats -->
    <div class="row">
        <!-- Total Customers Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Customers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCustomers }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Repeat Customers Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Repeat Customers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $repeatCustomers }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Retention Rate Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Retention Rate</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ number_format($retentionRate, 1) }}%</div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: {{ $retentionRate }}%" aria-valuenow="{{ $retentionRate }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percent fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Average Revenue per Customer Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Avg. Revenue per Customer</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${{ number_format($topCustomersByRevenue->sum('rentals_sum_total_amount') / ($topCustomersByRevenue->count() ?: 1), 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <!-- New Customers Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">New Customers (Last 12 Months)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="newCustomersChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Customers Distribution Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Revenue Distribution (Top 10 Customers)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4">
                        <canvas id="customerDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Customers by Revenue -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Top Customers by Revenue</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="topCustomersTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Rentals</th>
                            <th>Total Spent</th>
                            <th>Avg. Spent per Rental</th>
                            <th>Registration Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topCustomersByRevenue as $customer)
                        <tr>
                            <td>
                                <a href="{{ route('admin.customers.show', $customer->id) }}">
                                    {{ $customer->name }}
                                </a>
                            </td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->rentals_count }}</td>
                            <td>${{ number_format($customer->rentals_sum_total_amount, 2) }}</td>
                            <td>
                                ${{ number_format($customer->rentals_count > 0 ? $customer->rentals_sum_total_amount / $customer->rentals_count : 0, 2) }}
                            </td>
                            <td>{{ $customer->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Top Customers by Rental Count -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Top Customers by Rental Count</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="topCustomersByCountTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Rentals</th>
                            <th>Total Spent</th>
                            <th>Avg. Spent per Rental</th>
                            <th>Registration Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topCustomersByCount as $customer)
                        <tr>
                            <td>
                                <a href="{{ route('admin.customers.show', $customer->id) }}">
                                    {{ $customer->name }}
                                </a>
                            </td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->rentals_count }}</td>
                            <td>${{ number_format($customer->rentals_sum_total_amount, 2) }}</td>
                            <td>
                                ${{ number_format($customer->rentals_count > 0 ? $customer->rentals_sum_total_amount / $customer->rentals_count : 0, 2) }}
                            </td>
                            <td>{{ $customer->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
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
    // New Customers Chart
    var ctx = document.getElementById('newCustomersChart').getContext('2d');
    var newCustomersChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_column($newCustomersData, 'month')) !!},
            datasets: [{
                label: 'New Customers',
                data: {!! json_encode(array_column($newCustomersData, 'count')) !!},
                backgroundColor: 'rgba(78, 115, 223, 0.8)',
                borderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 1
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
                        stepSize: 5
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Customer Distribution Chart
    var ctxPie = document.getElementById('customerDistributionChart').getContext('2d');
    var customerDistributionChart = new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($topCustomersByRevenue->take(10)->pluck('name')) !!},
            datasets: [{
                data: {!! json_encode($topCustomersByRevenue->take(10)->pluck('rentals_sum_total_amount')) !!},
                backgroundColor: [
                    '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b',
                    '#5a5c69', '#858796', '#6f42c1', '#20c9a6', '#f8f9fc'
                ],
                hoverBackgroundColor: [
                    '#2e59d9', '#17a673', '#2c9faf', '#dda20a', '#be2617',
                    '#3a3b45', '#60616f', '#5d36a4', '#169b80', '#d8daeb'
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
                            var label = context.label || '';
                            var value = '$' + context.parsed.toFixed(2);
                            return label + ': ' + value;
                        }
                    }
                },
                legend: {
                    display: false
                }
            },
            cutout: '70%',
        }
    });

    // Initialize DataTables
    $(document).ready(function() {
        $('#topCustomersTable').DataTable({
            "pageLength": 10,
            "order": [[ 3, "desc" ]]
        });

        $('#topCustomersByCountTable').DataTable({
            "pageLength": 10,
            "order": [[ 2, "desc" ]]
        });
    });
</script>
@endsection
