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
    </style>
  </head>
  <body>
  <div class="container-scroller">
      <!-- partial:partials/_sidebar.html -->
      @include('admin.sidebar')
      <!-- partial -->
      @include('admin.header')
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="container-md mt-2">
  <div class="card ">
    <form  class="form-group"method="get" action="{{url('/searchCustomeradmin')}}">
      <div class="card-body">
<input class="input-group mb-2 " name="searchCustomeradmin" placeholder="search staff by ..name...or email address" value="{{isset($searchCustomeradmin) ? $searchCustomeradmin : ''}}">
<button type="submit" class="btn btn-success">Search</button>

      
    </form>
    <div class="card">
        <div class="card-title"> <h2>Users</h2></div>
 
<button><a href="{{ route('admin.users.create') }}" class="btn-secondary nav-link">Add User</a></button>
</div>
    <div class=" mt-4">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
</div>
</div>
</div>
<div>
<div class=" table-responsive mt-2">
               <table class="table table-bordered jsgrid jsgrid-table dataTables_wrapper table-primary ">
<thead>
<tr>
                <th>Employee Name</th>
                <th>Employee Email</th>
                    <th>Action</th>
                <!-- Add more table headers as needed -->
            </tr>
        <tbody>
            @if(isset($users) && ($users))
            @foreach ($users as $user)
            <tr>
            <td>{{ $user->name }} </td>
            <td>{{ $user->email }}</td>
        
                
                
                <!-- Add more table cells for other attributes -->
                <td>
                <form method="GET" action="{{ route('admin.users.destroy', $user) }}">

<button type="submit">Delete</button>
</form>
                    <!-- Add delete button with form submission if needed -->
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


          
    <!-- container-scroller -->
    @include('admin.script')

    </div>
  </body>
</html>
