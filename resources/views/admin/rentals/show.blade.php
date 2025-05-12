@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Rental Details</h1>
        <div>
            <a href="{{ route('admin.rentals.edit', $rental) }}" class="btn btn-primary btn-sm shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit Rental
            </a>
            <a href="{{ route('admin.rentals.index') }}" class="btn btn-secondary btn-sm shadow-sm ml-2">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Rentals
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Rental Information</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 30%;">Rental ID</th>
                                <td>{{ $rental->id }}</td>
                            </tr>
                            <tr>
                                <th>Start Date</th>
                                <td>{{ $rental->start_date->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <th>End Date</th>
                                <td>{{ $rental->end_date->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <th>Duration</th>
                                <td>{{ $rental->start_date->diffInDays($rental->end_date) + 1 }} days</td>
                            </tr>
                            <tr>
                                <th>Total Cost</th>
                                <td>${{ number_format($rental->total_cost, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if($rental->status == 'ongoing')
                                    <span class="badge badge-primary">Ongoing</span>
                                    @elseif($rental->status == 'completed')
                                    <span class="badge badge-success">Completed</span>
                                    @else
                                    <span class="badge badge-danger">Canceled</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{{ $rental->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Last Updated</th>
                                <td>{{ $rental->updated_at->format('M d, Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Customer Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="mr-3">
                                    <img class="rounded-circle" src="{{ asset('images/undraw_profile.svg') }}" width="60" height="60">
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ $rental->user->name }}</h5>
                                    <p class="text-muted mb-0">{{ $rental->user->email }}</p>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <th style="width: 30%;">Phone</th>
                                        <td>{{ $rental->user->phone ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Address</th>
                                        <td>{{ $rental->user->address ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Rentals</th>
                                        <td>{{ $rental->user->rentals->count() }}</td>
                                    </tr>
                                </table>
                            </div>

                            <a href="{{ route('admin.customers.show', $rental->user) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-user fa-sm"></i> View Customer Profile
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Car Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="mr-3">
                                    <img src="{{ $rental->car->image ? asset('storage/' . $rental->car->image) : asset('images/car-placeholder.jpg') }}"
                                         alt="{{ $rental->car->name }}" class="img-thumbnail" width="100">
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ $rental->car->brand }} {{ $rental->car->model }}</h5>
                                    <p class="text-muted mb-0">{{ $rental->car->year }} · {{ $rental->car->car_type }}</p>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <th style="width: 30%;">Daily Rate</th>
                                        <td>${{ number_format($rental->car->daily_rent_price, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Current Status</th>
                                        <td>
                                            @if($rental->car->availability)
                                                <span class="badge badge-success">Available</span>
                                            @else
                                                <span class="badge badge-danger">Not Available</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <a href="{{ route('admin.cars.show', $rental->car) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-car fa-sm"></i> View Car Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
