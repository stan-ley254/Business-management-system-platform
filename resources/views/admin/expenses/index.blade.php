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
        body{
          margin:0px;
          border:0px;
        }
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
            <div class="container-md mt-2">
<div class="card ">
    <div class="card-body">
        {{-- Top Controls --}}
       <div class="row g-3 mb-4">

    {{-- Expanded Date Filter (now full width on desktop) --}}
    <div class="col-12 col-md-9">
        
    </div>

    {{-- Back Button Only --}}
    <div class="col-12 col-md-3 text-md-end">
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary w-100">Back</a>
    </div>

</div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Section Title --}}
        <h2 class="mb-3 text-success">All Expenses</h2>

        {{-- Table Goes Here --}}
        <div class=" table-responsive">
               <table class="table table-bordered jsgrid jsgrid-table dataTables_wrapper table-primary ">
               <thead>
     <tr>
            <th>Name</th>
           <th>Amount</th>
           <th>Date</th>
           <th>Actions</th>
      </tr>
                        </thead>
                         @if(isset($expenses) && count($expenses)>0)
                 @foreach($expenses as $expense)
<tbody>
   <tr>
            <td>{{ $expense->name }}</td>
            <td>{{ $expense->amount }}</td>
            <td>{{ $expense->date }}</td>
            <td>
                <a href="{{ route('expenses.edit', $expense) }}">Edit</a>
                <form action="{{ route('expenses.destroy', $expense) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            </td>
  @endforeach
  @endif
  </tbody>
               </table>
               </div>
            
    </div>
</div>



</div>
    </div>
      </div>     
            </div>
          
    <!-- container-scroller -->
    @include('admin.script')
  </body>
</html>