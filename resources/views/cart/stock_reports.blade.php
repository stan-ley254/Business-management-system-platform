@include('navstore')
<div class="container-xxl my-5">
    <!-- Header -->
    <div class="custom-header">
        Products Stock Reports
    </div>
    @if($products->count())
        <!-- Table -->
        <div class="table-responsive custom-table-container">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ $product->description }}</td>
                        <td>{{ $product->quantity }}</td>
                        <td>
                            @if($product->quantity == 0)
                                <span class="text-danger">Out of Stock</span>
                            @elseif($product->quantity <= 5)
                                <span class="text-warning">Low Stock</span>
                            @else
                                <span class="text-success">In Stock</span>
                            @endif
                        </td>
                        <td>{{$product->updated_at}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @else
            <p>No Stock Reports Yet</p>
            @endif
        </div>
    </div>
</div>

</body>
</html>
