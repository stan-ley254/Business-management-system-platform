<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    @include('admin.css')
    <style>
        .sidebar{
        position: fixed;
        }
        #importProgress {
    display: none;
    margin-top: 10px;
  }

  .progress-container {
    width: 100%;
    background-color: #f5f5f5;
    border-radius: 5px;
    height: 25px;
    overflow: hidden;
    margin-top: 10px;
  }

  .progress-bar-custom {
    height: 100%;
    width: 0;
    background-color: #4caf50;
    transition: width 0.4s ease;
    text-align: center;
    color: white;
    line-height: 25px;
  }

  #importMessage {
    font-weight: bold;
    color: #0d6efd;
    margin-top: 10px;
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
      @if(session()->has('message'))
<div class="alert alert-success alter-dismissible fade show">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close" aria-hidden="true">
        <span aria-hidden="true">&times;</span>
    </button>
    {{session()->get('message')}}
</div>
            @endif
   <div class="container">
  

    <form id="importForm" action="{{ url('/importProducts') }}" class="forms-sample mb-2" method="POST" enctype="multipart/form-data">
  @csrf
  <div class="input-group p-2">
    <label for="file">Choose CSV File</label>
    <input type="file" name="file" id="file" class="form-control text-white" required>
 
  <button type="submit" id="importButton" class="btn btn-primary">Import products</button>
</div>
</form>

  <div id="importProgress">
    <div id="importMessage">Importing products, please wait...</div>
    <div class="progress-container">
      <div class="progress-bar-custom" id="progressBar">0%</div>
    </div>
  </div>
   <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                 
                    <h4 class="card-title text-success mt-2">Add a Product</h4>

                    <form action="{{url('/add_product')}}" method="post" enctype="multipart/form-data">
                      @csrf
                      <div class="form-group">
                        <label for="product_name" class="text-success">Product Name</label>
                        <input type="text" class="input-group" name="product_name" id="exampleInputName1" placeholder="Product Name" required />
                      </div>
                      <div class="form-group">
                        <label for="description" class="text-success">Description</label>
                        <input type="text" class="input-group" name="description" id="exampleInputEmail3" placeholder="description" required />
                      </div>
                      <div class="form-group">
                        <label for="price" class="text-success">Price</label>
                        <input type="number" class="input-group" min="0" name="price" id="exampleInputPassword4" placeholder="Price" required />
                      </div>
                      <div class="form-group">
                        <label for="discount_price" class="text-success">Discount Price</label>
                        <input type="number" class="input-group" min="0" id="exampleInputPassword4" placeholder="Discount_price">
                      </div>
                      <div class="form-group">
                        <label for="quantity" class="text-success">Quantity</label>
                        <input type="number" class="input-group" min="0" name="quantity" id="exampleInputPassword2" placeholder="Quantity">
                      </div>
                      <div class="form-group">
                        <label for="barcode" class="text-success">Barcode</label>
                        <input type="number" class="input-group" min="0" name="barcode" id="exampleInputPassword4" placeholder="Barcode">
                      </div>
                      <div class="form-group">
                        <label for="category" class="text-success">Category</label>
                        <select class="input-group " name="category" id="exampleSelectGender">
                        @if(isset($category) && count($category)>0)
    @foreach($category as $category)
    <option>{{$category->category}}</option>
    @endforeach
    @endif
                        </select>
                      </div>
                     
                      <button type="submit" class="btn btn-primary me-2">Submit</button>
  
                    </form>
               <!--     <button class="btn-secondary  mt-2"><a class="nav-link" href="{{url('/show_product')}}">Show Products</a></button> -->
                  </div>
                </div>
              </div>
             
    <!-- container-scroller -->
    @include('admin.script')
  </body>
</html>