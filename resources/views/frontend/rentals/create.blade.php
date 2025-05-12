
@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="card-title">Book Your Car</h2>
                    <hr>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <img src="{{ $car->image ? asset('storage/' . $car->image) : asset('images/car-placeholder.jpg') }}"
                                 class="img-fluid rounded" alt="{{ $car->name }}">
                        </div>
                        <div class="col-md-6">
                            <h3>{{ $car->brand }} {{ $car->model }}</h3>
                            <p class="text-muted">{{ $car->year }} · {{ $car->car_type }}</p>
                            <p class="text-primary fw-bold fs-4">${{ number_format($car->daily_rent_price, 2) }} / day</p>

                            <div class="mt-3">
                                <h5>Car Features:</h5>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check-circle text-success me-2"></i> Air Conditioning</li>
                                    <li><i class="fas fa-check-circle text-success me-2"></i> Bluetooth</li>
                                    <li><i class="fas fa-check-circle text-success me-2"></i> Parking Sensors</li>
                                    <li><i class="fas fa-check-circle text-success me-2"></i> GPS Navigation</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('rentals.store', $car) }}" method="POST" id="booking-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">Pickup Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date"
                                       min="{{ date('Y-m-d') }}" value="{{ old('start_date') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">Return Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date"
                                       min="{{ date('Y-m-d') }}" value="{{ old('end_date') }}" required>
                            </div>
                        </div>

                        <div id="availability-message" class="alert d-none mb-3"></div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg" id="book-button">Book Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title">Rental Summary</h4>
                    <hr>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Car:</span>
                        <span>{{ $car->brand }} {{ $car->model }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Daily Rate:</span>
                        <span>${{ number_format($car->daily_rent_price, 2) }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Rental Days:</span>
                        <span id="rental-days">0</span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total Cost:</span>
                        <span id="total-cost">$0.00</span>
                    </div>

                    <div class="mt-4">
                        <h5>Rental Policies:</h5>
                        <ul class="list-unstyled small">
                            <li><i class="fas fa-info-circle me-2"></i> Minimum rental period is 1 day</li>
                            <li><i class="fas fa-info-circle me-2"></i> Full payment required at booking</li>
                            <li><i class="fas fa-info-circle me-2"></i> Valid driver's license required</li>
                            <li><i class="fas fa-info-circle me-2"></i> Cancellation available before pickup date</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        const dailyRate = {{ $car->daily_rent_price }};
        const startDateInput = $('#start_date');
        const endDateInput = $('#end_date');
        const rentalDaysElement = $('#rental-days');
        const totalCostElement = $('#total-cost');
        const availabilityMessage = $('#availability-message');
        const bookButton = $('#book-button');

        function updateRentalSummary() {
            const startDate = new Date(startDateInput.val());
            const endDate = new Date(endDateInput.val());

            if (startDate && endDate && startDate <= endDate) {
                // Calculate the difference in days
                const diffTime = Math.abs(endDate - startDate);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // Include both start and end days

                rentalDaysElement.text(diffDays);
                totalCostElement.text('$' + (diffDays * dailyRate).toFixed(2));

                // Check availability
                checkAvailability(startDateInput.val(), endDateInput.val());
            } else {
                rentalDaysElement.text('0');
                totalCostElement.text('$0.00');
                availabilityMessage.addClass('d-none');
            }
        }

        function checkAvailability(startDate, endDate) {
            if (!startDate || !endDate) return;

            $.ajax({
                url: '{{ route("cars.check-availability", $car) }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    start_date: startDate,
                    end_date: endDate
                },
                success: function(response) {
                    if (response.available) {
                        availabilityMessage.removeClass('alert-danger d-none').addClass('alert-success').text('Car is available for the selected dates!');
                        bookButton.prop('disabled', false);
                    } else {
                        availabilityMessage.removeClass('alert-success d-none').addClass('alert-danger').text('Sorry, this car is not available for the selected dates.');
                        bookButton.prop('disabled', true);
                    }
                },
                error: function() {
                    availabilityMessage.removeClass('alert-success d-none').addClass('alert-danger').text('Error checking availability. Please try again.');
                    bookButton.prop('disabled', true);
                }
            });
        }

        startDateInput.change(updateRentalSummary);
        endDateInput.change(updateRentalSummary);

        // Set minimum end date based on start date
        startDateInput.on('change', function() {
            endDateInput.attr('min', $(this).val());
            if (endDateInput.val() < $(this).val()) {
                endDateInput.val($(this).val());
            }
        });
    });
</script>
@endsection
