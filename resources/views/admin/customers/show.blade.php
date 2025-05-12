@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Customer Details</h1>
        <div>
            <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-primary btn-sm shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit Customer
            </a>
            <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary btn-sm shadow-sm ml-2">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Customers
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Customer Information</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img class="img-profile rounded-circle" src="{{ asset('images/undraw_profile.svg') }}" width="100">
                        <h4 class="mt-3">{{ $customer->name }}</h4>
                        <p class="text-muted">{{ $customer->email }}</p>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 30%;">Phone</th>
                                <td>{{ $customer->phone ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>{{ $customer->address ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Registered On</th>
                                <td>{{ $customer->created_at->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <th>Total Rentals</th>
                                <td>{{ $rentals->count() }}</td>
                            </tr>
                            <tr>
                                <th>Total Spent</th>
                                <td>${{ number_format($rentals->where('status', '!=', 'canceled')->sum('total_cost'), 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Rental History</h6>
                </div>
                <div class="card-body">
                    @if($rentals->isEmpty())
                        <div class="alert alert-info">
                            This customer has not made any rentals yet.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
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
    </div>
</div>
@endsection
