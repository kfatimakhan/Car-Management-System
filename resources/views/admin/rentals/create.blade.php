@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add New Rental</h1>
        <a href="{{ route('admin.rentals.index') }}" class="d-none d-sm-inline-block btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Rentals
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Rental Details</h6>
        </div>
        <div class="card-body">
            @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('admin.rentals.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="user_id">Customer</label>
                            <select class="form-control @error('user_id') is-invalid @enderror"
                                    id="user_id" name="user_id" required>
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('user_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} ({{ $customer->email }})
                                </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="car_id">Car</label>
                            <select class="form-control @error('car_id') is-invalid @enderror"
                                    id="car_id" name="car_id" required>
                                <option value="">Select Car</option>
                                @foreach($cars as $car)
                                <option value="{{ $car->id }}" {{ old('car_id') == $car->id ? 'selected' : '' }}>
                                    {{ $car->brand }} {{ $car->model }} ({{ $car->year }}) - ${{ number_format($car->daily_rent_price, 2) }}/day
                                </option>
                                @endforeach
                            </select>
                            @error('car_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                   id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                   id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control @error('status') is-invalid @enderror"
                            id="status" name="status" required>
                        <option value="ongoing" {{ old('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="canceled" {{ old('status') == 'canceled' ? 'selected' : '' }}>Canceled</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <div id="rental-summary" class="alert alert-info d-none">
                        <h6 class="font-weight-bold">Rental Summary</h6>
                        <p>Total Days: <span id="total-days">0</span></p>
                        <p>Daily Rate: $<span id="daily-rate">0.00</span></p>
                        <p>Total Cost: $<span id="total-cost">0.00</span></p>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Create Rental</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Store car data for calculations
        const cars = @json($cars->keyBy('id'));

        // Function to calculate rental details
        function calculateRental() {
            const carId = $('#car_id').val();
            const startDate = $('#start_date').val();
            const endDate = $('#end_date').val();

            if (carId && startDate && endDate) {
                const car = cars[carId];
                if (car) {
                    const start = new Date(startDate);
                    const end = new Date(endDate);

                    // Calculate difference in days
                    const diffTime = Math.abs(end - start);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // Include both start and end days

                    if (diffDays > 0) {
                        const dailyRate = parseFloat(car.daily_rent_price);
                        const totalCost = dailyRate * diffDays;

                        $('#total-days').text(diffDays);
                        $('#daily-rate').text(dailyRate.toFixed(2));
                        $('#total-cost').text(totalCost.toFixed(2));

                        $('#rental-summary').removeClass('d-none');
                    }
                }
            }
        }

        // Event listeners
        $('#car_id, #start_date, #end_date').change(calculateRental);

        // Set minimum end date based on start date
        $('#start_date').on('change', function() {
            $('#end_date').attr('min', $(this).val());
            if ($('#end_date').val() < $(this).val()) {
                $('#end_date').val($(this).val());
            }
        });
    });
</script>
@endsection
