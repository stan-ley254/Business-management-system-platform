@include('navstore')
<div class="p-4">
<div class="container-sm">
    @if(session('success'))
    <div class="message d-print-inline-flex rounded">
        <div class="alert alert-success" id="success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger" id="error">
            {{ session('error') }}
        </div>
    @endif
</div>
    <!-- Setup Items Exchange Form -->
    <div class="custom-form-container">
        <!-- Header Section -->
        <div class="custom-header">
            Create New Supplier
        </div>

        <!-- Form Section -->
        <form action="{{ url('storeSupplier') }}" method="POST" class="row">
        @csrf
        <div class="form-group">
                        <label for="supplier_name">Supplier Name:</label>
                        <input type="text" name="supplier_name" id="supplier_name" class="form-control" >
                    </div>
                    <div class="form-group">
                        <label for="phone_number" >Phone Number:</label>
                        <input type="text" name="phone_number" id="phone_number" class="form-control " >
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status:</label>
                        <input type="text" name="status" id="status" class="form-control " >
                    </div>
                    <div class="form-group">
                        <label for="amount">Amount:</label>
                        <input type="number" name="amount" id="amount" class="form-control  " >
                    </div>
                    <div class="form-group">
                        <label for="location">Location:</label>
                        <input type="text" name="location" id="location" class="form-control " >
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
