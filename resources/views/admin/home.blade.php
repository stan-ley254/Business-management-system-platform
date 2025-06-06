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
            <div class="container-md mt-2 ml-2">
          <div class="container-fluid ">
          <div class="card p-2 ">
          <div class="card-title">
             <h2 class="justify-content-center text-success"> Welcome {{ Auth::user()->name }}</h2>
            </div>
          <div class="card-body">
           <p>Click on the 3-lined button to access the full sidebar or hide it</p>

          </div>
        <!-- main-panel ends -->
      </div>
          </div>
          <div class="container-fluid mt-4">

          <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title text-success">Today's Sales</h5>
                    <h3>₵0.00</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title text-success">Total Products</h5>
                    @if(isset($totalProducts) && $totalProducts)
                    <h3>{{$totalProducts}}</h3>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title text-success">Total Customers</h5>
                    @if(isset($totalCustomers) && $totalCustomers)
                    <h3>
                       {{ $totalCustomers}}
                    </h3>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title text-success">Total Sales</h5>
                   <div class="mt-4">
                    <h4><span id="period-total"></span></h4>
                </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="card-title text-success">Sales Overview</h5>
            <canvas id="salesChart"></canvas>
        </div>
    </div>
    </div>
    @include('admin.script')


          </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
   
  </body>
</html>