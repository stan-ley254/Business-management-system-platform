@include('navstore')

<div class="container-xxl">
    @if(isset($sales) && ($sales))
       
        <div class="custom-header">
     Manage All Sales
    </div>
    @if(session('success'))
            <div class="message rounded">
                <div id="success" class="alert alert-success">
                    {{ session('success') }}
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger" id="error">
                {{ session('error') }}
            </div>
        @endif
        <div class="row">
            <!-- Main content: table and form -->
            <div class="col-lg-9 col-md-12">
                <div class="custom-form-container">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>SaleID</th>
                                    <th>Product Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>ActivePrice</th>
                                    <th>Quantity</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody id="sales-data">
                                @php $currentCartId = null; @endphp
                                @foreach($sales as $sale)
                                    @if($currentCartId !== null && $currentCartId !== $sale->cart_id)
                                        <tr class="total-row" data-total="{{ $previousTotal }}">
                                            <th colspan="4">Total:</th>
                                            <td colspan="3">{{ $previousTotal }}</td>
                                        </tr>
                                    @endif
                                    @if($currentCartId !== $sale->cart_id)
                                        @php
                                            $currentCartId = $sale->cart_id;
                                            $previousTotal = $sale->total;
                                        @endphp
                                    @endif
                                    <tr>
                                        <td>{{ $sale->cart_id }}</td>
                                        <td>{{ $sale->product_name }}</td>
                                        <td>{{ $sale->description }}</td>
                                        <td>
                                            @if($sale->active_price)
                                                <span class="strikethrough">{{ $sale->price }}</span>
                                            @else
                                                {{ $sale->price }}
                                            @endif
                                        </td>
                                        <td>{{ $sale->active_price ?? 'N/A' }}</td>
                                        <td>{{ $sale->quantity }}</td>
                                        <td>{{ $sale->updated_at }}</td>
                                    </tr>
                                @endforeach
                                @if($currentCartId !== null)
                                    <tr class="total-row" data-total="{{ $previousTotal }}">
                                        <th colspan="4">Total:</th>
                                        <td colspan="3">{{ $previousTotal }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2">
                        <h4>Period Total: <span id="period-total"></span></h4>
                    </div>
                </div>
            </div>

            <!-- Sidebar Section -->
            <div class="col-lg-3 col-md-12">
                <div class="custom-section">
                    <form action="{{ url('/filterSales') }}" method="POST" class="form-inline mt-2">
                        @csrf
                        <div class="form-group">
                            <label for="from_date">From Date:</label>
                            <input type="date" class="form-control" id="from_date" name="from_date" required>
                        </div>
                        <div class="form-group">
                            <label for="to_date">To Date:</label>
                            <input type="date" class="form-control" id="to_date" name="to_date" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-custom mt-2"><i class="fas fa-filter"></i> Filter Sales</button>
                        </div>
                    </form>
                </div>

                <div class="custom-section">
                    <form method="get" action="{{ url('/searchSalesCart') }}">
                        @csrf
                        <div class="form-group">
                            <input type="text" class="form-control" name="searchSalesCart" placeholder="search sales..by product name" value="{{ isset($searchSalesCart) ? $searchSalesCart : '' }}">
                            <button type="submit" class="btn btn-custom mt-2"><i class="fas fa-search"></i> Search</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>z

<script src="{{asset('/js/scriptsfiles.js')}}" ></script>
</body>
</html>
