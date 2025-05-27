@include('navstore')
<div class="container-xxl my-2">
    <!-- Header -->
    <div class="custom-header">
     Manage Customers
    </div>

    <!-- Form Section -->
    <div class="custom-form-container">
        <form  method="get" action="{{url('/searchCustomer')}}" class="mt-1">
            @csrf
        <div class="form-group">
<input type="text" class="form-control" name="searchCustomer" placeholder="search customer by..name..or phone" value="{{isset($searchCustomer) ? $searchCustomer : ''}}">
<button type="submit" class="btn btn-custom mt-2"><i class="fas fa-search"></i> Search</button>
</form>
</div>
    </div>

    <!-- Table Section -->
    <div class="custom-table-container">
    
        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
            <thead>
<tr>
                <th>Customer Name</th>
                <th>Phone Number</th>
                    <th>Total Debt</th>
                    <th>Location</th>
                   
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
               <!-- <td>
                    <a class="btn btn-warning btn-sm" href="{{ url('editCustomeradmin', $customer->id) }}"><i class="fas fa-edit"></i>edit customer</a>
                  <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to Delete this')" href="{{ url('destroyCustomeradmin', $customer->id) }}"><i class="fas fa-trash"></i> Delete</a>
</td>
-->
                    <!-- Add delete button with form submission if needed -->
            
            </tr>
            @endforeach
            @endif
  </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
