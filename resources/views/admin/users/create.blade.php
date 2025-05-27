<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    @include('admin.css')
    <style>
      .sidebar{
        position: fixed;
      }
      .form_color{
        color:#ffffff;
      }
    </style>
  </head>
  <body>
    
 
      <!-- partial:partials/_sidebar.html -->
     
      
        <div class="main-panel">
        @include('admin.sidebar')
      <!-- partial -->
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
  <div class="form-group">
    <label for="name" class="text-success">Name</label>
    <input type="text" class="input-group " name="name"  placeholder="User Name" required>
  </div>
  <div class="form-group">
    <label for="email" class="text-success">Email</label>
    <input type="text" class="input-group" name="email" id="exampleInputEmail3" placeholder="Email" required>
  </div>
  <div class="form-group">
    <label for="password" class="text-success">Password</label>
    <input type="password" class="input-group" name="password" id="exampleInputPassword4" placeholder="Enter Password" required>
  </div>
  <div class="form-group">
    <label for="password_confirmation" class="text-success">Confirm Password</label>
    <input type="password" class="input-group" name="password_confirmation" id="exampleInputPassword4" placeholder="Confirm Password" required>
  </div>
 
 
  <button type="submit" class="btn btn-primary me-2">Submit</button>
  
</form>
                  </div>
                </div>
              </div>
          </div>
        <!-- main-panel ends -->
      </div>
          </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    @include('admin.script')
  </body>
</html>
