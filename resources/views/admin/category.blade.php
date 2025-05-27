<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    @include('admin.css')
    <style>
      
    </style>
  </head>
  <body>

      <!-- partial:partials/_sidebar.html -->
      @include('admin.sidebar')
      <!-- partial -->
      @include('admin.header')
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
      <div class="container-md">
            <div class="text">
               <h2>Add Category</h2>
</div>
               <div class="card">
               <div class="card-body">
               <form action="{{url('/add_category')}}" method="post" class="form-group">
                @csrf
                <input class="input-group mb-2" type="text" name="category" placeholder="Write category name" required />
               <input type="submit" class="btn btn-primary " name="submit" value="Add Category">
               </form>
               </div>
               </div>
               <div class="table-responsive mt-2">
               <table class="table table-warning">
<thead class="dataTable thead">
            <tr>
                    <th>Category Name</th>
                    <th>Action</th>

            </tr>
            </thead>
            @if(isset($data) && count($data)>0)
                @foreach($data as $data)
<tbody>
     <tr>
                <td>{{$data->category}}</td>
               <td>
                 <a onclick="return confirm('Are you sure you want to Delete this')" class="btn btn-danger" href="{{url('delete_category',$data->id)}}">Delete</a>
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
  </body>
</html>