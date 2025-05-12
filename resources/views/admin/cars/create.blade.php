@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add New Car</h1>
        <a href="{{ route('admin.cars.index') }}" class="d-none d-sm-inline-block btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Cars
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Car Details</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.cars.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Car Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="brand">Brand</label>
                            <input type="text" class="form-control @error('brand') is-invalid @enderror"
                                   id="brand" name="brand" value="{{ old('brand') }}" required>
                            @error('brand')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="model">Model</label>
                            <input type="text" class="form-control @error('model') is-invalid @enderror"
                                   id="model" name="model" value="{{ old('model') }}" required>
                            @error('model')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="year">Year of Manufacture</label>
                            <input type="number" class="form-control @error('year') is-invalid @enderror"
                                   id="year" name="year" value="{{ old('year') }}" min="1900" max="{{ date('Y') + 1 }}" required>
                            @error('year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="car_type">Car Type</label>
                            <select class="form-control @error('car_type') is-invalid @enderror"
                                    id="car_type" name="car_type" required>
                                <option value="">Select Car Type</option>
                                <option value="Sedan" {{ old('car_type') == 'Sedan' ? 'selected' : '' }}>Sedan</option>
                                <option value="SUV" {{ old('car_type') == 'SUV' ? 'selected' : '' }}>SUV</option>
                                <option value="Hatchback" {{ old('car_type') == 'Hatchback' ? 'selected' : '' }}>Hatchback</option>
                                <option value="Convertible" {{ old('car_type') == 'Convertible' ? 'selected' : '' }}>Convertible</option>
                                <option value="Coupe" {{ old('car_type') == 'Coupe' ? 'selected' : '' }}>Coupe</option>
                                <option value="Minivan" {{ old('car_type') == 'Minivan' ? 'selected' : '' }}>Minivan</option>
                                <option value="Pickup" {{ old('car_type') == 'Pickup' ? 'selected' : '' }}>Pickup</option>
                            </select>
                            @error('car_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="daily_rent_price">Daily Rent Price ($)</label>
                            <input type="number" class="form-control @error('daily_rent_price') is-invalid @enderror"
                                   id="daily_rent_price" name="daily_rent_price" value="{{ old('daily_rent_price') }}"
                                   min="0" step="0.01" required>
                            @error('daily_rent_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="image">Car Image</label>
                    <input type="file" class="form-control-file @error('image') is-invalid @enderror"
                           id="image" name="image">
                    <small class="form-text text-muted">Upload an image of the car (optional).</small>
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="availability" name="availability" value="1"
                               {{ old('availability', '1') == '1' ? 'checked' : '' }}>
                        <label class="custom-control-label" for="availability">Available for Rent</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Add Car</button>
            </form>
        </div>
    </div>
</div>
@endsection
