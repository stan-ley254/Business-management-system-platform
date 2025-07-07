@include('navstore')
<div class="container-xxl my-4">
    <!-- Header -->
    <div class="custom-header">
     Manage Held Carts
    </div>

    <!-- Table Section -->
    <div class="custom-table-container">
    @if ($heldCart->count() > 0)
        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
            <thead>
            <tr>
            <th>Cart ID</th>
                    <th>Product Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($heldCart as $cart)
                    <tr>
                        <td>{{ $cart->cart_id }}</td>
                        <td>{{ $cart->product_name }}</td>
                        <td>{{ $cart->description }}</td>
                        <td>{{ $cart->price }}</td>
                        <td>{{ $cart->quantity }}</td>
                        <td>{{ $cart->price * $cart->quantity }}</td>
                        <td>
                            @if ($loop->first)
                               <!-- Resume Cart Button -->
<form action="{{ url('resumeCart', $cart->cart_id) }}" method="POST" style="display: inline;">
  @csrf
  <button type="submit" class="btn btn-success rounded"><i class="fas fa-play"></i> Resume</button>
</form>
                                <form action="{{ url('deleteCart', $cart->cart_id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-danger rounded"><i class="fas fa-trash"></i> Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
    
        </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $heldCart->links() }}
        </div>
    @else
        <p>No held carts yet.</p>
    @endif
    </div>
</div>
<script src="{{asset('/js/jquery-3.7.1.min.js')}}"></script>
<script src="{{asset('/js/scriptsfiles.js')}}" ></script>
</body>
</html>
