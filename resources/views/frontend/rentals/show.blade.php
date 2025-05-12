@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('rentals.index') }}">My Rentals</a></li>
            <li class="breadcrumb-item active" aria-current="page">Rental #{{ $rental->id }}</li>
        </ol>
    </nav>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="card-title mb-0">Rental Details</h2>
                        <div>
                            @if($rental->status == 'ongoing' && $rental->start_date > now())
                            <span class="badge bg-info fs-6">Upcoming</span>
                            @elseif($rental->status == 'ongoing')
                            <span class="badge bg-primary fs-6">Ongoing</span>
                            @elseif($rental->status == 'completed')
                            <span class="badge bg-success fs-6">Completed</span>
                            @else
                            <span class="badge bg-danger fs-6">Canceled</span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h5>Rental Information</h5>
                            <table class="table">
                                <tr>
                                    <th>Rental ID:</th>
                                    <td>{{ $rental->id }}</td>
                                </tr>
                                <tr>
                                    <th>Pickup Date:</th>
                                    <td>{{ $rental->start_date->format('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Return Date:</th>
                                    <td>{{ $rental->end_date->format('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Duration:</th>
                                    <td>{{ $rental->start_date->diffInDays($rental->end_date) + 1 }} days</td>
                                </tr>
                                <tr>
                                    <th>Total Cost:</th>
                                    <td class="fw-bold">${{ number_format($rental->total_cost, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Booking Date:</th>
                                    <td>{{ $rental->created_at->format('M d, Y') }}</td>
                                </tr>
                            </table>

                            @if($rental->canBeCanceled())
                            <form action="{{ route('rentals.cancel', $rental) }}" method="POST" class="mt-3">
                                @csrf
                                <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Are you sure you want to cancel this rental?')">
                                    Cancel Rental
                                </button>
                            </form>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <h5>Car Information</h5>
                            <div class="text-center mb-3">
                                <img src="{{ $rental->car->image ? asset('storage/' . $rental->car->image) : asset('images/car-placeholder.jpg') }}"
                                     alt="{{ $rental->car->name }}" class="img-fluid rounded" style="max-height: 150px;">
                            </div>
                            <table class="table">
                                <tr>
                                    <th>Car:</th>
                                    <td>{{ $rental->car->brand }} {{ $rental->car->model }}</td>
                                </tr>
                                <tr>
                                    <th>Year:</th>
                                    <td>{{ $rental->car->year }}</td>
                                </tr>
                                <tr>
                                    <th>Type:</th>
                                    <td>{{ $rental->car->car_type }}</td>
                                </tr>
                                <tr>
                                    <th>Daily Rate:</th>
                                    <td>${{ number_format($rental->car->daily_rent_price, 2) }}</td>
                                </tr>
                            </table>

                            <div class="mt-3">
                                <a href="{{ route('cars.show', $rental->car) }}" class="btn btn-outline-primary">
                                    View Car Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h4 class="card-title">Rental Summary</h4>
                    <hr>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Car:</span>
                        <span>{{ $rental->car->brand }} {{ $rental->car->model }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Daily Rate:</span>
                        <span>${{ number_format($rental->car->daily_rent_price, 2) }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Rental Days:</span>
                        <span>{{ $rental->start_date->diffInDays($rental->end_date) + 1 }}</span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total Cost:</span>
                        <span>${{ number_format($rental->total_cost, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title">Need Help?</h4>
                    <p>If you have any questions or need assistance with your rental, please don't hesitate to contact our customer support team.</p>
                    <div class="d-grid">
                        <a href="{{ route('contact') }}" class="btn btn-primary">Contact Support</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
