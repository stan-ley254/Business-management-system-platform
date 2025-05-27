@include('navstore')
<div class="container-xxl my-5">
    <!-- Header -->
    <div class="custom-header">
    Debt Items for Customer: {{ $debt->customer->customer_name }}
    </div>
    @if ($debt->items->isEmpty())
        <p>All items related to this debt have been paid for.</p>
    @else
        <!-- Table -->
        <div class="table-responsive custom-table-container">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                        <th>Product Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($debt->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->description }}</td>
                            <td>{{ $item->price }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->updated_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

</body>
</html>
