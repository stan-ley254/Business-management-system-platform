<!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.css')
    <style>
        .sidebar { position: fixed; }
        .form_color { color: #ffffff; }
    </style>
</head>
<body>
    @include('admin.sidebar')
    @include('admin.header')

    <div class="main-panel">
        <div class="content-wrapper">
            <div class="container-md mt-2">
                <div class="message d-print-inline-flex rounded">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>

                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-success">Business Information</h4>
                        <form action="{{ route('business.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group mb-3">
                                <label for="name" class="text-success">Business Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $business->name ?? '') }}">
                            </div>

                            <div class="form-group mb-3">
                                <label for="address" class="text-success">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3">{{ old('address', $business->address ?? '') }}</textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label for="phone" class="text-success">Phone Number</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $business->phone ?? '') }}">
                            </div>

                            <div class="form-group mb-3">
                                <label for="logo_path" class="text-success">Business Logo</label>
                                <input type="file" class="form-control" id="logo_path" name="logo_path" accept="image/*">
                                @if(!empty($business->logo_path))
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $business->logo_path) }}" alt="Current logo" style="max-width: 180px; max-height: 80px;">
                                    </div>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-primary mt-2">Save Business Details</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.script')
</body>
</html>
