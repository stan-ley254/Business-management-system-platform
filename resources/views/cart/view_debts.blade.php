@include('navstore')
<div class="container-xxl my-2">
    <!-- Header -->
    <div class="custom-header">
     Manage Dedts
    </div>
    <div class="message d-print-inline-flex rounded">
    @if(session('success'))
        <div class="alert alert-success" id="success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
</div>
    <!-- Form Section -->
    <div class="custom-form-container">
        <form  method="get" action="{{url('/searchDebt')}}" class="mt-1">
            @csrf
        <div class="form-group">
<input type="text" class="form-control" name="searchDebt" placeholder="search customer by status..paid..unpaid" value="{{isset($searchDebt) ? $searchDebt : ''}}">
<button type="submit" class="btn btn-custom mt-2"><i class="fas fa-search"></i> Search</button>
</form>
</div>
    </div>

    <!-- Table Section -->
    <div class="custom-table-container">
    @if ($debts->count() > 0)
        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
            <thead>
<tr>
<th>ID</th>
                    <th>Customer Name</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                <!-- Add more table headers as needed -->
            </tr>
        <tbody>
        @foreach($debts as $debt)
                    <tr>
                        <td>{{ $debt->id }}</td>
                        <td>{{ $debt->customer->customer_name }}</td>
                        <td>{{ $debt->amount }}</td>
                        <td>{{ $debt->status ? 'Paid' : 'Unpaid' }}</td>
                        <td>{{ $debt->updated_at }}</td>
                        <td>
                           <!-- Settle Debt Form -->
<form action="{{ url('settleDebt') }}" method="POST" class="form-group">
  @csrf
  <input type="hidden" name="debt_id" value="{{ $debt->id }}">
  <input type="number" min="0" name="amount" step="0.01" placeholder="Amount">
  <button type="submit" class="btn btn-success"><i class="fas fa-dollar-sign"></i> Settle</button>
</form>

<!-- Debt Items Button -->
<a href="{{ url('debtItems', $debt->id) }}" class="btn btn-info"><i class="fas fa-list"></i> Debt Items</a>
                        </td>
                    </tr>
            @endforeach
          
  </tbody>
            </table>
            <div class="d-flex justify-content-center">
            {{ $debts->links() }}
        </div>
</div>
@else
<p class="text-info">No Debts yet</p>
        @endif
    </div>
</div>

</body>
</html>
