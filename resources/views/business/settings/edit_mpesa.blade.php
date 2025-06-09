<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    @include('admin.css')
    <style>
        .sidebar {
            position: fixed;
        }
        .form_color {
            color: #ffffff;
        }
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
                        <h4 class="card-title text-success">M-Pesa Configuration (Create)</h4>
                        <form action="{{ route('business.mpesa.update') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="mpesa_short_code" class="text-success">Short Code (Till/Paybill)</label>
                                <input type="text" class="input-group" id="mpesa_short_code" name="mpesa_short_code" value="{{ old('mpesa_short_code') }}" required>
                                @error('mpesa_short_code')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="mpesa_consumer_key" class="text-success">Consumer Key</label>
                                <input type="text" class="input-group" id="mpesa_consumer_key" name="mpesa_consumer_key" value="{{ old('mpesa_consumer_key') }}" required>
                                @error('mpesa_consumer_key')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="mpesa_consumer_secret" class="text-success">Consumer Secret</label>
                                <input type="text" class="input-group" id="mpesa_consumer_secret" name="mpesa_consumer_secret" value="{{ old('mpesa_consumer_secret') }}" required>
                                @error('mpesa_consumer_secret')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="mpesa_passkey" class="text-success">Passkey</label>
                                <input type="text" class="input-group" id="mpesa_passkey" name="mpesa_passkey" value="{{ old('mpesa_passkey') }}" required>
                                @error('mpesa_passkey')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="mpesa_initiator_name" class="text-success">Initiator Name (Optional)</label>
                                <input type="text" class="input-group" id="mpesa_initiator_name" name="mpesa_initiator_name" value="{{ old('mpesa_initiator_name') }}">
                                @error('mpesa_initiator_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="mpesa_security_credential" class="text-success">Security Credential (Optional)</label>
                                <input type="text" class="input-group" id="mpesa_security_credential" name="mpesa_security_credential" value="{{ old('mpesa_security_credential') }}">
                                @error('mpesa_security_credential')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.script')
</body>
</html>