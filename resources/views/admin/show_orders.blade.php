
<!DOCTYPE html>
<html lang="en">
  <head>
   <title>Mifugo Bora</title>
    <!-- Required meta tags -->
    @include('admin.css')
    <style>
         .center{
            margin:auto;
            width:60%;
         
            padding:30px;
        }
        table,th,td{
            margin:auto;
            padding:5px;
            width:40%;
            display:;
            margin-top:70px;
      
            border:1px solid white;
            border-collapse:collapse;
        }
        .th-deg{
           font-size:30px;
           padding:5px;
           background:skyblue;
        }
        .img-deg{
            width:100px;
            height:100px;
        }
        .total-deg{
            font-size:20px;
            padding:40px;
            font:bold;
        }
        h1-deg{
            text-align:center;

        }
        .title-deg{
         text-align:center;
         font-size:25px;
         font-weight:bold;
        }
      </style>
  </head>
  
  <body>
  <div class="container-scroller">
      <!-- partial:partials/_sidebar.html -->
      @include('admin.sidebar')
      <!-- partial --><h1 class="title-deg">All Orders</h1>
      @include('admin.header')
        <!-- partial -->
      <!-- end why section -->
      
    <div class="">
        <table>
            <tr>
            <th class="th-deg">Name</th>
            <th class="th-deg">Email</th>
            <th class="th-deg">Product_name</th>
            <th class="th-deg">price</th>
            <th class="th-deg">quantity</th>
            <th class="th-deg">image</th>
            <th class="th-deg">product_id</th>
            </tr>
            @foreach($orders as $orders)
          <tr>
            <td>{{$orders->name}}</td>
            <td>{{$orders->email}}</td>
            <td>{{$orders->product_name}}</td>
            <td>{{$orders->price}}</td>
            <td>{{$orders->quantity}}</td>
            <td >{{$orders->image}}</td>
            <td>{{$orders->product_id}}</td>
           
           
           
           
           
          </tr>
          @endforeach
        </table>
    </div>
      </div>
    <!-- container-scroller -->
    @include('admin.script')
  </body>
</html>