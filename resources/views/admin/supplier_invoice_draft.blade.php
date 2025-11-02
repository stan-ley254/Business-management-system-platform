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
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Draft Invoice - {{ $invoice->invoice_number }}</h4>
    <a href="{{ route('suppliers.products', $invoice->supplier->id) }}" class="btn btn-secondary btn-sm">
      Add More Products
    </a>
  </div>

  <div class="card">
    <div class="card-body">
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <table class="table table-bordered">
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
            <td>{{ $item->product_name }}</td>
            <td>{{ number_format($item->cost_price, 2) }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ number_format($item->cost_price * $item->quantity, 2) }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>

      <div class="mt-3 text-end">
        <h5>
          Total: KES {{ number_format($invoice->items->sum(fn($i) => $i->cost_price * $i->quantity), 2) }}
        </h5>
      </div>

      <form action="{{ route('invoice.draft.confirm', $invoice->id) }}" method="POST" class="mt-3 text-end">
        @csrf
        <button type="submit" class="btn btn-success">
          <i class="fas fa-check-circle"></i> Confirm Invoice
        </button>
      </form>
    </div>
  </div>
</div>
<!-- container-scroller -->
    @include('admin.script')
  </body>
</html>