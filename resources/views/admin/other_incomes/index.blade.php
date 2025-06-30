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
        <h2 class="mb-3 text-success">All Other Incomes</h2>

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
                         @if(isset($otherIncomes) && count($otherIncomes)>0)
                 @foreach($otherIncomes as $otherIncomes)
<tbody>
   <tr>
            <td>{{ $otherIncomes->name }}</td>
            <td>{{ $otherIncomes->amount }}</td>
            <td>{{ $otherIncomes->date }}</td>
            <td>
                <a href="{{ route('other-incomes.edit', $otherIncomes) }}">Edit</a>
                <form action="{{ route('other-incomes.destroy', $otherIncomes) }}" method="POST">
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