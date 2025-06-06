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
  @include('superadmin.businesses.sidebarsuper')
      <!-- partial -->
      @include('admin.header')
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
      <div class="container-md">
            <div class="text">
               <h2>Add Category</h2>
</div>
               <div class="card">
               <div class="card-body">
               <form action="{{url('/add_category')}}" method="post" class="form-group">
                @csrf
                <input class="input-group mb-2" type="text" name="category" placeholder="Write category name" required />
               <input type="submit" class="btn btn-primary " name="submit" value="Add Category">
               </form>
               </div>
               </div>
      <div class="container mt-4">
    <h3 class="text-success">Set Next Payment Due Date for a Business</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('superadmin.businesses.set_due_date') }}">
        @csrf
        <div class="form-group">
            <label for="business_id">Select Business</label>
            <select name="business_id" class="form-control input-group" required>
                <option class="text-success">-- Choose Business --</option>
                @foreach($businesses as $business)
                    <option value="{{ $business->id }}">{{ $business->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group mt-3">
            <label for="next_payment_due">Next Payment Due Date</label>
            <input type="date" name="next_payment_due" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success mt-3">Update Due Date</button>
    </form>
</div>

</div>
</div>
</div>

          
    <!-- container-scroller -->
    @include('admin.script')
  </body>
</html>
