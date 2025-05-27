@include('navstore')
<div class="container-xxl my-2">
    <!-- Header -->
    <div class="custom-header">
     Manage Suppliers
    </div>

    <!-- Form Section -->
    <div class="custom-form-container">
        <form  method="get" action="{{url('/searchSupplier')}}" class="mt-1">
            @csrf
        <div class="form-group">
<input type="text" class="form-control" name="searchSupplier" placeholder="search supplier by..name..or phone" value="{{isset($searchSupplier) ? $searchSupplier : ''}}">
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
                <th>Supplier name</th>
                <th>Phone number</th>
                <th>Description</th>
                    <th>Status</th>
                    <th>Amount</th>
                    <th>location</th>
                
            </tr>
        </thead>
        <tbody>
            @foreach ($suppliers as $suppliers)
            <tr>
                <td>{{ $suppliers->supplier_name }}</td>
                <td>{{ $suppliers->phone_number }}</td>
                <td>{{ $suppliers->description }}</td>
                <td>{{$suppliers->status }}</td>
                <td>{{$suppliers->amount }}</td>
                <td>{{$suppliers->location }}</td>
               <!--  <td>
                   <a class="btn btn-warning btn-sm" href="{{ url('editSupplier', $suppliers->id) }}"><i class="fas fa-edit"></i>edit supplier</a>
                    <form action="{{ url('destroySupplier', $suppliers->id) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
        <button type="submit" onclick="return confirm('Are you sure you want to Delete this')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</button>
         </td>
-->
                    <!-- Add delete button with form submission if needed -->
               
            </tr>
            @endforeach
        </tbody>
            </table>
        </div>
    </div>
</div>
@include('jquery')
<script>
      $(document).ready(function() {
        // Show the success message when the page loads
        $('#success').show();

        // Set a timer to hide the success message after 5 seconds
        setTimeout(function() {
            $('#success').fadeOut('slow'); // Fade out slowly
        }, 1000); // 1000 milliseconds = 1 seconds
    });
   </script>


</body>
</html>
