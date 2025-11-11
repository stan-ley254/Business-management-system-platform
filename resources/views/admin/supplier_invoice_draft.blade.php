<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    @include('admin.css')
    <style>
      /* Lively invoice styles */
      .invoice-header {
        background: linear-gradient(90deg, #0f172a 0%, #0ea5a4 100%);
        color: #fff;
        padding: 1rem 1.25rem;
        border-radius: .5rem;
        margin-bottom: 1rem;
        box-shadow: 0 6px 20px rgba(2,6,23,0.08);
      }

      .invoice-meta .badge {
        font-weight: 600;
        color: #0f172a;
      }

      .supplier-card {
        background: linear-gradient(180deg,#ffffff,#f8fafc);
        border-left: 6px solid #06b6d4;
        padding: 0.9rem;
        border-radius: .5rem;
        box-shadow: 0 6px 18px rgba(15,23,42,0.04);
      }

      .product-avatar {
        width:44px;
        height:44px;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        border-radius:50%;
        color:#fff;
        font-weight:700;
        font-size:1rem;
        flex:0 0 44px;
      }

      .table-hover tbody tr:hover {
        background: rgba(14,165,164,0.04);
      }

      .totals-wrap {
        border-radius:.5rem;
        padding: .75rem;
        background: linear-gradient(180deg,#fff,#fbfdfe);
        box-shadow: 0 6px 18px rgba(2,6,23,0.03);
      }

      .btn-spinner { display: inline-block; margin-left: .5rem; vertical-align: middle; }
      .small-muted { color:#6b7280; font-size: .9rem; }

      @media (max-width: 767px) {
        .invoice-header { flex-direction: column; gap: .5rem; }
        .product-avatar { width:36px; height:36px; font-size:.9rem; }
      }
    </style>
  </head>
  <body>
      @include('admin.sidebar')
      @include('admin.header')

      <div class="main-panel">
        <div class="content-wrapper">
          <div class="container">
            <div class="invoice-header d-flex justify-content-between align-items-center">
              <div>
                <h4 class="mb-0">Draft Invoice
                  <small class="small-muted">#{{ $invoice->invoice_number ?? '—' }}</small>
                </h4>
                <div class="invoice-meta mt-1">
                  <span class="badge bg-white me-2">Status: {{ ucfirst($invoice->status) }}</span>
                  <span class="badge bg-white">Items: {{ $invoice->items->count() }}</span>
                </div>
              </div>

              <div class="text-end">
                <div class="small-muted">Supplier</div>
                <div class="fw-semibold">
                  {{ $invoice->supplier->supplier_name ?? $invoice->supplier->name ?? '—' }}
                </div>
                <div class="small-muted mt-1">
                  {{ $invoice->supplier->phone_number ?? '' }}
                </div>
              </div>
            </div>

            <div class="row g-3 mb-3">
              <div class="col-md-8">
                <div class="supplier-card">
                  <div class="d-flex align-items-center gap-3">
                    <div>
                      <small class="small-muted">Invoice from</small>
                      <div class="fw-semibold">{{ $invoice->supplier->supplier_name ?? '—' }}</div>
                      <div class="small-muted">{{ $invoice->supplier->phone_number ?? '' }}</div>
                    </div>
                    <div class="ms-auto text-end">
                      <small class="small-muted">Draft total</small>
                      <div class="h5 mb-0" id="headerTotal">Ksh {{ number_format($invoice->items->sum(fn($i) => $i->cost_price * $i->quantity), 2) }}</div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-4">
                <div class="totals-wrap text-end">
                  <div class="mb-2 small-muted">Summary</div>
                  <div class="fw-semibold">Items: {{ $invoice->items->count() }}</div>
                  <div class="fw-semibold mt-2">Total: <span id="grandTotal">Ksh {{ number_format($invoice->items->sum(fn($i) => $i->cost_price * $i->quantity), 2) }}</span></div>
                </div>
              </div>
            </div>

            <form action="{{ route('supplier.invoice.updateDraft', $invoice->id) }}" method="POST" id="draftInvoiceForm">
              @csrf
              <div class="card">
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table class="table table-hover mb-0">
                      <thead class="table-light">
                        <tr>
                          <th>Product</th>
                          <th width="150">Cost Price (Ksh)</th>
                          <th width="120">Quantity</th>
                          <th width="150" class="text-end">Subtotal</th>
                        </tr>
                      </thead>
                      <tbody id="invoice-items">
                        @foreach($invoice->items as $item)
                          @php
                            $name = $item->product_name ?? $item->supplierProduct->supplier_product_name ?? '—';
                            $avatarBg = '#'.substr(md5($name), 0, 6);
                          @endphp
                          <tr data-item-id="{{ $item->id }}">
                            <td class="align-middle">
                              <div class="d-flex align-items-center gap-3">
                                <div class="product-avatar" style="background: {{ $avatarBg }};">
                                  {{ strtoupper(mb_substr($name, 0, 1)) }}
                                </div>
                                <div>
                                  <div class="fw-semibold">{{ $name }}</div>
                                  <div class="small-muted small">
                                    {{ $item->description ?? ($item->supplierProduct->description ?? '') }}
                                  </div>
                                  <div class="small-muted small mt-1">
                                    Barcode: {{ $item->barcode ?? ($item->supplierProduct->barcode ?? '—') }}
                                  </div>
                                </div>
                              </div>
                            </td>

                            <td class="align-middle">
                              <input type="number" step="0.01" name="items[{{ $item->id }}][cost_price]"
                                     value="{{ $item->cost_price }}" class="form-control form-control-sm cost-price" />
                            </td>

                            <td class="align-middle">
                              <input type="number" min="1" name="items[{{ $item->id }}][quantity]"
                                     value="{{ $item->quantity }}" class="form-control form-control-sm quantity" />
                            </td>

                            <td class="text-end align-middle">
                              <span class="subtotal">Ksh {{ number_format($item->cost_price * $item->quantity, 2) }}</span>
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                  <a href="{{ route('suppliers.view') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Suppliers
                  </a>
                  <a href="{{ route('suppliers.products', $invoice->supplier_id) }}" class="btn btn-outline-secondary ms-2">
                    <i class="fas fa-box me-1"></i> View Products
                  </a>
                </div>

                <div class="d-flex gap-2">
                  <button type="submit" class="btn btn-outline-primary" id="updateDraftBtn">
                    <i class="fas fa-save me-1"></i> Update Draft
                    <span class="btn-spinner spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:none;"></span>
                  </button>
                </div>
              </div>
            </form>

            <form action="{{ route('invoice.draft.confirm', $invoice->id) }}" method="POST" class="mt-3 text-end" id="confirmForm">
              @csrf
              <button type="submit" class="btn btn-success" id="confirmBtn">
                <i class="fas fa-check-circle me-1"></i> Confirm Invoice
                <span class="btn-spinner spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:none;"></span>
              </button>
            </form>
          </div>
        </div>
      </div>

    @include('admin.script')

    <script>
      // live subtotal / grand total recalculation and button UX
      document.addEventListener('DOMContentLoaded', function () {
        const updateForm = document.getElementById('draftInvoiceForm');
        const confirmForm = document.getElementById('confirmForm');
        const updateBtn = document.getElementById('updateDraftBtn');
        const confirmBtn = document.getElementById('confirmBtn');
        const updateSpinner = updateBtn.querySelector('.btn-spinner');
        const confirmSpinner = confirmBtn.querySelector('.btn-spinner');

        function parseNumber(v) {
          return Number(String(v || '').replace(/[^0-9.-]+/g, '')) || 0;
        }

        function recalc() {
          const rows = document.querySelectorAll('#invoice-items tr');
          let grand = 0;
          rows.forEach(row => {
            const priceEl = row.querySelector('.cost-price');
            const qtyEl = row.querySelector('.quantity');
            const subtotalEl = row.querySelector('.subtotal');
            const price = parseNumber(priceEl.value);
            const qty = parseNumber(qtyEl.value);
            const sub = price * qty;
            subtotalEl.textContent = 'Ksh ' + sub.toFixed(2);
            grand += sub;
          });
          document.getElementById('grandTotal').textContent = 'Ksh ' + grand.toFixed(2);
          const headerTotal = document.getElementById('headerTotal');
          if (headerTotal) headerTotal.textContent = 'Ksh ' + grand.toFixed(2);
        }

        // attach input listeners
        document.querySelectorAll('.cost-price, .quantity').forEach(el => {
          el.addEventListener('input', recalc);
        });

        // show spinner on submit
        updateForm.addEventListener('submit', function () {
          updateBtn.setAttribute('disabled', 'disabled');
          updateSpinner.style.display = 'inline-block';
        });

        confirmForm.addEventListener('submit', function () {
          confirmBtn.setAttribute('disabled', 'disabled');
          confirmSpinner.style.display = 'inline-block';
        });

        // initial recalc
        recalc();
      });
    </script>
  </body>
</html>