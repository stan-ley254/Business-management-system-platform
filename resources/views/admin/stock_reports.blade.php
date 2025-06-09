<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    @include('admin.css')
    <style>
      .sidebar{
        position: fixed;
      }
      .custom-container {
          padding: 20px;
          background-color: #f8f9fa;
          margin-top:20px;
          margin-bottom:10px;
          border-radius: 8px;
          box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
      }
    </style>
  </head>
  <body>
    
 
      <!-- partial:partials/_sidebar.html -->
     
      @include('admin.sidebar')
      <!-- partial -->
      @include('admin.header')
        <div class="main-panel">
       
          <div class="content-wrapper">
            <div class="container-xxl my-5">
    <!-- Header -->
    <div class="text-success suble-title">
        Products Stock Reports
    </div>
    @if($products->count())
        <!-- Table -->
                         
 <div class="container-md mt-2">


        
        {{-- Table Goes Here --}}
        <div class=" table-responsive">
               <table class="table table-bordered jsgrid jsgrid-table dataTables_wrapper table-primary ">
               <thead>
                            <tr>
                               <th>Product Name</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Date</th>
                            </tr>
                        </thead>
                        <tbody">
                           @foreach($products as $product)
                    <tr>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ $product->description }}</td>
                        <td>{{ $product->quantity }}</td>
                        <td>
                            @if($product->quantity == 0)
                                <span class="text-danger">Out of Stock</span>
                            @elseif($product->quantity <= 5)
                                <span class="text-warning">Low Stock</span>
                            @else
                                <span class="text-success">In Stock</span>
                            @endif
                        </td>
                        <td>{{$product->updated_at}}</td>
                    </tr>
                @endforeach
                </tbody>
               </table>
                @else
            <p>No Stock Reports Yet</p>
            @endif
               </div>
            
    </div>
</div>



</div>
    </div>
          </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
   
  </body>
</html>
