@include('nav')
<div class="custom_card">
<div class="container my-5">
  <div class="custom-container ">
    <div class="row">
      <!-- Left side quick links -->
      <div class="col-lg-3 col-md-12 mb-4">
        <div class="card card-custom quick-links-card">
          <div class="card-body">
            <h4 class="card-title">Quick Links</h4>
            <div class="quick-links-list">
              <a class="link" href="{{url('viewCustomer')}}"><i class="fas fa-users"></i> Customers</a>
              <a class="link" href="{{url('viewSupplier')}}"><i class="fas fa-truck"></i> Suppliers</a>
              <a class="link" href="{{url('viewProduct')}}"><i class="fas fa-box"></i> Products</a>
              <a class="link" href="{{url('viewSales')}}"><i class="fas fa-industry"></i> Sales</a>
              <a class="link" href="{{url('viewCart')}}"><i class="fas fa-shopping-bag"></i> New Sale</a>
              <a class="link" href="#"><i class="fas fa-chart-bar"></i> Analysis</a>
              
            </div>
          </div>
        </div>
      </div>

      <!-- Right side 9 cards -->
      <div class="col-lg-9 col-md-12">
        <div class="row">
          <div class="col-md-4 mb-4">
            <a  class="nav-link"href="{{ url('/viewCart') }}">
              <div class="card card-custom-2">
                <div class="card-body">
                <i class="fas fa-shopping-cart icon-large"></i>
                  <p class="card-text">New Sale</p>
                </div>
              </div>
            </a>
          </div>
          <div class="col-md-4 mb-4">
            <a class="nav-link" href="{{url('viewSales')}}">
              <div class="card card-custom-2">
                <div class="card-body">
                  <i class="fas fa-chart-line icon-large"></i> 
                  <p class="card-text">Sales</p>
                </div>
              </div>
            </a>
          </div>
          <div class="col-md-4 mb-4">
            <a class="nav-link" href="{{url('viewCustomer')}}">
              <div class="card card-custom">
                <div class="card-body">
                  <i class="fas fa-users icon-large"></i> 
                  <p class="card-text">Customers</p>
                </div>
              </div>
            </a>
          </div>
          <div class="col-md-4 mb-4">
            <a class="nav-link" href="{{url('viewSupplier')}}">
              <div class="card card-custom-2">
                <div class="card-body">
                  <i class="fas fa-truck icon-large"></i> 
                  <p class="card-text">Suppliers</p>
                </div>
              </div>
            </a>
          </div>
          <div class="col-md-4 mb-4">
            <a class="nav-link" href="{{url('viewProduct')}}">
              <div class="card card-custom-2">
                <div class="card-body">
                  <i class="fas fa-box icon-large"></i> 
                  <p class="card-text">Products</p>
                </div>
              </div>
            </a>
          </div>
          <div class="col-md-4 mb-4">
            <a class="nav-link" href="#">
              <div class="card card-custom">
                <div class="card-body">
                  <i class="fas fa-chart-pie icon-large"></i> 
                  <p class="card-text">Analysis</p>
                </div>
              </div>
            </a>
          </div>
          
</div>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>