@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cars.index') }}">Cars</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $car->brand }} {{ $car->model }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h1 class="card-title h2 mb-4">{{ $car->brand }} {{ $car->model }}</h1>

                    <div class="mb-4">
                        <img src="{{ $car->image ? asset('storage/' . $car->image) : asset('images/car-placeholder.jpg') }}"
                             class="img-fluid rounded" alt="{{ $car->name }}">
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h4>Car Details</h4>
                            <table class="table">
                                <tr>
                                    <th>Brand:</th>
                                    <td>{{ $car->brand }}</td>
                                </tr>
                                <tr>
                                    <th>Model:</th>
                                    <td>{{ $car->model }}</td>
                                </tr>
                                <tr>
                                    <th>Year:</th>
                                    <td>{{ $car->year }}</td>
                                </tr>
                                <tr>
                                    <th>Type:</th>
                                    <td>{{ $car->car_type }}</td>
                                </tr>
                                <tr>
                                    <th>Daily Rate:</th>
                                    <td class="text-primary fw-bold">\${{ number_format($car->daily_rent_price, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Availability:</th>
                                    <td>
                                        @if($car->availability)
                                        <span class="badge bg-success">Available</span>
                                        @else
                                        <span class="badge bg-danger">Not Available</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h4>Features</h4>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><i class="fas fa-check-circle text-success me-2"></i> Air Conditioning</li>
                                <li class="list-group-item"><i class="fas fa-check-circle text-success me-2"></i> Power Steering</li>
                                <li class="list-group-item"><i class="fas fa-check-circle text-success me-2"></i> Power Windows</li>
                                <li class="list-group-item"><i class="fas fa-check-circle text-success me-2"></i> Anti-Lock Braking System</li>
                                <li class="list-group-item"><i class="fas fa-check-circle text-success me-2"></i> Driver Airbag</li>
                                <li class="list-group-item"><i class="fas fa-check-circle text-success me-2"></i> Passenger Airbag</li>
                                <li class="list-group-item"><i class="fas fa-check-circle text-success me-2"></i> Bluetooth Connectivity</li>
                            </ul>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h4>Description</h4>
                        <p>Experience the ultimate driving pleasure with our {{ $car->year }} {{ $car->brand }} {{ $car->model }}.
                           This {{ $car->car_type }} offers a perfect blend of style, comfort, and performance.
                           Whether you're planning a business trip, family vacation, or weekend getaway,
                           this car is designed to make your journey memorable.</p>
                        <p>The car comes with all modern amenities and safety features to ensure a smooth and secure ride.
                           With its spacious interior and elegant design, it's the perfect choice for any occasion.</p>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title">Check Availability</h4>

                    <form id="availability-form" class="mb-3">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="mb-3">
                                    <label for="check_start_date" class="form-label">Pickup Date</label>
                                    <input type="date" class="form-control" id="check_start_date"
                                           min="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="mb-3">
                                    <label for="check_end_date" class="form-label">Return Date</label>
                                    <input type="date" class="form-control" id="check_end_date"
                                           min="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <div class="mb-3 w-100">
                                    <button type="submit" class="btn btn-primary w-100">Check</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div id="availability-result" class="alert d-none"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h4 class="card-title">Rent This Car</h4>
                    <p class="card-text">Daily Rate: <span class="text-primary fw-bold">${{ number_format($car->daily_rent_price, 2) }}</span></p>

                    @if($car->availability)
                        @auth
                        <div class="d-grid">
                            <a href="{{ route('rentals.create', $car) }}" class="btn btn-primary btn-lg">Book Now</a>
                        </div>
                        @else
                        <div class="alert alert-info">
                            Please <a href="{{ route('login') }}">login</a> to book this car.
                            Don't have an account? <a href="{{ route('register') }}">Register here</a>.
                        </div>
                        @endauth
                    @else
                    <div class="alert alert-danger">
                        This car is currently not available for rent.
                    </div>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title">Why Choose Us</h4>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> No hidden charges</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> 24/7 customer support</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Free cancellation before pickup</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Well-maintained vehicles</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Flexible pickup and return</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        const startDateInput = $('#check_start_date');
        const endDateInput = $('#check_end_date');
        const availabilityResult = $('#availability-result');

        // Set minimum end date based on start date
        startDateInput.on('change', function() {
            endDateInput.attr('min', $(this).val());
            if (endDateInput.val() < $(this).val()) {
                endDateInput.val($(this).val());
            }
        });

        // Check availability form submission
        $('#availability-form').on('submit', function(e) {
            e.preventDefault();

            const startDate = startDateInput.val();
            const endDate = endDateInput.val();

            if (!startDate || !endDate) {
                availabilityResult.removeClass('alert-success alert-danger d-none').addClass('alert-warning')
                    .text('Please select both pickup and return dates.');
                return;
            }

            $.ajax({
                url: '{{ route("cars.check-availability", $car) }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    start_date: startDate,
                    end_date: endDate
                },
                beforeSend: function() {
                    availabilityResult.removeClass('d-none alert-success alert-danger alert-warning')
                        .addClass('alert-info').text('Checking availability...');
                },
                success: function(response) {
                    if (response.available) {
                        availabilityResult.removeClass('alert-info alert-danger alert-warning')
                            .addClass('alert-success')
                            .html('Car is available for the selected dates! <a href="{{ route("rentals.create", $car) }}" class="alert-link">Book now</a>.');
                    } else {
                        availabilityResult.removeClass('alert-info alert-success alert-warning')
                            .addClass('alert-danger')
                            .text('Sorry, this car is not available for the selected dates. Please try different dates.');
                    }
                },
                error: function() {
                    availabilityResult.removeClass('alert-info alert-success alert-warning')
                        .addClass('alert-danger')
                        .text('Error checking availability. Please try again.');
                }
            });
        });
    });
</script>
@endsection
