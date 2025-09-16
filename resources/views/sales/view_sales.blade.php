@include('navstore')

<div class="container-xxl">
    @if(isset($sales) && ($sales))
       
        <div class="custom-header">
     Manage All Sales
    </div>

  <div id="alert-container" class="message rounded">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>
    @if(isset($message))
    <div class="alert alert-info">
        {{ $message }}
    </div>
@endif

        <div class="row">
            <!-- Main content: table and form -->
            <div class="col-lg-9 col-md-12">
                <div class="custom-form-container">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>SaleID</th>
                                    <th>Product Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>ActivePrice</th>
                                    <th>Quantity</th>
                                    <th>Total (per item)</th>
                                    <th>Payment Status</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
 <tbody id="sales-data">
    @php $periodTotal = 0; @endphp
    @foreach($sales as $sale)
        @php 
            $lineTotal = ($sale->active_price ?? $sale->price) * $sale->quantity; 
            if($sale->status !== 'restored') {
                $periodTotal += $lineTotal;
            }
        @endphp
        <tr class="{{ $sale->status === 'restored' ? 'restored-row' : '' }}">
            <td>{{ $sale->cart_id }}</td>
            <td>{{ $sale->product_name }}</td>
            <td>{{ $sale->description }}</td>
            <td>
                @if($sale->active_price)
                    <span class="strikethrough">{{ $sale->price }}</span>
                @else
                    {{ $sale->price }}
                @endif
            </td>
            <td>{{ $sale->active_price ?? 'N/A' }}</td>
            <td>{{ $sale->quantity }}</td>

            {{-- Total column --}}
            <td>
                @if($sale->status === 'restored')
                    <span class="text-muted strikethrough">{{ number_format($lineTotal, 2) }}</span>
                @else
                    {{ number_format($lineTotal, 2) }}
                @endif
            </td>

            <td>{{ $sale->payment_status }}</td>
            <td>
                @if($sale->status === 'restored')
                    <span class="badge bg-danger">Restored</span>
                @else
                    <span class="badge bg-success">Active</span>
                @endif
            </td>
            <td>{{ $sale->updated_at }}</td>
        </tr>
    @endforeach
</tbody>


                        </table>
                    </div>
                     <div class="mt-4">
                <h4>Period Total: {{ number_format($periodTotal, 2) }}</h4>
              </div>
                </div>
            </div>

            <!-- Sidebar Section -->
            <div class="col-lg-3 col-md-12">
                <div class="custom-section">
                    <form action="{{ url('/filterSales') }}" method="POST" class="form-inline mt-2">
                        @csrf
                        <div class="form-group">
                            <label for="from_date">From Date:</label>
                            <input type="date" class="form-control" id="from_date" name="from_date" required>
                        </div>
                        <div class="form-group">
                            <label for="to_date">To Date:</label>
                            <input type="date" class="form-control" id="to_date" name="to_date" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-custom mt-2"><i class="fas fa-filter"></i> Filter Sales</button>
                        </div>
                    </form>
                </div>

                <div class="custom-section">
                    <form method="post" action="{{ url('/searchSalesCart') }}">
                        @csrf
                        <div class="form-group">
                            <input type="text" class="form-control" name="searchSalesCart" placeholder="search sales..by product name" value="{{ isset($searchSalesCart) ? $searchSalesCart : '' }}">
                            <button type="submit" class="btn btn-custom mt-2"><i class="fas fa-search"></i> Search</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
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

        $('#error').show();

        // Set a timer to hide the success message after 5 seconds
        setTimeout(function() {
            $('#error').fadeOut('slow'); // Fade out slowly
        }, 1000);
    });
   </script>
</body>
</html>
