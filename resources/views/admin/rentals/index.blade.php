
@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Rental Management</h1>
        <a href="{{ route('admin.rentals.create') }}" class="d-none d-sm-inline-block btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Add New Rental
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">All Rentals</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-filter fa-sm fa-fw text-gray-400"></i> Filter
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                    aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">Status:</div>
                    <a class="dropdown-item {{ request('status') == 'all' || !request('status') ? 'active' : '' }}"
                       href="{{ route('admin.rentals.index', ['status' => 'all']) }}">All</a>
                    <a class="dropdown-item {{ request('status') == 'ongoing' ? 'active' : '' }}"
                       href="{{ route('admin.rentals.index', ['status' => 'ongoing']) }}">Ongoing</a>
                    <a class="dropdown-item {{ request('status') == 'completed' ? 'active' : '' }}"
                       href="{{ route('admin.rentals.index', ['status' => 'completed']) }}">Completed</a>
                    <a class="dropdown-item {{ request('status') == 'canceled' ? 'active' : '' }}"
                       href="{{ route('admin.rentals.index', ['status' => 'canceled']) }}">Canceled</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Car</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Total Cost</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rentals as $rental)
                        <tr>
                            <td>{{ $rental->id }}</td>
                            <td>
                                <a href="{{ route('admin.customers.show', $rental->user) }}">
                                    {{ $rental->user->name }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('admin.cars.show', $rental->car) }}">
                                    {{ $rental->car->brand }} {{ $rental->car->model }}
                                </a>
                            </td>
                            <td>{{ $rental->start_date->format('M d, Y') }}</td>
                            <td>{{ $rental->end_date->format('M d, Y') }}</td>
                            <td>${{ number_format($rental->total_cost, 2) }}</td>
                            <td>
                                @if($rental->status == 'ongoing')
                                <span class="badge badge-primary">Ongoing</span>
                                @elseif($rental->status == 'completed')
                                <span class="badge badge-success">Completed</span>
                                @else
                                <span class="badge badge-danger">Canceled</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.rentals.show', $rental) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.rentals.edit', $rental) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.rentals.destroy', $rental) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this rental?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $rentals->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "paging": false,
            "ordering": true,
            "info": false
        });
    });
</script>
@endsection

