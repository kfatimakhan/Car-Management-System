@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3.webp" alt="avatar"
                        class="rounded-circle img-fluid" style="width: 150px;">
                    <h5 class="my-3">{{ Auth::user()->name }}</h5>
                    <p class="text-muted mb-1">{{ Auth::user()->email }}</p>
                    <p class="text-muted mb-4">Member since {{ Auth::user()->created_at->format('M Y') }}</p>
                    <div class="d-flex justify-content-center mb-2">
                        <button type="button" class="btn btn-primary">Edit Profile</button>
                        <button type="button" class="btn btn-outline-primary ms-1">My Bookings</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Full Name</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0">{{ Auth::user()->name }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Email</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Phone</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0">{{ Auth::user()->phone ?? 'Not provided' }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Address</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0">{{ Auth::user()->address ?? 'Not provided' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4 mb-md-0">
                        <div class="card-body">
                            <h5 class="card-title">Recent Bookings</h5>
                            @if(count(Auth::user()->bookings ?? []) > 0)
                                <ul class="list-group list-group-flush">
                                    @foreach(Auth::user()->bookings->take(3) as $booking)
                                    <li class="list-group-item px-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-0">{{ $booking->car->make }} {{ $booking->car->model }}</h6>
                                                <small class="text-muted">{{ $booking->start_date->format('M d, Y') }} - {{ $booking->end_date->format('M d, Y') }}</small>
                                            </div>
                                            <span class="badge bg-primary rounded-pill">{{ $booking->status }}</span>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                                <div class="mt-3">
                                    <a href="{{ route('bookings.index') }}" class="btn btn-sm btn-outline-primary">View All Bookings</a>
                                </div>
                            @else
                                <p class="text-muted">No bookings found.</p>
                                <a href="{{ route('cars.index') }}" class="btn btn-sm btn-primary">Browse Cars</a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card mb-4 mb-md-0">
                        <div class="card-body">
                            <h5 class="card-title">Account Settings</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item px-0">
                                    <a href="#" class="text-decoration-none">
                                        <i class="fas fa-key me-2"></i> Change Password
                                    </a>
                                </li>
                                <li class="list-group-item px-0">
                                    <a href="#" class="text-decoration-none">
                                        <i class="fas fa-bell me-2"></i> Notification Settings
                                    </a>
                                </li>
                                <li class="list-group-item px-0">
                                    <a href="#" class="text-decoration-none">
                                        <i class="fas fa-credit-card me-2"></i> Payment Methods
                                    </a>
                                </li>
                                <li class="list-group-item px-0">
                                    <a href="#" class="text-decoration-none">
                                        <i class="fas fa-history me-2"></i> Account Activity
                                    </a>
                                </li>
                                <li class="list-group-item px-0">
                                    <a href="#" class="text-decoration-none text-danger">
                                        <i class="fas fa-trash-alt me-2"></i> Delete Account
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
