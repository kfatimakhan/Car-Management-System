
@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>

    <!-- Stats Cards Row -->
    <div class="row">
        <!-- Total Cars Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Cars</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCars }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-car fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Available Cars Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Available Cars</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $availableCars }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Rentals Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Rentals</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalRentals }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Revenue Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
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
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Recent Rentals -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Rentals</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Car</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentRentals as $rental)
                                <tr>
                                    <td>{{ $rental->id }}</td>
                                    <td>{{ $rental->user->name }}</td>
                                    <td>{{ $rental->car->make }} {{ $rental->car->model }}</td>
                                    <td>
                                        @if($rental->status == 'active')
                                            <span class="badge badge-success">Active</span>
                                        @elseif($rental->status == 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($rental->status == 'completed')
                                            <span class="badge badge-info">Completed</span>
                                        @elseif($rental->status == 'cancelled')
                                            <span class="badge badge-danger">Cancelled</span>
                                        @endif
                                    </td>
                                    <td>{{ $rental->created_at->format('M d, Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.rentals.index') }}" class="btn btn-primary btn-sm">View All Rentals</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <a href="{{ route('admin.cars.create') }}" class="btn btn-primary btn-block py-3">
                                <i class="fas fa-plus-circle mr-2"></i> Add New Car
                            </a>
                        </div>
                        <div class="col-md-6 mb-4">
                            <a href="{{ route('admin.customers.create') }}" class="btn btn-success btn-block py-3">
                                <i class="fas fa-user-plus mr-2"></i> Add New User
                            </a>
                        </div>
                        <div class="col-md-6 mb-4">
                            <a href="{{ route('admin.reports.index') }}" class="btn btn-info btn-block py-3">
                                <i class="fas fa-chart-bar mr-2"></i> View Reports
                            </a>
                        </div>
                        <div class="col-md-6 mb-4">
                            <a href="{{ route('admin.settings') }}" class="btn btn-warning btn-block py-3">
                                <i class="fas fa-cog mr-2"></i> Settings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
