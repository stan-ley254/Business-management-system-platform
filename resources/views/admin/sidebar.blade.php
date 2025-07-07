<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
       <!-- Updated Icon Classes -->
<li class="nav-item menu-items">
    <a class="nav-link" href="{{url('/homeAdmin')}}">
      <span class="menu-icon">
        <i class="mdi mdi-home-analytics"></i> <!-- Better home/dashboard icon -->
      </span>
      <span class="menu-title">Home</span>
    </a>
</li>

<li class="nav-item menu-items">
    <a class="nav-link" data-bs-toggle="collapse" href="#products-menu" aria-expanded="false" aria-controls="products-menu">
      <span class="menu-icon">
        <i class="mdi mdi-package-variant-closed"></i> <!-- More accurate product icon -->
      </span>
      <span class="menu-title">Products</span>
      <i class="menu-arrow"></i>
    </a>
     <div class="collapse" id="products-menu">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{url('/view_product')}}">Add Product</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{url('/show_product')}}">Show Product</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{route('admin.import.logs')}}">Products Import Logs</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{url('stockReports_admin')}}">Stock Reports</a></li>
              </ul>
            </div>
</li>

<li class="nav-item menu-items">
    <a class="nav-link" href="{{url('view_category')}}">
      <span class="menu-icon">
        <i class="mdi mdi-tag-multiple"></i> <!-- Better for category -->
      </span>
      <span class="menu-title">Category</span>
    </a>
</li>

<li class="nav-item menu-items">
    <a class="nav-link" href="{{ url('/business/settings/edit_mpesa') }}">
      <span class="menu-icon">
        <i class="mdi mdi-cog-outline"></i> <!-- More intuitive settings -->
      </span>
      <span class="menu-title">Settings</span>
    </a>
</li>

<li class="nav-item menu-items">
    <a class="nav-link" data-bs-toggle="collapse" href="#users-menu" aria-expanded="false" aria-controls="users-menu">
      <span class="menu-icon">
        <i class="mdi mdi-account-multiple-outline"></i> <!-- Better fit for users -->
      </span>
      <span class="menu-title">Users</span>
      <i class="menu-arrow"></i>
    </a>
      <div class="collapse" id="users-menu">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{ route('admin.users.create') }}">Add User</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('admin.users.index') }}">Show Users</a></li>
              </ul>
            </div>
</li>

<li class="nav-item menu-items">
    <a class="nav-link" href="{{url('viewCustomeradmin')}}">
      <span class="menu-icon">
        <i class="mdi mdi-account-box-outline"></i> <!-- Customer specific -->
      </span>
      <span class="menu-title">Customers</span>
    </a>
</li>

<li class="nav-item menu-items">
    <a class="nav-link" href="{{url('view_sales')}}">
      <span class="menu-icon">
        <i class="mdi mdi-cart-outline"></i> <!-- Sales as cart -->
      </span>
      <span class="menu-title">Sales</span>
    </a>
</li>

<li class="nav-item menu-items">
    <a class="nav-link" data-bs-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
      <span class="menu-icon">
        <i class="mdi mdi-finance"></i> <!-- Financial reporting icon -->
      </span>
      <span class="menu-title">IncomeStatement</span>
      <i class="menu-arrow"></i>
    </a>
     <div class="collapse" id="auth">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{route('admin.income-statement')}}"> Generate </a></li>
                <li class="nav-item"> <a class="nav-link" href="{{route('expenses.index')}}"> Expenses </a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('expenses.create') }}"> Add Expenses </a></li>
                <li class="nav-item"> <a class="nav-link" href="{{route('other-incomes.index')}}"> Other Incomes </a></li>
                <li class="nav-item"> <a class="nav-link" href="{{route('other-incomes.create')}}"> Add other incomes </a></li>
              </ul>
            </div>
</li>

<li class="nav-item menu-items">
    <a class="nav-link" href="{{url('documentation')}}">
      <span class="menu-icon">
        <i class="mdi mdi-book-open-page-variant"></i> <!-- Better for documentation -->
      </span>
      <span class="menu-title">Documentation</span>
    </a>
</li>
      <!-- <li class="nav-item menu-items">
            <a class="nav-link" href="pages/icons/mdi.html">
              <span class="menu-icon">
                <i class="mdi mdi-contacts"></i>
              </span>
              <span class="menu-title">Icons</span>
            </a>
          </li>
          <li class="nav-item menu-items">
            <a class="nav-link" data-bs-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
              <span class="menu-icon">
                <i class="mdi mdi-security"></i>
              </span>
              <span class="menu-title">User Pages</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="auth">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="pages/samples/blank-page.html"> Blank Page </a></li>
                <li class="nav-item"> <a class="nav-link" href="pages/samples/error-404.html"> 404 </a></li>
                <li class="nav-item"> <a class="nav-link" href="pages/samples/error-500.html"> 500 </a></li>
                <li class="nav-item"> <a class="nav-link" href="pages/samples/login.html"> Login </a></li>
                <li class="nav-item"> <a class="nav-link" href="pages/samples/register.html"> Register </a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item menu-items">
            <a class="nav-link" href="http://www.bootstrapdash.com/demo/corona-free/jquery/documentation/documentation.html">
              <span class="menu-icon">
                <i class="mdi mdi-file-document-box"></i>
              </span>
              <span class="menu-title">Documentation</span>
            </a>
          </li>-->
        </ul>
      </nav>