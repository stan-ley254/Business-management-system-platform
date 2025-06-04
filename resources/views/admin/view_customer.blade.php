<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    @include('admin.css')
    <style>
      .strikethrough {
            text-decoration: line-through;
            color: red;
        }
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
            <div class="container-md mt-2">
  <div class="card ">
    <form  class="form-group"method="get" action="{{url('/searchCustomeradmin')}}">
      <div class="card-body">
<input class="input-group mb-2 " name="searchCustomeradmin" placeholder="search customer by ..name...or phone number" value="{{isset($searchCustomeradmin) ? $searchCustomeradmin : ''}}">
<button type="submit" class="btn btn-success">Search</button>

      
    </form>
    <div class=" mt-4">
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
</div>
</div>
<div>
<div class=" table-responsive">
               <table class="table table-bordered jsgrid jsgrid-table dataTables_wrapper table-primary ">
<thead>
<tr>
                <th>Customer Name</th>
                <th>Phone Number</th>
                    <th>Total Debt</th>
                    <th>Location</th>
                    <th>Action</th>
                <!-- Add more table headers as needed -->
            </tr>
        <tbody>
            @if(isset($customers) && ($customers))
            @foreach ($customers as $customer)
            <tr>
                <td>{{ $customer->customer_name }}</td>
                <td>{{ $customer->phone_number }}</td>
                <td>{{$customer->total_debt }}</td>
                <td>{{$customer->location }}</td>
        
                
                
                <!-- Add more table cells for other attributes -->
                <td>
                  <a class="btn btn-info" href="{{ url('editCustomeradmin', $customer->id) }}">edit customer</a>
                   <a onclick="return confirm('Are you sure you want to delete this customer')" class="btn btn-danger" href="{{ url('destroyCustomeradmin', $customer->id) }}">Delete</a>
                    <!-- Add delete button with form submission if needed -->
                </td>
            </tr>
            @endforeach
            @endif
  </tbody>
               </table>
               </div>
</div>
              
            </div>
</div>


          
    <!-- container-scroller -->
    @include('admin.script')

    </div>
  </body>
</html>