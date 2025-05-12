
@extends('layouts.frontend')

@section('content')
<!-- About Hero Section -->
<div class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold">About Us</h1>
                <p class="lead">Learn more about our car rental service and our commitment to quality.</p>
            </div>
        </div>
    </div>
</div>

<!-- About Content Section -->
<div class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h2>Our Story</h2>
                <p>Founded in 2010, Car Rental Service has grown from a small local business to one of the leading car rental companies in the region. Our journey began with a simple mission: to provide high-quality rental cars at affordable prices with exceptional customer service.</p>
                <p>Over the years, we have expanded our fleet to include a wide range of vehicles, from economy cars to luxury models, to meet the diverse needs of our customers. We take pride in maintaining our vehicles to the highest standards, ensuring that each car is clean, reliable, and safe.</p>
                <p>Today, we continue to uphold our commitment to excellence, making car rental a hassle-free experience for business travelers, tourists, and locals alike.</p>
            </div>
            <div class="col-lg-6">
                <img src="{{ asset('images/about-us.jpg') }}" alt="About Us" class="img-fluid rounded shadow">
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <h2 class="text-center mb-4">Our Values</h2>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-handshake fa-3x text-primary"></i>
                        </div>
                        <h4>Customer Satisfaction</h4>
                        <p>We prioritize our customers' needs and strive to exceed their expectations with every rental.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-shield-alt fa-3x text-primary"></i>
                        </div>
                        <h4>Safety & Reliability</h4>
                        <p>We maintain our vehicles to the highest standards to ensure your safety and peace of mind.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-leaf fa-3x text-primary"></i>
                        </div>
                        <h4>Environmental Responsibility</h4>
                        <p>We are committed to reducing our environmental footprint with fuel-efficient and hybrid vehicles.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <h2 class="text-center mb-4">Our Team</h2>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="{{ asset('images/team-1.jpg') }}" class="card-img-top" alt="Team Member">
                    <div class="card-body text-center">
                        <h5 class="card-title">John Smith</h5>
                        <p class="card-text text-muted">CEO & Founder</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="{{ asset('images/team-2.jpg') }}" class="card-img-top" alt="Team Member">
                    <div class="card-body text-center">
                        <h5 class="card-title">Sarah Johnson</h5>
                        <p class="card-text text-muted">Operations Manager</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="{{ asset('images/team-3.jpg') }}" class="card-img-top" alt="Team Member">
                    <div class="card-body text-center">
                        <h5 class="card-title">Michael Brown</h5>
                        <p class="card-text text-muted">Fleet Manager</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="{{ asset('images/team-4.jpg') }}" class="card-img-top" alt="Team Member">
                    <div class="card-body text-center">
                        <h5 class="card-title">Emily Davis</h5>
                        <p class="card-text text-muted">Customer Service</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Call to Action Section -->
<div class="bg-light py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2>Ready to Rent a Car?</h2>
                <p class="lead mb-4">Browse our selection of cars and find the perfect vehicle for your needs.</p>
                <a href="{{ route('cars.index') }}" class="btn btn-primary btn-lg">View Our Cars</a>
            </div>
        </div>
    </div>
</div>
@endsection
