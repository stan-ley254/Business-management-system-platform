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
      @if(session()->has('message'))
<div class="alert alert-success alert-dismissible fade show">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close" aria-hidden="true">
        <span aria-hidden="true">&times;</span>
    </button>
    {{session()->get('message')}}
</div>
            @endif
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
    <h3>Confirmed Invoice: {{ $invoice->invoice_number }}</h3>
    <p><strong>Supplier:</strong> {{ $invoice->supplier->name }}</p>
    <p><strong>Status:</strong> <span class="badge bg-success">{{ ucfirst($invoice->status) }}</span></p>
    <p><strong>Confirmed At:</strong> {{ $invoice->confirmed_at->format('Y-m-d H:i') }}</p>

    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Product</th>
                <th>Cost Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td>
  {{ $item->product_name 
      ?? ($item->supplierProduct->supplier_product_name ?? '—') 
  }}
</td>
                    <td>{{ number_format($item->cost_price, 2) }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->cost_price * $item->quantity, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-3">
        <h5>Total Cost: Ksh {{ number_format($invoice->items->sum(fn($i) => $i->cost_price * $i->quantity), 2) }}</h5>
    </div>

    <form action="{{ route('supplier.invoice.restock', $invoice->id) }}" method="POST" class="mt-4">
        @csrf
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-warehouse"></i> Restock Now
        </button>
    </form>

    <div class="mt-3">
        <a href="{{ route('suppliers.view') }}" class="btn btn-outline-secondary">← Back to Suppliers</a>
    </div>
</div>
<!-- container-scroller -->
    @include('admin.script')
  </body>
</html>