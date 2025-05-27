@include('navstore')
<div class="container-xxl my-5">
<div class="message d-print-inline-flex rounded">
    @if(session('success'))
        <div class="alert alert-success" id="success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
</div>
    <!-- Setup Items Exchange Form -->
    <div class="custom-form-container">
        <!-- Header Section -->
        <div class="custom-header">
            Create New Customer
        </div>

        <!-- Form Section -->
        <form action="{{ url('storeCustomer') }}" method="POST" class="row">
        @csrf
            <!-- Branch (Others) Selection -->
            <div class="form-group">
            <label for="customer_name">Customer Name:</label>
            <input type="text" class="form-control" id="customer_name" name="customer_name" required>
            </div>

            <!-- Start Date -->
            <div class=" form-group">
            <label for="phone_number">Phone Number:</label>
            <input type="text" class="form-control" id="phone_number" name="phone_number">
            </div>

            <!-- End Date -->
            <div class=" form-group">
            <label for="location">Location:</label>
            <input type="text" class="form-control" id="location" name="location">
            </div>

            <!-- Search Button -->
            <div class="col-md-12 text-right">
                <button type="submit" class="btn btn-custom mt-2">
                    <i class="fas fa-check"></i>  Create
                </button>
            </div>
        </form>
    </div>
</div>
@include('jquery')
<!-- Bootstrap JS, Popper.js, and jQuery -->


</body>
</html>
