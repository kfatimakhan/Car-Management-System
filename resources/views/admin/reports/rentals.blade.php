@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Rentals Report</h1>

    <!-- Filters Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reports.rentals') }}" class="form-inline">
                <div class="form-group mb-2 mr-2">
                    <label for="status" class="sr-only">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="form-group mb-2 mr-2">
                    <label for="start_date" class="sr-only">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}" placeholder="Start Date">
                </div>
                <div class="form-group mb-2 mr-2">
                    <label for="end_date" class="sr-only">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}" placeholder="End Date">
                </div>
                <div class="form-group mb-2 mr-2">
                    <label for="car_id" class="sr-only">Car</label>
                    <select name="car_id" id="car_id" class="form-control">
                        <option value="">All Cars</option>
                        @foreach($cars as $car)
                            <option value="{{ $car->id }}" {{ request('car_id') == $car->id ? 'selected' : '' }}>
                                {{ $car->make }} {{ $car->model }} ({{ $car->year }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary mb-2 mr-2">Apply Filters</button>
                <a href="{{ route('admin.reports.rentals') }}" class="btn btn-secondary mb-2">Reset</a>
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
                <input type="hidden" name="report_type" value="rentals">
                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <input type="hidden" name="car_id" value="{{ request('car_id') }}">
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-file-pdf mr-1"></i> Export as PDF
                </button>
            </form>
            <form method="POST" action="{{ route('admin.reports.export-csv') }}" class="d-inline ml-2">
                @csrf
                <input type="hidden" name="report_type" value="rentals">
                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <input type="hidden" name="car_id" value="{{ request('car_id') }}">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-file-csv mr-1"></i> Export as CSV
                </button>
            </form>
        </div>
    </div>

    <!-- Rentals Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Rentals</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="rentalsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Car</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rentals as $rental)
                        <tr>
                            <td>{{ $rental->id }}</td>
                            <td>
                                <a href="{{ route('admin.customers.show', $rental->user->id) }}">
                                    {{ $rental->user->name }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('admin.cars.show', $rental->car->id) }}">
                                    {{ $rental->car->make }} {{ $rental->car->model }}
                                </a>
                            </td>
                            <td>{{ $rental->start_date->format('M d, Y') }}</td>
                            <td>{{ $rental->end_date->format('M d, Y') }}</td>
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
                            <td>${{ number_format($rental->total_amount, 2) }}</td>
                            <td>{{ $rental->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $rentals->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#rentalsTable').DataTable({
            "paging": false,
            "ordering": true,
            "info": false,
            "searching": true
        });
    });
</script>
@endsection
