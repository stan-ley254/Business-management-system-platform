// Set up CSRF token for all AJAX requests
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
 $(document).ready(function() {
    // Show the success message when the page loads
    $('#success').show();

    // Set a timer to hide the success message after 5 seconds
    setTimeout(function() {
        $('#success').fadeOut('slow'); // Fade out slowly
    }, 1000); // 1000 milliseconds = 1 seconds
});
// Function to parse and format response data
function handleResponseData(response) {
    // Handle string responses
    if (typeof response === 'string') {
        try {
            return JSON.parse(response);
        } catch (e) {
            return { message: response };
        }
    }
    // Handle JSON responses
    return response;
}
document.getElementById('start-scan-btn').addEventListener('click', function () {
    const scannerContainer = document.getElementById('scanner-container');
    scannerContainer.style.display = 'block';

    Quagga.init({
        inputStream: {
            name: "Live",
            type: "LiveStream",
            target: document.querySelector('#barcode-scanner'),
            constraints: {
                facingMode: "environment"
            },
        },
        decoder: {
            readers: ["ean_reader", "code_128_reader"]
        },
    }, function (err) {
        if (err) {
            console.error(err);
            return;
        }
        Quagga.start();
    });

    Quagga.onDetected(function (result) {
        const barcode = result.codeResult.code;
        console.log("Scanned:", barcode);

        // Prevent multiple detections
        Quagga.offDetected();

        // Optional: show loading or feedback
        showResponseMessage('Processing scanned product...', 'info');

        // Send to backend
        $.ajax({
            url: '/add-cart-by-barcode',
            method: 'POST',
            data: {
                barcode: barcode,
                quantity: 1
            },
            success: function (response) {
                console.log('Scan Response:', response);

                if (response.status === 'success') {
                    if (response.cartItem) {
                        updateCartDisplay(response.cartItem);
                    } else {
                        loadCartItems(); // fallback if cartItem is missing
                    }

                    updateTotalAmount();
                    showResponseMessage(response.message || 'Product added successfully');
                } else {
                    showResponseMessage(response.message || 'Product not found', 'danger');
                }

                Quagga.stop();
                scannerContainer.style.display = 'none';
            },
            error: function () {
                showResponseMessage('Error occurred while adding product', 'danger');
                Quagga.stop();
                scannerContainer.style.display = 'none';
            }
        });
    });
});

// Enhanced show response message function
function showResponseMessage(response, type = 'success') {
    const data = handleResponseData(response);
    let message = '';

    // Handle different response formats
    if (typeof data === 'object') {
        message = data.message || data.error || JSON.stringify(data);
    } else {
        message = String(data);
    }

    // Create alert HTML
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    // Add alert to container
    $('#alert-container').html(alertHtml);
    
    // Auto-hide after 3 seconds
    setTimeout(() => {
        $('.alert').fadeOut('slow', function() {
            $(this).remove();
        });
    }, 3000);

    // Also log to console for debugging
    console.log('Response:', data);
}

