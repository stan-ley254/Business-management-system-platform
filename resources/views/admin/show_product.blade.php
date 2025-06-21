<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    @include('admin.css')
    <style>
       .scroll-container {
            width: auto;
            height: 100vw;
            overflow: auto;
            cursor: grab;
            user-select: none; /* Disable text selection */
        }
        .scroll-container:active {
            cursor: grabbing;
        }
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

<form action="{{ url('/clearAllproducts') }}" method="POST" class="mb-1">
    @csrf
    <button id="clearAll" onclick="return confirm('Are you sure you want to Delete all PRODUCT?!')"  class="button rounded-pill btn-danger mt-1">Clearproducts</button>
    </form>
  <div class="card mb-2">
    <form  class="form-group"method="get" action="{{url('/search')}}">
    @csrf
      <div class="card-body">
<input class="input-group mb-2 " name="search" placeholder="search product..." value="{{isset($search) ? $search : ''}}">
<button type="submit" class="btn btn-success">Search</button>

      </div>
    </form>
   

</div>
<div class=" table-responsive">
               <table class="table table-bordered jsgrid jsgrid-table dataTables_wrapper table-primary ">
<thead>
            <tr class="column">
                    <th> Name</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Cost Price</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Delete</th>
                    <th>Edit</th>

            </tr>
            </thead>
            @if(isset($show) && count($show)>0)
                @foreach($show as $show)
                <tbody>

     <tr>
                <td>{{$show->product_name}}</td>
                <td>{{$show->description}}</td>
                <td>{{$show->quantity}}</td>
                 <td>{{$show->cost_price}}</td>
                <td>{{$show->price}}</td>
                <td>{{$show->category}}</td>
               <td>
                 <a onclick="return confirm('Are you sure you want to Delete this')" class="btn btn-danger" href="{{url('/delete_product',$show->id)}}">Delete</a>
                </td>
                <td>
                    <a class="btn btn-success" href="{{url('/edit_product',$show->id)}}">Edit</a>
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


          
    <!-- container-scroller -->
    @include('admin.script')

    </div>
  </body>
</html>