@extends('layouts.frontend')

@section('content')
<!-- Contact Hero Section -->
<div class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold">Contact Us</h1>
                <p class="lead">We're here to help! Reach out to us with any questions or concerns.</p>
            </div>
        </div>
    </div>
</div>

<!-- Contact Content Section -->
<div class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 mb-4 mb-lg-0">
                <h2>Get In Touch</h2>
                <p>Have questions about our car rental service? Our team is ready to assist you. Fill out the form or use the contact information below to get in touch with us.</p>

                <div class="mt-4">
                    <h5>Contact Information</h5>
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <i class="fas fa-map-marker-alt text-primary me-2"></i> 123 Main Street, City, Country
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-phone-alt text-primary me-2"></i> +1 234 567 8900
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-envelope text-primary me-2"></i> info@carrentalservice.com
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-clock text-primary me-2"></i> Mon-Fri: 9:00 AM - 6:00 PM
                        </li>
                    </ul>
                </div>

                <div class="mt-4">
                    <h5>Follow Us</h5>
                    <div class="social-icons">
                        <a href="#" class="text-primary me-3"><i class="fab fa-facebook-f fa-lg"></i></a>
                        <a href="#" class="text-primary me-3"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-primary me-3"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-primary"><i class="fab fa-linkedin-in fa-lg"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="card-title mb-4">Send Us a Message</h3>

                        @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif

                        <form action="{{ route('contact.submit') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Your Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Your Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="subject" class="form-label">Subject</label>
                                <input type="text" class="form-control @error('subject') is-invalid @enderror"
                                       id="subject" name="subject" value="{{ old('subject') }}" required>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control @error('message') is-invalid @enderror"
                                          id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Map Section -->
<div class="mt-5">
    <div class="container-fluid p-0">
        <div class="row g-0">
            <div class="col-12">
                <div class="map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.215266754809!2d-73.98784492426285!3d40.75798657138946!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25855c6480299%3A0x55194ec5a1ae072e!2sTimes%20Square!5e0!3m2!1sen!2sus!4v1710234567890!5m2!1sen!2sus"
                            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
