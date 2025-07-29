<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Service Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- If using Vite --}}
      <link rel="stylesheet" type="text/css" href="{{asset('/css/bootstrap.min.css')}}" />
      <!-- font awesome style -->
    
      <link href="{{asset('/font-awesome/css/all.min.css')}}" rel="stylesheet" />
</head>
<body>
    <div class="d-flex" id="wrapper">
        @include('serviceadmin.sidebar')
        <div id="page-content-wrapper" class="w-100">
            @include('serviceadmin.topbar')

            <main class="container-fluid mt-4">
                @yield('content')
            </main>

          
        </div>
    </div>

    {{-- Bootstrap & Sidebar toggle --}}
  
<script src="{{asset('/js/bootstrap.bundle.min.js')}}"></script>
    <script>
        document.getElementById('menu-toggle').addEventListener('click', () => {
            document.getElementById('wrapper').classList.toggle('toggled');
        });
    </script>
</body>
</html>
