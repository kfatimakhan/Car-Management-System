@extends('layouts.frontend')

@section('content')
<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold">Find Your Perfect Ride</h1>
                <p class="lead">Rent a car for your next adventure. We offer a wide range of vehicles to suit your needs.</p>
                <div class="mt-4">
                    <a href="{{ route('cars.index') }}" class="btn btn-primary btn-lg">Browse Cars</a>
                    <a href="{{ route('about') }}" class="btn btn-outline-secondary btn-lg ms-2">Learn More</a>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="{{ asset('images/hero-car.jpg') }}" alt="Car Rental" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</div>

<!-- Featured Cars Section -->
<div class="featured-cars py-5">
    <div class="container">
        <h2 class="text-center mb-5">Featured Cars</h2>
        <div class="row">
            @foreach($featuredCars as $car)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="{{ $car->image ? asset('storage/' . $car->image) : asset('images/car-placeholder.jpg') }}"
                         class="card-img-top" alt="{{ $car->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $car->brand }} {{ $car->model }}</h5>
                        <p class="card-text">
                            <span class="badge bg-primary">{{ $car->car_type }}</span>
                            <span class="badge bg-secondary">{{ $car->year }}</span>
                        </p>
                        <p class="card-text text-primary fw-bold">\${{ number_format($car->daily_rent_price, 2) }} / day</p>
                    </div>
                    <div class="card-footer bg-white border-top-0">
                        <a href="{{ route('cars.show', $car) }}" class="btn btn-outline-primary w-100">View Details</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('cars.index') }}" class="btn btn-primary">View All Cars</a>
        </div>
    </div>
</div>

<!-- How It Works Section -->
<div class="how-it-works py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">How It Works</h2>
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="p-4 bg-white rounded shadow-sm">
                    <div class="icon-box mb-3">
                        <i class="fas fa-search fa-3x text-primary"></i>
                    </div>
                    <h4>1. Choose a Car</h4>
                    <p>Browse our selection of cars and choose the one that fits your needs.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="p-4 bg-white rounded shadow-sm">
                    <div class="icon-box mb-3">
                        <i class="fas fa-calendar-alt fa-3x text-primary"></i>
                    </div>
                    <h4>2. Book Your Dates</h4>
                    <p>Select your pickup and return dates to check availability.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="p-4 bg-white rounded shadow-sm">
                    <div class="icon-box mb-3">
                        <i class="fas fa-car fa-3x text-primary"></i>
                    </div>
                    <h4>3. Enjoy Your Ride</h4>
                    <p>Pick up your car and enjoy your journey with our reliable vehicles.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Testimonials Section -->
<div class="testimonials py-5">
    <div class="container">
        <h2 class="text-center mb-5">What Our Customers Say</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="card-text">"Great service and excellent cars. The rental process was smooth and hassle-free. Will definitely use again!"</p>
                    </div>
                    <div class="card-footer bg-white border-top-0">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('images/testimonial-1.jpg') }}" alt="Customer" class="rounded-circle me-3" width="50">
                            <div>
                                <h6 class="mb-0">John Doe</h6>
                                <small class="text-muted">Business Traveler</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="card-text">"The car was in perfect condition and the staff was very helpful. I had a wonderful experience with this car rental service."</p>
                    </div>
                    <div class="card-footer bg-white border-top-0">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('images/testimonial-2.jpg') }}" alt="Customer" class="rounded-circle me-3" width="50">
                            <div>
                                <h6 class="mb-0">Jane Smith</h6>
                                <small class="text-muted">Family Vacation</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star-half-alt text-warning"></i>
                        </div>
                        <p class="card-text">"Affordable prices and great selection of cars. The booking process was easy and the car was ready when I arrived. Highly recommended!"</p>
                    </div>
                    <div class="card-footer bg-white border-top-0">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('images/testimonial-3.jpg') }}" alt="Customer" class="rounded-circle me-3" width="50">
                            <div>
                                <h6 class="mb-0">Michael Johnson</h6>
                                <small class="text-muted">Weekend Getaway</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
