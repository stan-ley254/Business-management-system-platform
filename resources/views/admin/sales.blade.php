<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    @include('admin.css')
    <style>
      .strikethrough {
            text-decoration: line-through;
            color: red;
        }
        body{
          margin:0px;
          border:0px;
        }
        .scroll-container {
            width: auto;
            height: 100vw;
            overflow: auto;
            cursor: grab;
            user-select: none; /* Disable text selection */
        }
        .scroll-container:active {
            cursor: grabbing;
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
            <div class="container-md mt-2">
<div class="card ">
    <div class="card-body">
        {{-- Top Controls --}}
        <div class="row g-3 mb-4">
            {{-- Search --}}
            <div class="col-12 col-md-4">
                <form method="POST" action="">
                    @csrf
                    <div class="input-group">
                        <input type="text" class="input-group" name="searchSales" placeholder="search sales..." value="{{isset($searchSales) ? $searchSales : ''}}">
                        <button type="submit" class="btn btn-success mt-1">Search</button>
                    </div>
                </form>
            </div>

            {{-- Date Filter --}}
            <div class="col-12 col-md-5">
                <form method="POST" action="{{ url('/filterSalesAdmin') }}">
                    @csrf
                    <div class="row g-2">
                        <div class="col-6">
                            <input type="date" class="input-group" name="from_date" required>
                        </div>
                        <div class="col-6">
                            <input type="date" class="input-group" name="to_date" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Back and Export --}}
            <div class="col-12 col-md-3 text-md-end">
                <div class="d-flex flex-column flex-md-row gap-2">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary w-100">Back</a>
                    <a href="{{ url('/exportSales') }}" class="btn btn-success w-100">Export Sales</a>
                </div>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Section Title --}}
        <h2 class="mb-3 text-success">All Business Sales</h2>

        {{-- Table Goes Here --}}
        <div class=" table-responsive">
               <table class="table table-bordered jsgrid jsgrid-table dataTables_wrapper table-primary ">
               <thead>
                            <tr>
                                <th>Sale ID</th>
                                <th>Product Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Active Price</th>
                                <th>Quantity</th>
                                <th>Date</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody id="sales-data">
          @php
    $currentCartId = null;
@endphp

@foreach($sales as $index => $sale)
    @if($currentCartId !== null && $currentCartId !== $sale->cart_id)
        {{-- Output total for the previous cart --}}
        <tr class="total-row" data-total="{{ $previousTotal }}">
            <th colspan="4">Total:</th>
            <td colspan="3">{{ $previousTotal }}</td>
        </tr>
    @endif

    {{-- New cart group starts --}}
    @if($currentCartId !== $sale->cart_id)
        @php
            $currentCartId = $sale->cart_id;
            $previousTotal = $sale->total;  // only update here!
        @endphp
    @endif

    <tr>
        <td>{{ $sale->cart_id }}</td>
        <td>{{ $sale->product_name }}</td>
        <td>{{ $sale->description }}</td>
        <td>
            @if($sale->active_price)
                <span class="strikethrough">{{ $sale->price }}</span>
            @else
                {{ $sale->price }}
            @endif
        </td>
        <td>{{ $sale->active_price ?? 'N/A' }}</td>
        <td>{{ $sale->quantity }}</td>
        <td>{{ $sale->updated_at }}</td>
        <td>
            <a onclick="return confirm('Are you sure you want to Delete this')" class="btn btn-danger" href="{{url('/deleteSale',$sale->id)}}">Delete</a>
        </td>
    </tr>
@endforeach

{{-- Output the last cart total --}}
@if($currentCartId !== null)
    <tr class="total-row" data-total="{{ $previousTotal }}">
        <th colspan="4">Total:</th>
        <td colspan="3">{{ $previousTotal }}</td>
    </tr>
@endif

  </tbody>
               </table>
               </div>
               <div class="mt-4">
                    <h4>Period Total: <span id="period-total"></span></h4>
                </div>
    </div>
</div>



</div>
    </div>
      </div>     
            </div>
          
    <!-- container-scroller -->
    @include('admin.script')
  </body>
</html>