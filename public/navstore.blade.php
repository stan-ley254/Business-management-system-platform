<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Enterprise Management System</title>
    <!-- bootstrap core css -->
<link rel="stylesheet" type="text/css" href="{{asset('/css/bootstrap.min.css')}}" />
      <!-- font awesome style -->
    
      <link href="{{asset('/font-awesome/css/all.min.css')}}" rel="stylesheet" />
      <!-- Custom styles for this template-->
      <link href="{{asset('/css/style.css')}}" rel="stylesheet" /> 

        <!-- responsive style -->
        <script src="{{asset('/js/bootstrap.bundle.min.js')}}"></script>
        <style>
        .strikethrough {
            text-decoration: line-through;
            color: red;
        }</style>
    </head>
    <body>
 <!-- Navbar -->

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <!-- Brand -->
    

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <ul class="navbar-nav">
                <li class="nav-item dropdown ">
                <a class="nav-link dropdown-toggle custom" href="#" id="navbarDropdownMenuLinkHandling" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-tachometer-alt"></i> Dashboard <span class="caret"></span></a>
                           <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLinkHandling">
                              <li class="nav-item font-medium text-base">{{ Auth::user()->name }}</li>
                              <li class="nav-item"><a class="dropdown-item" href="{{ route('profile.edit') }}">
                                {{ __('Profile') }}
                            </a></li>
                              <li class="nav-item"> <a class="nav-link" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                {{ __('Log Out') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
  
                           </li>
                            
                           </ul>
                           </li>
                           </ul>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
      <ul class="navbar-nav">
      <li class="nav-item dropdown custom-nav-item">
  <a class="nav-link dropdown-toggle custom" href="#" id="navbarDropdownMenuLinkHandling" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="fas fa-users custom-icon"></i> Customers
  </a>
  <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLinkHandling">
    <li><a class="dropdown-item" href="{{url('viewCustomer')}}"><i class="fas fa-edit"></i> Manage Customer</a></li>
    <li><a class="dropdown-item" href="{{url('createCustomer')}}"><i class="fas fa-plus"></i> Create New Customer</a></li>
  </ul>
</li>

<!-- Product Dropdown -->
<li class="nav-item dropdown custom-nav-item">
  <a class="nav-link dropdown-toggle custom" href="#" id="navbarDropdownMenuLinkProduct" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="fas fa-truck custom-icon"></i> Suppliers
  </a>
  <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLinkProduct">
    <li><a class="dropdown-item" href="{{url('viewSupplier')}}"><i class="fas fa-edit"></i> Manage Supplier</a></li>
    <li><a class="dropdown-item" href="{{url('createSupplier')}}"><i class="fas fa-plus"></i> Create New Supplier</a></li>
  </ul>
</li>

<!-- Branch Dropdown -->
<li class="nav-item dropdown custom-nav-item">
  <a class="nav-link dropdown-toggle custom" href="#" id="navbarDropdownMenuLinkBranch" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="fas fa-box custom-icon"></i> Products
  </a>
  <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLinkBranch">
    <li><a class="dropdown-item" href="{{url('viewProduct')}}"><i class="fas fa-box"></i> Manage Products</a></li>
    <li><a class="dropdown-item" href="{{url('stockReports')}}"><i class="fas fa-chart-bar"></i> Stock Reports</a></li>
  </ul>
</li>

<!-- Purchasing Dropdown -->
<li class="nav-item dropdown custom-nav-item">
  <a class="nav-link dropdown-toggle custom" href="#" id="navbarDropdownMenuLinkPurchasing" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="fas fa-dollar-sign custom-icon"></i> Sales
  </a>
  <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLinkPurchasing">
    <li><a class="dropdown-item" href="{{url('viewSales')}}"><i class="fas fa-edit"></i> Manage Sales</a></li>
  </ul>
</li>

<!-- Selling Dropdown -->
<li class="nav-item dropdown custom-nav-item">
  <a class="nav-link dropdown-toggle custom" href="#" id="navbarDropdownMenuLinkSelling" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="fas fa-shopping-cart custom-icon"></i> NewSale
  </a>
  <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLinkSelling">
    <li><a class="dropdown-item" href="{{ url('/viewCart') }}"><i class="fas fa-shopping-bag"></i> Make a Sale</a></li>
    <li><a class="dropdown-item" href="{{url('getHeldCarts')}}"><i class="fas fa-pause"></i> Held Sales</a></li>
    <li><a class="dropdown-item" href="{{url('viewDebts')}}"><i class="fas fa-hand-holding-usd"></i> Debts</a></li>
  </ul>
</li>

<!-- Reports Dropdown -->
<li class="nav-item dropdown custom-nav-item">
  <a class="nav-link dropdown-toggle custom" href="#" id="navbarDropdownMenuLinkReports" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="fas fa-chart-pie custom-icon"></i> Analysis
  </a>
  <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLinkReports">
    <li><a class="dropdown-item" href=""><i class="fas fa-boxes"></i> Tracking Inventory</a></li>
    <li><a class="dropdown-item" href="#"><i class="fas fa-chart-line"></i> Predictions</a></li>
    <li><a class="dropdown-item" href="#"><i class="fas fa-search"></i> Clustering</a></li>
  </ul>
</li>

      </ul>
    </div>
  </div>
</nav>
