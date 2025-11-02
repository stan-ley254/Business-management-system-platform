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
              <h5 class="mb-0">Add Product — Supplier: {{ $supplier->supplier_name ?? '—' }}</h5>
              <a href="{{ route('suppliers.products', $supplier->id) }}" class="btn btn-sm btn-secondary">Back to Products</a>
            </div>
          </div>

          @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <div class="card">
            <div class="card-body">
              <form action="{{ route('suppliers.products.store', $supplier->id) }}" method="POST" novalidate>
                @csrf

                <div class="mb-3">
                  <label for="supplier_product_name" class="form-label">Product Name</label>
                  <input
                    type="text"
                    id="supplier_product_name"
                    name="supplier_product_name"
                    value="{{ old('supplier_product_name') }}"
                    class="form-control @error('supplier_product_name') is-invalid @enderror"
                    required
                  >
                  @error('supplier_product_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="row">
                  <div class="col-md-4 mb-3">
                    <label for="default_cost_price" class="form-label">Cost Price</label>
                    <input
                      type="number"
                      step="0.01"
                      id="default_cost_price"
                      name="default_cost_price"
                      value="{{ old('default_cost_price') }}"
                      class="form-control @error('default_cost_price') is-invalid @enderror"
                    >
                    @error('default_cost_price')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="col-md-4 mb-3">
                    <label for="barcode" class="form-label">Barcode</label>
                    <input
                      type="text"
                      id="barcode"
                      name="barcode"
                      value="{{ old('barcode') }}"
                      class="form-control @error('barcode') is-invalid @enderror"
                    >
                    @error('barcode')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="col-md-4 mb-3">
                    <label for="linked_product_id" class="form-label">Link to System Product (optional)</label>
                    <select
                      id="linked_product_id"
                      name="linked_product_id"
                      class="form-select @error('linked_product_id') is-invalid @enderror"
                    >
                      <option value="">-- Not linked --</option>
                      @foreach($systemProducts as $p)
                        <option value="{{ $p->id }}" {{ old('linked_product_id') == $p->id ? 'selected' : '' }}>
                          {{ $p->product_name }}
                        </option>
                      @endforeach
                    </select>
                    @error('linked_product_id')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <div class="mb-3">
                  <label for="description" class="form-label">Description</label>
                  <textarea
                    id="description"
                    name="description"
                    class="form-control @error('description') is-invalid @enderror"
                    rows="3"
                  >{{ old('description') }}</textarea>
                  @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="d-flex gap-2">
                  <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Product
                  </button>

                  <a href="{{ route('suppliers.products', $supplier->id) }}" class="btn btn-secondary">
                    Cancel
                  </a>
                </div>
              </form>
            </div>
          </div>

        </div>
      </div>
    </div>

    @include('admin.script')
  </body>
</html>