$(document).ready(function() {
    // Message handling (if you have elements with ID 'success' or 'message' you want to auto-hide)
    function handleMessages() {
        $('#success, #message').show().delay(1000).fadeOut('slow');
    }
    handleMessages();

    // Function to format price
    function formatPrice(price) {
        return parseFloat(price || 0).toFixed(2);
    }

    // Function to update cart display
   function updateCartDisplay(cartItem) {
    // If cart table doesn’t exist yet, build it
    if ($('#cart-items').length === 0) {
        $('.custom-table-container').html(`
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Description</th>
                            <th>Original Price</th>
                            <th>Active Price</th>
                            <th>Quantity</th>
                            <th>Update Quantity & Active Price</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody id="cart-items"></tbody>
                </table>
            </div>
        `);
    }

    const cartBody = $('#cart-items');
    const existingRow = $(`tr[data-product-id="${cartItem.product_id}"]`);

    const originalPrice = formatPrice(cartItem.price);
    const activePrice = cartItem.discount_price ? formatPrice(cartItem.discount_price) : 'N/A';

    const priceDisplay = cartItem.discount_price
        ? `<span class="strikethrough">${originalPrice}</span>`
        : originalPrice;

    const rowHtml = `
        <tr data-product-id="${cartItem.product_id}">
            <td>${cartItem.product_id}</td>
            <td>${cartItem.product_name}</td>
            <td>${cartItem.description}</td>
            <td>${priceDisplay}</td>
            <td>${activePrice}</td>
            <td>${cartItem.quantity}</td>
            <td>
                <form class="update-cart-form" data-item-id="${cartItem.id}">
                    <div class="form-group">
                        <input type="number" min="1" name="quantity" value="${cartItem.quantity}" class="form-control mb-2">
                        <input type="number" name="active_price" step="0.01" placeholder="Enter active price" value="${cartItem.discount_price || ''}" class="form-control">
                    </div>
                    <button class="btn btn-success mt-2 rounded update-cart-btn" type="submit">
                        <i class="fas fa-sync-alt"></i> Update
                    </button>
                </form>
            </td>
            <td>
                <button class="btn btn-danger btn-sm delete-item" data-item-id="${cartItem.id}">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </td>
        </tr>
    `;

    if (existingRow.length) {
        existingRow.replaceWith(rowHtml);
    } else {
        cartBody.append(rowHtml);
    }

    updateTotalAmount();
}

  

    // Helper function to update total amount
    function updateTotalAmount() {
        $.ajax({
            url: '/calculateTotalAmount',
            method: 'GET',
            success: function(response) {
                $('#totalAmount').text(formatPrice(response.total_amount));
            },
            error: function() {
                console.error('Error updating total amount');
            }
        });
    }

    // Load cart items
    function loadCartItems() {
        $.ajax({
            url: '/getCartItems',
            method: 'GET',
            success: function(response) {
                if (response.cartItems && response.cartItems.length > 0) {
                    $('.custom-table-container p').remove();
                    response.cartItems.forEach(function(cartItem) {
                        updateCartDisplay(cartItem);
                    });
                    $('#totalAmount').text(formatPrice(response.total_amount));
                } else {
                    $('.custom-table-container').html('<p>The cart is empty.</p>');
                    $('#totalAmount').text('0.00');
                }
            },
            error: function() {
                showResponseMessage('Error loading cart items', 'danger');
            }
        });
    }

    // Handle product selection
    $('#product-select').change(function() {
        const productId = $(this).val();
        const quantity = $('#quantity').val();

        if (productId) {
            $.ajax({
                url: '/addCart',
                method: 'POST',
                data: {
                    product_id: productId,
                    quantity: quantity
                },
                success: function(response) {
                    if (response.cartItem) {
                        updateCartDisplay(response.cartItem);
                    }
                    if (response.message) {
                        // Replace showAlert with our custom showResponseMessage function
                        showResponseMessage(response, response.status === 'success' ? 'success' : 'danger');
                    }
                },
                error: function(xhr) {
                    showResponseMessage(xhr.responseJSON?.message || 'Error adding item to cart', 'danger');
                }
            });
        }
    });

    document.getElementById('mpesaPaymentForm').addEventListener('submit', async function(e) {
        e.preventDefault();
    
        const phone = document.getElementById('phoneNumber').value;
        const statusDiv = document.getElementById('paymentStatus');
        statusDiv.innerText = 'Processing payment...';
    
        try {
            const res = await fetch("{{ route('mpesa.stkpush') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ phone })
            });
    
            const data = await res.json();
    
            if (res.ok && data.ResponseCode === "0") {
                statusDiv.innerHTML = '<span class="text-success">STK push sent. Complete the payment on your phone.</span>';
            } else {
                statusDiv.innerHTML = '<span class="text-danger">Payment failed: ' + (data.errorMessage || 'Unknown error') + '</span>';
            }
        } catch (err) {
            statusDiv.innerHTML = '<span class="text-danger">Error contacting payment server.</span>';
        }
    });

    // Handle cart item updates - single implementation
    $(document).on('submit', '.update-cart-form', function(e) {
        e.preventDefault();
        const form = $(this);
        const itemId = form.data('item-id');
        
        $.ajax({
            url: `/updateCart/${itemId}`,
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.status === 'success') {
                    updateCartDisplay(response.cartItem);
                    showResponseMessage(response, 'success');
                } else {
                    showResponseMessage(response.message || 'Error updating cart', 'danger');
                }
            },
            error: function(xhr) {
                showResponseMessage(xhr.responseJSON?.message || 'Error updating cart', 'danger');
            }
        });
    });

    // Handle delete item
    $(document).on('click', '.delete-item', function() {
        const itemId = $(this).data('item-id');
        if (confirm('Are you sure you want to remove this item?')) {
            $.ajax({
                url: `/deleteCartItem/${itemId}`,
                method: 'POST',
                success: function(response) {
                    if (response.status === 'success') {
                        $(`button[data-item-id="${itemId}"]`).closest('tr').remove();
                        updateTotalAmount();
                        if ($('#cart-items tr').length === 0) {
                            $('.custom-table-container').html('<p>The cart is empty.</p>');
                        }
                        // Replace default alert() with our custom message
                        showResponseMessage(response, 'success');
                    } else {
                        showResponseMessage(response, 'danger');
                    }
                },
                error: function() {
                    showResponseMessage('Error deleting item', 'danger');
                }
            });
        }
    });

    // Payment form handling
    $('#payment-form').on('submit', function(e) {
        e.preventDefault();
        const cashGiven = $('#cash_given').val();

        $.ajax({
            url: '/processPayment',
            method: 'GET',
            data: {
                cash_given: cashGiven
            },
            success: function(response) {
                $('#payment-result').html(`<div>Total Amount: ${response.total_amount}<br>Balance: ${response.balance}</div>`);
            },
            error: function(response) {
                const errorMessage = response.responseJSON.error;
                $('#payment-result').html(`<div class="alert alert-danger">${errorMessage}</div>`);
            }
        });
    });

    // Update period total on DOMContentLoaded
    document.addEventListener('DOMContentLoaded', function () {
        let periodTotal = 0;

        // Select all rows that contain the grouped cart totals
        const totalRows = document.querySelectorAll('.total-row');

        // Loop through each total row and add its value to the period total
        totalRows.forEach(row => {
            const total = parseFloat(row.getAttribute('data-total')) || 0;
            periodTotal += total;
        });

        // Display the final period total in the designated element
        const periodTotalElement = document.getElementById('period-total');
        if (periodTotalElement) {
            periodTotalElement.textContent = periodTotal.toFixed(2);
        }
    });

    // Product search functionality
    function fetchProducts() {
        let query = $('#product-search').val();
        let category = $('#category-select').val();
        
        $.ajax({
            url: '/searchProductCart',
            method: 'GET',
            data: { query: query, category: category },
            success: function(data) {
                let options = '<option value="">Select a product</option>';
                data.forEach(product => {
                    options += `<option value="${product.id}">${product.product_name}</option>`;
                });
                $('#product-select').html(options);
            }
        });
    }

    $('#product-search').on('input', fetchProducts);
    $('#category-select').on('change', fetchProducts);

    // Initialize cart items and total
    loadCartItems();
    updateTotalAmount();
});
