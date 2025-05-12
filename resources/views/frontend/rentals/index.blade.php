@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">My Rentals</h1>

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

    <div class="card shadow-sm">
        <div class="card-body">
            <ul class="nav nav-tabs mb-4" id="rentalTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming"
                            type="button" role="tab" aria-controls="upcoming" aria-selected="true">
                        Upcoming & Ongoing
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="past-tab" data-bs-toggle="tab" data-bs-target="#past"
                            type="button" role="tab" aria-controls="past" aria-selected="false">
                        Past Rentals
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="canceled-tab" data-bs-toggle="tab" data-bs-target="#canceled"
                            type="button" role="tab" aria-controls="canceled" aria-selected="false">
                        Canceled
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="rentalTabsContent">
                <!-- Upcoming & Ongoing Rentals -->
                <div class="tab-pane fade show active" id="upcoming" role="tabpanel" aria-labelledby="upcoming-tab">
                    @php
                        $upcomingRentals = $rentals->filter(function($rental) {
                            return ($rental->status == 'ongoing' && $rental->end_date >= now());
                        });
                    @endphp

                    @if($upcomingRentals->isEmpty())
                    <div class="alert alert-info">
                        You don't have any upcoming or ongoing rentals.
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Car</th>
                                    <th>Pickup Date</th>
                                    <th>Return Date</th>
                                    <th>Total Cost</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upcomingRentals as $rental)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $rental->car->image ? asset('storage/' . $rental->car->image) : asset('images/car-placeholder.jpg') }}"
                                                 alt="{{ $rental->car->name }}" width="60" class="me-3">
                                            <div>
                                                <h6 class="mb-0">{{ $rental->car->brand }} {{ $rental->car->model }}</h6>
                                                <small class="text-muted">{{ $rental->car->year }} · {{ $rental->car->car_type }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $rental->start_date->format('M d, Y') }}</td>
                                    <td>{{ $rental->end_date->format('M d, Y') }}</td>
                                    <td>${{ number_format($rental->total_cost, 2) }}</td>
                                    <td>
                                        @if($rental->status == 'ongoing' && $rental->start_date > now())
                                        <span class="">Upcoming</span>
                                        @else
                                        <span class="">Ongoing</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('rentals.show', $rental) }}" class="btn btn-sm btn-outline-primary">Details</a>

                                        @if($rental->canBeCanceled())
                                        <form action="{{ route('rentals.cancel', $rental) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Are you sure you want to cancel this rental?')">
                                                Cancel
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>

                <!-- Past Rentals -->
                <div class="tab-pane fade" id="past" role="tabpanel" aria-labelledby="past-tab">
                    @php
                        $pastRentals = $rentals->filter(function($rental) {
                            return ($rental->status == 'completed' || ($rental->status == 'ongoing' && $rental->end_date < now()));
                        });
                    @endphp

                    @if($pastRentals->isEmpty())
                    <div class="alert alert-info">
                        You don't have any past rentals.
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Car</th>
                                    <th>Pickup Date</th>
                                    <th>Return Date</th>
                                    <th>Total Cost</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pastRentals as $rental)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $rental->car->image ? asset('storage/' . $rental->car->image) : asset('images/car-placeholder.jpg') }}"
                                                 alt="{{ $rental->car->name }}" width="60" class="me-3">
                                            <div>
                                                <h6 class="mb-0">{{ $rental->car->brand }} {{ $rental->car->model }}</h6>
                                                <small class="text-muted">{{ $rental->car->year }} · {{ $rental->car->car_type }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $rental->start_date->format('M d, Y') }}</td>
                                    <td>{{ $rental->end_date->format('M d, Y') }}</td>
                                    <td>${{ number_format($rental->total_cost, 2) }}</td>
                                    <td>
                                        @if($rental->status == 'completed')
                                        <span class="badge bg-success">Completed</span>
                                        @else
                                        <span class="badge bg-secondary">Completed</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('rentals.show', $rental) }}" class="btn btn-sm btn-outline-primary">Details</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>

                <!-- Canceled Rentals -->
                <div class="tab-pane fade" id="canceled" role="tabpanel" aria-labelledby="canceled-tab">
                    @php
                        $canceledRentals = $rentals->filter(function($rental) {
                            return $rental->status == 'canceled';
                        });
                    @endphp

                    @if($canceledRentals->isEmpty())
                    <div class="alert alert-info">
                        You don't have any canceled rentals.
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Car</th>
                                    <th>Pickup Date</th>
                                    <th>Return Date</th>
                                    <th>Total Cost</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($canceledRentals as $rental)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $rental->car->image ? asset('storage/' . $rental->car->image) : asset('images/car-placeholder.jpg') }}"
                                                 alt="{{ $rental->car->name }}" width="60" class="me-3">
                                            <div>
                                                <h6 class="mb-0">{{ $rental->car->brand }} {{ $rental->car->model }}</h6>
                                                <small class="text-muted">{{ $rental->car->year }} · {{ $rental->car->car_type }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $rental->start_date->format('M d, Y') }}</td>
                                    <td>{{ $rental->end_date->format('M d, Y') }}</td>
                                    <td>${{ number_format($rental->total_cost, 2) }}</td>
                                    <td>
                                        <span class="badge bg-danger">Canceled</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('rentals.show', $rental) }}" class="btn btn-sm btn-outline-primary">Details</a>
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
