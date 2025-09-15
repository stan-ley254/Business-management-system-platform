<!DOCTYPE html>
<html lang="en">
  <head>
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
    <div class="main-panel">
      @include('admin.sidebar')
      @include('admin.header')

      <div class="content-wrapper">
        <div class="container-md mt-2">
          <div class="card">

            {{-- Flash Messages --}}
            @if(session('success'))
              <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
              <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card-body">
              <h4 class="card-title mt-2">Add a User</h4>

              <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group mb-3">
                  <label for="name" class="text-success">Name</label>
                  <input type="text" class="form-control text-white" name="name" placeholder="User Name" required>
                </div>

                <div class="form-group mb-3">
                  <label for="email" class="text-success">Email</label>
                  <input type="email" class="form-control text-white" name="email" placeholder="Email" required>
                </div>

                <!-- Password -->
                <div class="form-group mb-3">
                  <label for="password" class="text-success">Password</label>
                  <div class="input-group">
                    <input type="password" class="form-control text-white" name="password" id="password" placeholder="Enter Password" required>
                    <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#password">
                      <i class="mdi mdi-eye"></i>
                    </button>
                  </div>
                  <!-- Password Rules -->
                  <ul id="password-rules" class="list-unstyled small mt-2 text-muted">
                    <li id="rule-length"><i class="mdi mdi-circle-outline text-secondary"></i> At least 8 characters</li>
                    <li id="rule-uppercase"><i class="mdi mdi-circle-outline text-secondary"></i> At least 1 uppercase letter</li>
                    <li id="rule-lowercase"><i class="mdi mdi-circle-outline text-secondary"></i> At least 1 lowercase letter</li>
                    <li id="rule-number"><i class="mdi mdi-circle-outline text-secondary"></i> At least 1 number</li>
                    <li id="rule-symbol"><i class="mdi mdi-circle-outline text-secondary"></i> At least 1 special character</li>
                  </ul>
                </div>

                <!-- Confirm Password -->
                <div class="form-group mb-3">
                  <label for="password_confirmation" class="text-success">Confirm Password</label>
                  <div class="input-group">
                    <input type="password" class="form-control text-white" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" required>
                    <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#password_confirmation">
                      <i class="mdi mdi-eye"></i>
                    </button>
                  </div>
                </div>

                <button type="submit" class="btn btn-primary me-2">Submit</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    @include('admin.script')

   
  </body>
</html>
