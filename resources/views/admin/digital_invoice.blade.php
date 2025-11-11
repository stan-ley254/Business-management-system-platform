<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    @include('admin.css')
    <style>
      /* Lively invoice styles */
      .invoice-header {
        background: linear-gradient(90deg, #4f46e5 0%, #06b6d4 100%);
        color: #fff;
        padding: 1.25rem;
        border-radius: 0.5rem;
        margin-bottom: 1.25rem;
      }

      .invoice-meta .badge {
        font-weight: 600;
      }

      .supplier-card {
        border-left: 4px solid #06b6d4;
        padding: 1rem;
        border-radius: .5rem;
        background: #fff;
        box-shadow: 0 6px 18px rgba(15,23,42,0.04);
      }

      .product-avatar {
        width: 44px;
        height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: #fff;
        font-weight: 700;
      }

      .table-hover tbody tr:hover {
        background: rgba(99,102,241,0.04);
      }

      .totals-card {
        background: linear-gradient(180deg,#f8fafc,#ffffff);
        padding: 1rem;
        border-radius: .5rem;
        box-shadow: 0 6px 18px rgba(15,23,42,0.03);
      }

      .btn-restock:disabled {
        opacity: .7;
        pointer-events: none;
      }
    </style>
  </head>
  <body>
      @include('admin.sidebar')
      @include('admin.header')

      <div class="main-panel">
        <div class="content-wrapper">
          @if(session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close" aria-hidden="true">
                <span aria-hidden="true">&times;</span>
              </button>
              {{ session('message') }}
            </div>
          @endif

          <div class="container mt-4">
            <div class="invoice-header d-flex justify-content-between align-items-center">
              <div>
                <h4 class="mb-1">Invoice <small class="text-white-50">#{{ $invoice->invoice_number }}</small></h4>
                <div class="invoice-meta">
                  <span class="badge bg-white text-dark me-2">Status: {{ ucfirst($invoice->status) }}</span>
                  <span class="badge bg-white text-dark">Items: {{ $invoice->items->count() }}</span>
                </div>
              </div>

              <div class="text-end">
                <div class="text-white-50 small">Confirmed</div>
                <div class="h5 mb-0">
                  @php
                    $confirmedAt = $invoice->confirmed_at ?? null;
                  @endphp
                  {{ $confirmedAt ? \Illuminate\Support\Carbon::parse($confirmedAt)->format('Y-m-d H:i') : '—' }}
                </div>
              </div>
            </div>

            <div class="row g-3 mb-3">
              <div class="col-md-8">
                <div class="supplier-card">
                  <div class="d-flex align-items-center gap-3">
                    <div>
                      <small class="text-muted">Supplier</small>
                      <h5 class="mb-0">
                        {{ $invoice->supplier->supplier_name ?? $invoice->supplier->name ?? '—' }}
                      </h5>
                      <div class="text-muted small">
                        @if(!empty($invoice->supplier->phone_number)) {{ $invoice->supplier->phone_number }} @endif
                      </div>
                    </div>

                    <div class="ms-auto text-end">
                      <small class="text-muted">Total</small>
                      <div class="h4 mb-0">Ksh {{ number_format($invoice->items->sum(fn($i) => $i->cost_price * $i->quantity), 2) }}</div>
                      <small class="text-muted">Invoice total cost</small>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-4">
                <div class="totals-card">
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                      <small class="text-muted">Tax</small>
                      <div class="fw-semibold">Ksh 0.00</div>
                    </div>
                    <div class="text-end">
                      <small class="text-muted">Items</small>
                      <div class="fw-semibold">{{ $invoice->items->count() }}</div>
                    </div>
                  </div>

                  <div class="d-grid gap-2">
                    <form id="restockForm" action="{{ route('supplier.invoice.restock', $invoice->id) }}" method="POST">
                      @csrf
                      <button id="restockBtn" type="submit" class="btn btn-primary btn-restock">
                        <i class="fas fa-warehouse me-1"></i>
                        <span id="restockText">Restock Now</span>
                        <span id="restockSpinner" class="spinner-border spinner-border-sm ms-2" role="status" style="display:none;" aria-hidden="true"></span>
                      </button>
                    </form>

                    <a href="{{ route('suppliers.view') }}" class="btn btn-outline-secondary">
                      <i class="fas fa-arrow-left me-1"></i> Back to Suppliers
                    </a>
                  </div>
                </div>
              </div>
            </div>

            <div class="card">
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table table-hover mb-0">
                    <thead class="table-light">
                      <tr>
                        <th class="text-start">Product</th>
                        <th>Cost Price</th>
                        <th>Quantity</th>
                        <th class="text-end">Subtotal</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($invoice->items as $item)
                        @php
                          $name = $item->product_name ?? $item->supplierProduct->supplier_product_name ?? '—';
                          $avatarBg = '#'.substr(md5($name), 0, 6);
                        @endphp
                        <tr>
                          <td class="align-middle">
                            <div class="d-flex align-items-center gap-3">
                              <div class="product-avatar" style="background: {{ $avatarBg }};">
                                {{ strtoupper(mb_substr($name, 0, 1)) }}
                              </div>
                              <div>
                                <div class="fw-semibold">{{ $name }}</div>
                                <div class="small text-muted">
                                  {{ $item->description ?? ($item->supplierProduct->description ?? '') }}
                                  @if(empty($item->description) && !empty($item->supplierProduct->description))
                                    <span class="text-muted"> (from supplier product)</span>
                                  @endif
                                </div>
                                <div class="small text-muted mt-1">
                                  Barcode: {{ $item->barcode ?? ($item->supplierProduct->barcode ?? '—') }}
                                </div>
                              </div>
                            </div>
                          </td>

                          <td class="align-middle">Ksh {{ number_format($item->cost_price, 2) }}</td>
                          <td class="align-middle">{{ $item->quantity }}</td>
                          <td class="text-end align-middle">Ksh {{ number_format($item->cost_price * $item->quantity, 2) }}</td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>

    @include('admin.script')

    <script>
      // simple UX: show spinner when restock submitted
      document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('restockForm');
        const btn = document.getElementById('restockBtn');
        const spinner = document.getElementById('restockSpinner');
        const restockText = document.getElementById('restockText');

        if (form) {
          form.addEventListener('submit', function () {
            btn.setAttribute('disabled', 'disabled');
            spinner.style.display = 'inline-block';
            restockText.textContent = 'Restocking...';
          });
        }
      });
    </script>
  </body>
</html>