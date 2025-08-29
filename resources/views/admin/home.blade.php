<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    @include('admin.css')
    <style>
  
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
                     @if(isset($todaySalesTotal) && $todaySalesTotal)
                    <h3>Ksh {{ number_format($todaySalesTotal, 2) }}</h3>
@endif
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
                    @if(isset($totalSales) && $totalSales)
                   <h3>Ksh {{ number_format($totalSales, 2) }}</h3>
                   @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-2">
        <div class="card-body ">
         
            <h5 class="card-title text-success">Sales Overview</h5>
              @if(isset($labels) && $labels)
            @if(isset($totals) && $totals)
            <canvas id="salesChart"></canvas>
            @endif
            @endif
        </div>
    </div>
    </div>
    @include('admin.script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: '₵ Sales',
                data: {!! json_encode($totals) !!},
                backgroundColor: 'rgba(40, 167, 69, 0.2)',
                borderColor: 'rgba(40, 167, 69, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₵' + value;
                        }
                    }
                }
            }
        }
    });
</script>


          </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
   
  </body>
</html>