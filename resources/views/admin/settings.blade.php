@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Settings</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Application Settings</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf

                <div class="form-group row">
                    <label for="site_name" class="col-sm-2 col-form-label">Site Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="site_name" name="site_name" value="{{ config('app.name') }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="contact_email" class="col-sm-2 col-form-label">Contact Email</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" id="contact_email" name="contact_email" value="contact@example.com">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="contact_phone" class="col-sm-2 col-form-label">Contact Phone</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="contact_phone" name="contact_phone" value="+1 234 567 8900">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-10 offset-sm-2">
                        <button type="submit" class="btn btn-primary">Save Settings</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
