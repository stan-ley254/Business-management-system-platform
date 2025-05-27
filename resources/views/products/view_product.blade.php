@include('navstore')
<div class="container-xxl my-2">
    <!-- Header -->
    <div class="custom-header">
     Manage Products
    </div>
    <div class="message rounded">
        @if(session('success'))
            <div id="success" class="alert alert-success ">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger" id="error">
            {{ session('error') }}
        </div>
    @endif
</div>
    <!-- Form Section -->
    <div class="custom-form-container">
        <form  method="get" action="{{url('/searchProduct')}}" class="mt-1">
            @csrf
        <div class="form-group">
<input type="text" class="form-control" name="searchProduct" placeholder="search product by name...category ..or description" value="{{isset($searchProduct) ? $searchProduct : ''}}">
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
            <th>Product ID</th>
            <th>Product Name</th>
            <th>Description</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Discount</th>
            <th>Category</th>
           
            </tr>
        </thead>
        <tbody>
        @if(isset($product) && $product)
            @foreach($product as $product)
            
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->description }}</td>
                    <td>{{ $product->quantity }}</td>
                    <td>{{ $product->price }}</td>
                    <td>{{ $product->discount_price }}</td>
                    <td>{{ $product->category }}</td>
                   
            @endforeach
        @endif
        </tbody>
            </table>
        </div>
    </div>
</div>


</body>
</html>
