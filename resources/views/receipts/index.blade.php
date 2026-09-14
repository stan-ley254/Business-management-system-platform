@include('navstore')

<div class="container-xxl">
    @if(isset($receipts))
       
        <div class="custom-header">
     My Receipts
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
                                    <th>Receipt #</th>
                                    <th>Date</th>
                                    <th>Cashier</th>
                                    <th>Customer</th>
                                    <th>Payment Status</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
 <tbody>
    @forelse($receipts as $receipt)
        <tr>
            <td>{{ $receipt->cart_id }}</td>
            <td>{{ $receipt->created_at }}</td>
            <td>{{ optional($receipt->cashier)->name ?? 'Unknown' }}</td>
            <td>{{ $receipt->customer_name }}</td>
            <td>{{ $receipt->payment_status }}</td>
            <td>{{ number_format($receipt->total, 2) }}</td>
            <td>
                <a class="btn btn-sm btn-primary" href="{{ route('receipt.download', ['cartId' => $receipt->cart_id]) }}">Download</a>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7">No receipts found.</td>
        </tr>
    @endforelse
</tbody>


                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $receipts->links() }}
                    </div>
                </div>
            </div>

            <!-- Sidebar Section -->
            <div class="col-lg-3 col-md-12">
                <div class="custom-section">
                    <form action="{{ url('/filterReceipts') }}" method="POST" class="form-inline mt-2">
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
                            <button type="submit" class="btn btn-custom mt-2"><i class="fas fa-filter"></i> Filter Receipts</button>
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
