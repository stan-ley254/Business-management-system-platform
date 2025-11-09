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
    <h4>Set Selling Prices for New Products</h4>
    <form action="{{ route('admin.storeNewProducts') }}" method="POST">
        @csrf
        @foreach ($newProducts as $index => $product)
        <div class="card p-3 mb-3">
            <h5>{{ $product['product_name'] }}</h5>
            <input type="hidden" name="products[{{ $index }}][product_name]" value="{{ $product['product_name'] }}">
            <input type="hidden" name="products[{{ $index }}][description]" value="{{ $product['description'] }}">
            <input type="hidden" name="products[{{ $index }}][category]" value="{{ $product['category'] }}">
            <input type="hidden" name="products[{{ $index }}][cost_price]" value="{{ $product['cost_price'] }}">
            <input type="hidden" name="products[{{ $index }}][quantity]" value="{{ $product['quantity'] }}">

            <p><strong>Cost Price:</strong> KES {{ number_format($product['cost_price'], 2) }}</p>

            <div class="form-group">
                <label>Selling Price</label>
                <input type="number" step="0.01" name="products[{{ $index }}][price]"
                       class="form-control"
                       value="{{ $product['suggested_price'] }}" required>
            </div>

            <div class="form-group">
                <label>Discount Price (Optional)</label>
                <input type="number" step="0.01" name="products[{{ $index }}][discount_price]"
                       class="form-control">
            </div>
        </div>
        @endforeach
        <button type="submit" class="btn btn-primary">Save All</button>
    </form>
</div>
<!-- container-scroller -->
    @include('admin.script')
  </body>
</html>