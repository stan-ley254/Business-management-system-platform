<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Enterprise Management System</title>
    <!-- bootstrap core css -->
<link rel="stylesheet" type="text/css" href="{{asset('/css/bootstrap.min.css')}}"/>
      <!-- font awesome style -->

      <link href="{{asset('/font-awesome/css/all.min.css')}}" rel="stylesheet" />
      <!-- Custom styles for this template-->
      <link href="{{asset('/css/style.css')}}" rel="stylesheet" /> 
      <!-- responsive style -->
      <script src="{{asset('/js/bootstrap.bundle.min.js')}}"></script>  
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
    <!-- Brand -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
                 @guest
                 <ul class="navbar-nav ">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}"><i class="fas fa-sign-in-alt custom-icon"></i> {{ __('Login') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}"><i class="fas fa-user-plus custom-icon"></i> {{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                <ul class="navbar-nav">
                <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle custom" href="#" id="navbarDropdownMenuLinkHandling" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <span class="nav-label"><i class="fas fa-tachometer-alt"></i> Dashboard <span class="caret"></span></a>
                           <ul class="dropdown-menu">
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
                     </ul>
                     @endguest
  </div>
    </nav>

   
