@include('navstore')

<!-- Toggle button for sidebar (visible on smaller screens) -->
<button class="btn toggle-sidebar-btn d-lg-none " data-bs-toggle="collapse" data-bs-target="#sidebar">
    <i class="fas fa-bars"></i> select products here
</button>

<div class="container-fluid mt-2">
    <div class="row">
        <!-- Sidebar (collapsible for mobile screens) -->
        <nav id="sidebar" class="col-lg-3 collapse d-lg-block sidebar">
            <h3>Navigator</h3>
            <div class="custom-form-container custom-container-color">
                <!-- Updated Add to Cart Form -->
                <form id="add-to-cart-form" class="">
                    @csrf
                    <div class="form-group">
                        <label for="products" class="">Products:</label>
                        <select class="form-control" name="product_id" id="product-select">
                            <option value="">Select a product</option>
                            @foreach($product as $item)
                                <option value="{{ $item->id }}" data-price="{{ $item->price }}" data-stock="{{ $item->quantity }}">
                                    {{ $item->product_name }} ({{ $item->category }})
                                </option>
                            @endforeach
                        </select>
                        <input type="number" name="quantity" id="quantity" value="1" min="1" class="form-control mt-2">
                    </div>
                </form>

                <!-- Total Amount Display -->
                <div class="mt-3">
                    <h4>Total: <span id="totalAmount">0.00</span></h4>
                </div>
            </div>
            <div class="custom-form-container custom-container-color-1 mt-1">
                <form id="payment-form">
                    @csrf
                    <div class="form-group">
                        <label for="cash_given">Cash Given:</label>
                        <input type="number" id="cash_given" name="cash_given" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-secondary mt-1 rounded"><i class="fas fa-money-bill"></i> Balance</button>
                </form>
                <div id="payment-result" class="mt-1">
                </div>
            </div>
            <div class="custom-form-container custom-container-color-2 mt-1">
    <form action="{{ url('/addToDebt') }}" method="POST">
        @csrf
        <select class="form-control mt-1" name="customer_id" id="customer_id">
            @if(isset($customer) && count($customer) > 0)
                @foreach($customer as $cust)
                    <option value="{{ $cust->id }}">{{ $cust->customer_name }}</option>
                @endforeach
            @endif
        </select>
        <button class="btn btn-success mt-1 rounded" type="submit">
            <i class="fas fa-hand-holding-usd"></i> Add to Debt
        </button>
    </form>
</div>

        </nav>

        <!-- Main content area -->
        <main class="col main-content">
            <!-- Alert Messages -->
            <div id="alert-container" class="message rounded">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>
            <div id="alert-container" class="message rounded">
    <!-- Messages will be dynamically inserted here -->
</div>
            <div class="custom-form-container ">
                <div class="custom-header">
                    <div class="row mb-2">
                        <div class="col-md-4 mb-1">
                            <form action="{{ url('/holdCart') }}" method="POST">
                                @csrf
                                <button class="btn btn-success btn-sm" id="holdCartBtn"><i class="fas fa-pause"></i> Hold Sale</button>
                            </form>
                        </div>

                        <div class="col-md-4 mb-1">
                            <div class="dropdown">
                                <a class="btn btn-primary btn-sm dropdown-toggle custom" href="#" id="checkoutDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-shopping-bag"></i> Checkout
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="checkoutDropdown">
                                <li><a class="dropdown-item checkout-option" data-method="cash" href="{{ url('/checkout') }}">Cash</a></li>
    <li><a class="dropdown-item checkout-option" data-method="mpesa" href="#" data-bs-toggle="modal" data-bs-target="#mpesaModal">M-Pesa</a></li>
    <li><a class="dropdown-item checkout-option" data-method="card" href="#">Card</a></li>
                                </ul>
                               

                            </div>
                        </div>

                        <div class="col-md-4">
                            <form action="{{ url('/clearAllItems') }}" method="POST" id="clear-cart-form">
                                @csrf
                                <button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Clear Cart</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="custom-table-container">
                    @if(isset($cartItems) && !$cartItems->isEmpty())
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Product ID</th>
                                        <th>Product Name</th>
                                        <th>Description</th>
                                        <th>Price</th>
                                        <th>Active Price</th>
                                        <th>Quantity</th>
                                        <th>Update Quantity & Active Price</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody id="cart-items">
                                    @foreach($cartItems as $item)
                                        <tr data-product-id="{{ $item->product_id }}">
                                            <td>{{ $item->product_id }}</td>
                                            <td>{{ $item->product_name }}</td>
                                            <td>{{ $item->description }}</td>
                                            <td>
                                                @if($item->active_price)
                                                    <span class="strikethrough">{{ number_format($item->price, 2) }}</span>
                                                @else
                                                    {{ number_format($item->price, 2) }}
                                                @endif
                                            </td>
                                            <td>{{ $item->active_price ? number_format($item->active_price, 2) : 'N/A' }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>
                                                <form class="form-inline update-cart-form" method="POST" action="{{url('/updateCart', $item->id) }}" data-item-id="{{ $item->id }}">
                                                    @csrf
                                                    <div class="form-group">
                                                        <input type="number" min="0" name="quantity" value="{{ $item->quantity }}" class="form-control form-control-sm">
                                                        <input type="number" name="active_price" placeholder="Enter active price" value="{{ $item->active_price }}" class="form-control form-control-sm mt-1">
                                                    </div>
                                                    <button class="btn btn-success btn-sm mt-1 rounded" type="submit">
                                                        <i class="fas fa-sync-alt"></i> Update
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <button class="btn btn-danger btn-sm delete-item" data-item-id="{{ $item->id }}">
                                                  Delete
                                                </button>
                                            </td>
                                            
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p>The cart is empty.</p>
                    @endif
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Add loading spinner -->
<div id="loading-spinner" class="position-fixed top-50 start-50 translate-middle d-none">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<!-- M-Pesa Modal -->
<div class="modal fade" id="mpesaModal" tabindex="-1" aria-labelledby="mpesaModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="mpesaModalLabel">M-Pesa Payment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="mpesaPaymentForm">
          @csrf
          <div class="mb-3">
            <label for="phoneNumber" class="form-label">Customer Phone Number</label>
            <input type="text" class="form-control" id="phoneNumber" name="phone" placeholder="e.g. 07XXXXXXXX" required>
          </div>
          <button type="submit" class="btn btn-success w-100">Pay with M-Pesa</button>
        </form>
        <div id="paymentStatus" class="mt-3 text-center"></div>
      </div>
    </div>
  </div>
</div>

<script src="{{ asset('/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('/js/scriptsfiles.js') }}"></script>
