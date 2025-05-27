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
            <div class="container-xl mt-1">
            <div class="card">
    <div class="card-body">
        {{-- Top Controls --}}
        <div class="row g-3 mb-4">
            {{-- Search --}}
            <div class="col-12 col-md-4">
                <form method="GET" action="{{ route('admin.import.logs') }}">
                    <div class="input-group">
                        <input type="text" class="input-group" name="searchSales" placeholder="Search by product..." value="{{ $searchProductName ?? '' }}">
                        <button type="submit" class="btn btn-success mt-1">Search</button>
                    </div>
                </form>
            </div>

            {{-- Date Filter --}}
            <div class="col-12 col-md-5">
                <form method="POST" action="{{ route('admin.import.logs') }}">
                    @csrf
                    <div class="row g-2">
                        <div class="col-6">
                            <input type="date" class="input-group" name="from_date" required>
                        </div>
                        <div class="col-6">
                            <input type="date" class="input-group" name="to_date" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Back and Export --}}
            <div class="col-12 col-md-3 text-md-end">
                <div class="d-flex flex-column flex-md-row gap-2">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary w-100">Back</a>
                    <a href="{{ route('admin.import.logs.export') }}" class="btn btn-success w-100">Export CSV</a>
                </div>
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
        <h2 class="mb-3 text-success">Product Import Logs</h2>

        {{-- Table Goes Here --}}
        {{-- ... --}}
    </div>
</div>


<div class=" table-responsive">
@if($logs->count())
               <table class="table table-bordered jsgrid jsgrid-table dataTables_wrapper table-primary ">
               <thead>
               <tr>
                <th>Date</th>
                <th>Product Name</th>
                <th>Quantity Added</th>
                <th>Imported By</th>
            </tr>
                        </thead>
                        <tbody id="sales-data">
                        @foreach($logs as $log)
            <tr>
                <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ $log->product_name }}</td>
                <td>{{ $log->quantity_added }}</td>
                <td>{{ $log->imported_by }}</td>
            </tr>
            @endforeach

  </tbody>
               </table>
               {{ $logs->links() }}
    @else
        <p>No import logs available.</p>
    @endif
               </div>           
</div>
      </div>       
    <!-- container-scroller -->
    @include('admin.script')

    </div>
  </body>
</html>