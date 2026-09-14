<!DOCTYPE html>
<html lang="en">
  <head>
    @include('admin.css')
    <style>
      .strikethrough {
        text-decoration: line-through;
        color: red;
      }
      body { margin: 0; padding: 0 }
    </style>
  </head>
  <body>
    @include('admin.sidebar')
    @include('admin.header')
    <div class="main-panel">
      <div class="content-wrapper">
        <div class="container-md mt-2">
          <div class="card">
            <div class="card-body">
              <div class="row g-3 mb-4">
                <div class="col-12 col-md-4">
                  <form method="POST" action="{{url('searchSales')}}">
                    @csrf
                    <div class="input-group">
                      <input type="text" class="input-group" name="searchSales" placeholder="search receipts by cart_id or customer..." value="{{isset($searchSales) ? $searchSales : ''}}">
                      <button type="submit" class="btn btn-success mt-1">Search</button>
                    </div>
                  </form>
                </div>
                <div class="col-12 col-md-5">
                  <form method="POST" action="{{ url('/filter_receipts') }}">
                    @csrf
                    <div class="row g-2">
                      <div class="col-6">
                        <input type="date" class="input-group" name="from_date" required>
                      </div>
                      <div class="col-6">
                        <input type="date" class="input-group" name="to_date" required>
                      </div>
                      <div class="col-12">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                      </div>
                    </div>
                  </form>
                </div>
                <div class="col-12 col-md-3 text-md-end">
                  <div class="d-flex flex-column flex-md-row gap-2">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary w-100">Back</a>
                  </div>
                </div>
              </div>

              @if(isset($receipts) && ($receipts))
                @if(session('success'))
                  <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                  <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <h2 class="mb-3 text-success">All Business Receipts</h2>

                <div class="table-responsive">
                  <table class="table table-bordered jsgrid jsgrid-table dataTables_wrapper table-primary">
                    <thead>
                      <tr>
                        <th>Receipt #</th>
                        <th>Date</th>
                        <th>Cashier</th>
                        <th>Customer</th>
                        <th>Payment Status</th>
                        <th>Total</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($receipts as $receipt)
                        <tr>
                          <td>{{ $receipt->cart_id }}</td>
                          <td>{{ $receipt->created_at }}</td>
                          <td>{{ optional($receipt->cashier)->name ?? 'Unknown' }}</td>
                          <td>{{ $receipt->customer_name }}</td>
                          <td>{{ $receipt->payment_status }}</td>
                          <td>{{ number_format($receipt->total, 2) }}</td>
                          <td>
                            <a class="btn btn-primary btn-sm" href="{{ route('receipt.download', ['cartId' => $receipt->cart_id]) }}">Download</a>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>

                <div class="mt-4">
                  {{ $receipts->links() }}
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

    @include('admin.script')
  </body>
</html>