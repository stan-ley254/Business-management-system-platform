<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    @include('admin.css')
    <style>
        .sidebar{
        position: fixed;
        }
        #importProgress {
    display: none;
    margin-top: 10px;
  }

  .progress-container {
    width: 100%;
    background-color: #f5f5f5;
    border-radius: 5px;
    height: 25px;
    overflow: hidden;
    margin-top: 10px;
  }

  .progress-bar-custom {
    height: 100%;
    width: 0;
    background-color: #4caf50;
    transition: width 0.4s ease;
    text-align: center;
    color: white;
    line-height: 25px;
  }

  #importMessage {
    font-weight: bold;
    color: #0d6efd;
    margin-top: 10px;
  }
      
    </style>
    
  </head>
  <body>
 
      <!-- partial:partials/_sidebar.html -->
      @include('admin.sidebar')
      <!-- partial -->
      @include('admin.header')
        <!-- partial -->
        <div class="main-panel">
       
          <div class="content-wrapper">

   <div class="container">
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
<div class="container mt-4">
    <h3>Draft Invoice - {{ $invoice->supplier->name }}</h3>
    <p><strong>Status:</strong> <span class="badge bg-warning">{{ ucfirst($invoice->status) }}</span></p>

    @if (session('success'))
        <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif

    <form action="{{ route('supplier.invoice.updateDraft', $invoice->id) }}" method="POST" id="draftInvoiceForm">
        @csrf

        <table class="table table-bordered mt-4 align-middle">
            <thead>
                <tr>
                    <th>Product</th>
                    <th width="150">Cost Price (Ksh)</th>
                    <th width="120">Quantity</th>
                    <th width="150">Subtotal</th>
                </tr>
            </thead>
            <tbody id="invoice-items">
                @foreach($invoice->items as $item)
                    <tr>
                        <td>
  {{ $item->product_name 
      ?? ($item->supplierProduct->supplier_product_name ?? '—') 
  }}
</td>
                        <td>
                            <input type="number" step="0.01" name="items[{{ $item->id }}][cost_price]" 
                                   value="{{ $item->cost_price }}" 
                                   class="form-control form-control-sm cost-price">
                        </td>
                        <td>
                            <input type="number" min="1" name="items[{{ $item->id }}][quantity]" 
                                   value="{{ $item->quantity }}" 
                                   class="form-control form-control-sm quantity">
                        </td>
                        <td class="subtotal">{{ number_format($item->cost_price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-between mt-3">
            <h5>Total: Ksh <span id="grandTotal">{{ number_format($invoice->items->sum(fn($i) => $i->cost_price * $i->quantity), 2) }}</span></h5>
            <button type="submit" class="btn btn-outline-primary">
                <i class="fas fa-save"></i> Update Draft
            </button>
        </div>
    </form>

    <form action="{{ route('invoice.draft.confirm', $invoice->id) }}" method="POST" class="mt-3 text-end">
        @csrf
        <button type="submit" class="btn btn-success">
            <i class="fas fa-check-circle"></i> Confirm Invoice
        </button>
    </form>

    <a href="{{ route('suppliers.view') }}" class="btn btn-outline-secondary mt-3">← Back to Suppliers</a>
</div>


            </div>
            </div>
            <!-- main-panel ends -->
        </div>
<!-- container-scroller -->
    @include('admin.script')
  </body>
</html>