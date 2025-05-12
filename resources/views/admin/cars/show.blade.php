@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Car Details</h1>
        <div>
            <a href="{{ route('admin.cars.edit', $car) }}" class="btn btn-primary btn-sm shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit Car
            </a>
            <a href="{{ route('admin.cars.index') }}" class="btn btn-secondary btn-sm shadow-sm ml-2">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Cars
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Car Image</h6>
                </div>
                <div class="card-body text-center">
                    <img src="{{ $car->image ? asset('storage/' . $car->image) : asset('images/car-placeholder.jpg') }}"
                         alt="{{ $car->name }}" class="img-fluid rounded">
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Car Information</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 30%;">Car Name</th>
                                <td>{{ $car->name }}</td>
                            </tr>
                            <tr>
                                <th>Brand</th>
                                <td>{{ $car->brand }}</td>
                            </tr>
                            <tr>
                                <th>Model</th>
                                <td>{{ $car->model }}</td>
                            </tr>
                            <tr>
                                <th>Year</th>
                                <td>{{ $car->year }}</td>
                            </tr>
                            <tr>
                                <th>Car Type</th>
                                <td>{{ $car->car_type }}</td>
                            </tr>
                            <tr>
                                <th>Daily Rent Price</th>
                                <td>${{ number_format($car->daily_rent_price, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Availability</th>
                                <td>
                                    @if($car->availability)
                                        <span class="badge badge-success">Available</span>
                                    @else
                                        <span class="badge badge-danger">Not Available</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{{ $car->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Last Updated</th>
                                <td>{{ $car->updated_at->format('M d, Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rental History -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Rental History</h6>
        </div>
        <div class="card-body">
            @if($car->rentals->isEmpty())
                <div class="alert alert-info">
                    This car has not been rented yet.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Total Cost</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($car->rentals as $rental)
                            <tr>
                                <td>{{ $rental->id }}</td>
                                <td>{{ $rental->user->name }}</td>
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
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
