<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
        <!-- plugins:css -->
    <link rel="stylesheet" href="/admin/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="/admin/assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="/admin/assets/vendors/jvectormap/jquery-jvectormap.css">
    <link rel="stylesheet" href="/admin/assets/vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="/admin/assets/vendors/owl-carousel-2/owl.carousel.min.css">
    <link rel="stylesheet" href="/admin/assets/vendors/owl-carousel-2/owl.theme.default.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="admin/assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="admin/assets/images/favicon.png" />
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
            @if(session()->has('message'))
<div class="alert alert-success alter-dismissible fade show">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close" aria-hidden="true">
        <span aria-hidden="true">&times;</span>
    </button>
    {{session()->get('message')}}
</div>
            @endif
<div class="container-fluid">
          <div class="text ">
                <h2>Edit Product</h2>
            </div>
            <div>
    <a href="{{url('/show_product')}}"><Button class="btn btn-secondary">Show Products</Button></a>
   </div>
  
    
   <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">

                  <form action="{{url('/update_product',$product->id) }}" method="post" enctype="multipart/form-data">
                  @csrf
                      <div class="form-group">
                        <label for="product_name">Product Name</label>
                        <input type="text" class="input-group" name="product_name" id="exampleInputName1" placeholder="Product Name" value="{{ $product->product_name }}" required="">
                      </div>
                      <div class="form-group">
                        <label for="description">Description</label>
                        <input type="text" class="input-group" name="description" id="exampleInputEmail3" placeholder="description" value="{{ $product->description }}" required="">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputPassword4">Price</label>
                        <input type="number" class="input-group" min="0" name="price" id="exampleInputPassword4" placeholder="Price" value="{{ $product->price }}" required="">
                      </div>
                      
                      <div class="form-group">
                        <label for="discount_price">Discount Price</label>
                        <input type="number" class="input-group" min="0" id="exampleInputPassword4" placeholder="Discount_price" value="{{ $product->discount_price }}">
                      </div>
                      <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" class="input-group" min="0" name="quantity" id="exampleInputPassword4" value="{{ $product->quantity }}" placeholder="Quantity">
                      </div>
                      <div class="form-group">
                        <label for="exampleSelectGender">Category</label>
                        <select class="input-group " name="category" id="exampleSelectGender">
                        @if(isset($category) && count($category)>0)
    @foreach($category as $category)
    <option>{{$category->category}}</option>
    @endforeach
    @endif
                        </select>
                      </div>
                     
                      <button type="submit" class="btn btn-primary me-2">Update Product</button>
                      
                    </form>
                    
                  </div>
                </div>
              </div>
</div>
          </div>
    <!-- container-scroller -->
    @include('admin.script')
  </body>
</html>