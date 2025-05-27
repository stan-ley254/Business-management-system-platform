<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    @include('admin.css')
    <style>

    </style>
  </head>
  <body>
  
      <!-- partial:partials/_sidebar.html -->
      @include('admin.sidebar')
      <!-- partial -->
      @include('admin.header')
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            @if(session()->has('message'))
<div class="alert alert-success alter-dismissible fade show">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close" aria-hidden="true">
        <span aria-hidden="true">&times;</span>
    </button>
    {{session()->get('message')}}
</div>
            @endif
            <div class="container-fluid">
            <div class="text">
                <h2>Edit Product</h2>
            </div>
            <div>
   </div>
   <div class="card">
    <div class="card-body">
    <form action="{{url('updateCustomeradmin',$customer->id) }}" method="POST" class="form-inline">
        @csrf
        @method('PUT')
 <div class="form-group">
        <label for="name">Customer Name:</label>
        <input type="text" name="customer_name" id="name" value="{{ $customer->customer_name }}" class="mx-2" required>
</div>
 <div class="form-group">
        <label for="">Phone Number:</label>
        <input type="text" name="phone_number" id="phone_number" value="{{ $customer->phone_number }}" required>
        </div>
 <div class="form-group">
        <label for="location">location:</label>
        <input type="location" name="location" id="location" value="{{ $customer->location}}" class="mx-2" required>
        <!-- Add any other fields for the sale here -->
</div>
        <button class="button rounded-pill btn-success ml-4"type="submit">Update Customer</button>
    </form>
</div>
</div>
   </div>
   </div>
            </div>
</div>
   
    <!-- container-scroller -->
    @include('admin.script')
  </body>
</html>