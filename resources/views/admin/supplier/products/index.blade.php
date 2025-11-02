<!DOCTYPE html>
<html lang="en">
  <head>
    @include('admin.css')
  </head>
  <body>
    @include('admin.sidebar')
    @include('admin.header')

    <div class="main-panel">
      <div class="content-wrapper">
        <div class="container-md">
          <div class="card mb-3 mt-2">
            <div class="card-body d-flex justify-content-between align-items-center">
              <h5 class="mb-0">Supplier: {{ $supplier->supplier_name ?? '—' }}</h5>
              <a href="{{ url('suppliers') }}" class="btn btn-sm btn-secondary">Back to Suppliers</a>
            </div>
          </div>

          <div class="table-responsive">
            <table class="table table-bordered table-striped">
              <thead class="thead-light">
                <tr>
                  <th>Product Name</th>
                  <th>Cost Price</th>
                  <th>Barcode</th>
                  <th>Linked System Product</th>
                  <th class="text-center">Actions</th>
                </tr>
              </thead>

              <tbody>
                @forelse($products as $product)
                  <tr>
                    <td>{{ $product->supplier_product_name }}</td>
                    <td>{{ isset($product->default_cost_price) ? number_format($product->default_cost_price, 2) : '—' }}</td>
                    <td>{{ $product->barcode ?? '—' }}</td>
                    <td>
                      @if($product->linkedProduct)
                        <a href="{{ route('suppliers.products.edit', $product->id) }}" class="text-decoration-none">
                          {{ $product->linkedProduct->product_name }}
                        </a>
                      @else
                        <span class="text-muted">Not linked</span>
                      @endif
                    </td>
                    <td class="text-center">
                      <a href="{{ route('suppliers.products.edit', $product->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Edit
                      </a>

                      <!-- Link to system product (opens edit/link UI) -->
                      <a href="{{ route('suppliers.products.edit', $product->id) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-link"></i> Link
                      </a>

                      <!-- Add to draft invoice (posts product to draft) -->
                      <form action="{{ url('invoice/draft/add', $product->id) }}" method="POST" class="d-inline-block">
                        @csrf
                        <input type="hidden" name="supplier_product_id" value="{{ $product->id }}">
                        <input type="hidden" name="name" value="{{ $product->supplier_product_name }}">
                        <input type="hidden" name="price" value="{{ $product->default_cost_price ?? 0 }}">
                        <button type="submit" class="btn btn-success btn-sm">
                          <i class="fas fa-receipt"></i> Add to Invoice
                        </button>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="text-center text-muted">No products found for this supplier.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    @include('admin.script')
  </body>
</html>