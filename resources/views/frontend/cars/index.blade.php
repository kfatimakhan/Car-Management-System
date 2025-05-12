
@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Available Cars</h1>

    <div class="row">
        <!-- Filters Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Filter Cars</h5>

                    <form action="{{ route('cars.index') }}" method="GET">
                        <div class="mb-3">
                            <label for="brand" class="form-label">Brand</label>
                            <select class="form-select" id="brand" name="brand">
                                <option value="">All Brands</option>
                                @foreach($brands as $brand)
                                <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>
                                    {{ $brand }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="car_type" class="form-label">Car Type</label>
                            <select class="form-select" id="car_type" name="car_type">
                                <option value="">All Types</option>
                                @foreach($carTypes as $type)
                                <option value="{{ $type }}" {{ request('car_type') == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Price Range ($ per day)</label>
                            <div class="row">
                                <div class="col-6">
                                    <input type="number" class="form-control" name="min_price"
                                           placeholder="Min" min="0" value="{{ request('min_price') }}">
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control" name="max_price"
                                           placeholder="Max" min="0" value="{{ request('max_price') }}">
                                </div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Apply Filters</button>
                        </div>

                        @if(request()->anyFilled(['brand', 'car_type', 'min_price', 'max_price']))
                        <div class="d-grid mt-2">
                            <a href="{{ route('cars.index') }}" class="btn btn-outline-secondary">Clear Filters</a>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <!-- Car Listings -->
        <div class="col-lg-9">
            @if($cars->isEmpty())
            <div class="alert alert-info">
                No cars found matching your criteria. Please try different filters.
            </div>
            @else
            <div class="row">
                @foreach($cars as $car)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ $car->image ? asset('storage/' . $car->image) : asset('images/car-placeholder.jpg') }}"
                             class="card-img-top" alt="{{ $car->name }}" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title">{{ $car->brand }} {{ $car->model }}</h5>
                            <p class="card-text">
                                <span class="badge bg-primary">{{ $car->car_type }}</span>
                                <span class="badge bg-secondary">{{ $car->year }}</span>
                            </p>
                            <p class="card-text text-primary fw-bold">\${{ number_format($car->daily_rent_price, 2) }} / day</p>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <div class="d-grid gap-2">
                                <a href="{{ route('cars.show', $car) }}" class="btn btn-outline-primary">View Details</a>
                                @auth
                                <a href="{{ route('rentals.create', $car) }}" class="btn btn-primary">Rent Now</a>
                                @else
                                <a href="{{ route('login') }}" class="btn btn-primary">Login to Rent</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $cars->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
