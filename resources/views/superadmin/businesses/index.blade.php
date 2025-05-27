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
     
      @include('superadmin.businesses.sidebarsuper')
      <!-- partial -->
      @include('admin.header')
        <div class="main-panel">
       
          <div class="content-wrapper">
          <div class="container-fluid mt-2">
          <div class="card ml-4 ">
          <div class="card-title text-success p-4">
             <h2 class="justify-content-center"> Welcome {{ Auth::user()->name }}</h2>
            </div>
       
        <!-- main-panel ends -->
      </div>
          </div>
          <div class="container-fluid mt-4">

          <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                <h5 class="text-success">Total Businesses</h5>
                @if(isset($totalBusinesses) && $totalBusinesses)
                <h2>{{ $totalBusinesses }}</h2>
                @endif
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                <h5 class="text-success">Total Users</h5>
                @if(isset($totalUsers) && $totalUsers)
                <h2>{{ $totalUsers }}</h2>
                @endif
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                <h5 class="text-success">Active Businesses</h5>
                @if(isset($activeBusinesses) && $activeBusinesses)
                <h2>{{ $activeBusinesses }}</h2>
                @endif
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title text-muted">Total Sales</h5>
                    <h3>₵0.00</h3>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="container-fluid">
    <div class="card shadow-sm p-4 mb-2 ">
    <div class="card-header bg-success text-white card-title">
        Businesses Overview
    </div>
                
                <h1 class="text-xl font-bold mb-2">Businesses</h1>
    
    <div class="table-responsive ">
    <div class="message d-print-inline-flex rounded">
    @if(session('success'))
        <div class="alert alert-success" id="success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
</div>
    <table class="table table-info bordered ">
        <thead>
            <tr> 
                <th>Name</th>
                <th>Created</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        
        <tbody>
        @if(isset($businesses) && $businesses)
        @foreach($businesses as $business)
            <tr>
                <td>{{ $business->name }}</td>
                <td>{{ $business->created_at->diffForHumans() }}</td>
                <td>
                @if($business->is_active)
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-danger">Inactive</span>
                @endif
            </td>
          
                <td>
             <div class="mt-2">
             <form method="POST" action="{{ url('/toggleBusiness', $business->id) }}">
                    @csrf
                    @method('PATCH')
                    @if($business->is_active)
                        <button type="submit" class="btn btn-sm btn-danger">Deactivate</button>
                    @else
                        <button type="submit" class="btn btn-sm btn-success">Activate</button>
                    @endif
                </form>
             </div>
                   <div class="mt-2 mb-2">
                   <a href="{{ route('superadmin.businesses.edit', $business) }}" class="btn btn-sm btn-secondary">Edit</a>
                   </div>
                    <form action="{{ route('superadmin.businesses.destroy', $business) }}" method="POST" class="inline">
                        @csrf 
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
                    </form>
                </td>
            </tr>
          
            @endforeach
            @endif
        </tbody>
        
    </table>
   
    
                </div>
            </div>
        </div>
    </div>
    </div>
    @include('admin.script')


        
      <!-- page-body-wrapper ends -->

    <!-- container-scroller -->
   
  </body>
</html>