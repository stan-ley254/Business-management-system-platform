<!DOCTYPE html>
<html lang="en">
  <head>
    @include('admin.css')
    <style>
       .scroll-container {
            width: auto;
            height: 100vw;
            overflow: auto;
            cursor: grab;
            user-select: none;
        }
        .scroll-container:active { cursor: grabbing; }
    </style>
  </head>
  <body>
    @include('admin.sidebar')
    @include('admin.header')

    <div class="main-panel">
      <div class="content-wrapper">
        <div class="container-md">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Suppliers</h4>
            <a href="{{ url('/createSupplier') }}" class="btn btn-primary btn-sm">New Supplier</a>
          </div>

          @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @endif
          @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
          @endif

          <div class="card">
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-bordered table-striped table-secondary mb-0">
                  <thead class="thead-light">
                    <tr>
                      <th>Supplier Name</th>
                      <th>Phone</th>
                      <th>Balance</th>
                      <th>Status</th>
                      <th>Actions</th>
                    </tr>
                  </thead>

                  <tbody>
                    @forelse($suppliers ?? [] as $supplier)
                      <tr>
                        <td>{{ $supplier->supplier_name }}</td>
                        <td>{{ $supplier->phone_number ?? '—' }}</td>
                        <td>{{ number_format($supplier->balance ?? $supplier->amount ?? 0, 2) }}</td>
                        <td>{{ ucfirst($supplier->status ?? '—') }}</td>
                        <td class="d-flex gap-2">
                          <a href="{{ route('suppliers.invoices.draft', $supplier->id) }}" class="btn btn-sm btn-outline-primary">
  <i class="mdi mdi-file-invoice"></i> View Draft Invoice
</a>


                          <a href="{{ route('suppliers.products', $supplier->id) }}" class="btn btn-sm btn-outline-info">
                            <i class="fas fa-box"></i> View Products
                          </a>

                          <a href="{{ route('suppliers.products.create', $supplier->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-plus"></i> Add Product
                          </a>

                          <a href="{{ route('suppliers.invoice.create', $supplier->id) }}" class="btn btn-sm btn-success">
                            <i class="fas fa-file-invoice-dollar"></i> Create Invoice
                          </a>

                          <form action="{{ route('suppliers.products.destroy', $supplier->id) }}" method="POST" class="m-0" onsubmit="return confirm('Are you sure you want to delete this supplier?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                              <i class="fas fa-trash"></i> Delete
                            </button>
                          </form>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="5" class="text-center text-muted">No suppliers found.</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    @include('admin.script')
  </body>
</html>
