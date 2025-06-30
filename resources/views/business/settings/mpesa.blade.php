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
            <div class="container-md mt-2">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-success">M-Pesa Configuration (Create)</h4>
                        <form action="{{ route('business.mpesa.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="mpesa_short_code" class="text-success">Short Code (Till/Paybill)</label>
                                <input type="text" class="input-group" id="mpesa_short_code" name="mpesa_short_code" required>
                               
                            </div>
                            <div class="form-group">
                                <label for="mpesa_consumer_key" class="text-success">Consumer Key</label>
                                <input type="text" class="input-group" id="mpesa_consumer_key" name="mpesa_consumer_key"  required>
                             
                            </div>
                            <div class="form-group">
                                <label for="mpesa_consumer_secret" class="text-success">Consumer Secret</label>
                                <input type="text" class="input-group" id="mpesa_consumer_secret" name="mpesa_consumer_secret"  required>
                             
                            </div>
                            <div class="form-group">
                                <label for="mpesa_passkey" class="text-success">Passkey</label>
                                <input type="text" class="input-group" id="mpesa_passkey" name="mpesa_passkey"  required>
                               
                            </div>
                            <div class="form-group">
                                <label for="mpesa_initiator_name" class="text-success">Initiator Name (Optional)</label>
                                <input type="text" class="input-group" id="mpesa_initiator_name" name="mpesa_initiator_name" >
                                
                            </div>
                            <div class="form-group">
                                <label for="mpesa_security_credential" class="text-success">Security Credential (Optional)</label>
                                <input type="text" class="input-group" id="mpesa_security_credential" name="mpesa_security_credential" >
                                
                                
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