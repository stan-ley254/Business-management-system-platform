/* scriptsfiles.js
   Merged POS script + OfflineSync module (localforage + Sanctum)
   Requires:
   - localforage loaded globally
   - jQuery loaded globally
   - Laravel Sanctum token stored in localStorage as "api_token"
*/

///////////////////////////
// OfflineSync Module
///////////////////////////
const OfflineSync = (function () {
    const QUEUE_KEY = 'offlineQueue';
    const LOCAL_CART_KEY = 'localCart';
    const SYNC_ENDPOINT = '/api/sync/receive'; // Sanctum-protected API route

    // configure localforage
    if (typeof localforage !== 'undefined') {
        localforage.config({
            name: 'JustartTech',
            storeName: 'offline_data'
        });
    } else {
        console.warn('localforage not found. Offline queue disabled.');
    }

    async function getQueue() {
        return (await localforage.getItem(QUEUE_KEY)) || [];
    }

    async function saveQueue(queue) {
        await localforage.setItem(QUEUE_KEY, queue);
        updateQueuedCountUI(queue.length);
    }

    function updateQueuedCountUI(count) {
        let badge = document.getElementById('offline-queue-badge');
        if (!badge) {
            const nav = document.querySelector('.navbar') || document.body;
            if (!nav) return;
            badge = document.createElement('span');
            badge.id = 'offline-queue-badge';
            badge.style.cssText = 'display:inline-block;margin-left:8px;padding:2px 6px;background:#ff9800;color:#fff;border-radius:12px;font-size:12px;';
            badge.title = 'Offline queued actions';
            nav.appendChild(badge);
        }
        badge.textContent = count > 0 ? `${count} queued` : '';
    }

    async function enqueueAction(action) {
        const queue = await getQueue();
        action.created_at = new Date().toISOString();
        action.attempts = action.attempts || 0;
        action.client_id = action.client_id || `c-${Date.now()}`;
        queue.push(action);
        await saveQueue(queue);
        console.log('[OfflineSync] Action queued', action);
        return action.client_id;
    }
async function processQueueOnce() {
    const queue = await getQueue();
    if (!queue.length) return;

    if (navigator.onLine) {
        console.log("[OfflineSync] trying to sync queue", queue);

        const apiToken = localStorage.getItem('api_token');
        if (!apiToken) {
            console.warn("[OfflineSync] No API token found, cannot sync.");
            return;
        }

        const res = await fetch('/sync/receive', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${apiToken}`
            },
            body: JSON.stringify({ actions: queue })
        });

        const rawText = await res.text();
        console.log("[OfflineSync] Raw server response:", rawText);

        let result;
        try {
            result = JSON.parse(rawText);
        } catch (e) {
            console.error("[OfflineSync] JSON parse error", e);
            return;
        }

        if (result.ok && Array.isArray(result.results)) {
            // Keep only failed actions in the queue
            const failed = [];
            result.results.forEach((r, idx) => {
                if (!r.ok) {
                    // Increase attempt count
                    const action = queue[idx];
                    action.attempts = (action.attempts || 0) + 1;
                    if (action.attempts <= MAX_ATTEMPTS) {
                        failed.push(action);
                    } else {
                        console.warn("[OfflineSync] Dropping action after max attempts", action);
                    }
                }
            });

            await saveQueue(failed);
        }
    }
}


    async function sendOrQueueAjax(options) {
        options = options || {};
        const method = (options.type || options.method || 'GET').toUpperCase();

        if (method === 'GET') {
            if (navigator.onLine) return $.ajax(options);
            if (options._fallbackLocalKey) {
                const cached = await localforage.getItem(options._fallbackLocalKey);
                if (options.success) options.success(cached);
                return Promise.resolve({ queued: false, cached: true, data: cached });
            }
            if (options.error) options.error({ message: 'Offline: GET not available' });
            return Promise.reject({ message: 'Offline: GET not available' });
        }

        if (navigator.onLine) return $.ajax(options);

        const action = {
            url: options.url,
            method,
            data: options.data || {},
            meta: options._meta || {}
        };
        const clientId = await enqueueAction(action);

        if (typeof options.success === 'function') {
            options.success({ queued: true, client_id: clientId, message: 'Queued offline: will sync when online.' });
        }
        return Promise.resolve({ queued: true, client_id: clientId });
    }

    async function getLocalCart() {
        return (await localforage.getItem(LOCAL_CART_KEY)) || [];
    }
    async function setLocalCart(items) {
        await localforage.setItem(LOCAL_CART_KEY, items || []);
    }
    async function addToLocalCart(item) {
        const cart = await getLocalCart();
        const existing = cart.find(ci => String(ci.product_id) === String(item.product_id));
        if (existing) {
            existing.quantity += item.quantity || 1;
        } else {
            cart.push(item);
        }
        await setLocalCart(cart);
        return cart;
    }
    async function removeFromLocalCartByClientItemId(id) {
        let cart = await getLocalCart();
        cart = cart.filter(ci => ci._clientItemId !== id && ci.id !== id);
        await setLocalCart(cart);
    }
    async function updateLocalCartItem(id, updates) {
        let cart = await getLocalCart();
        let changed = false;
        cart = cart.map(ci => {
            if (ci._clientItemId === id || ci.id === id) {
                changed = true;
                return { ...ci, ...updates };
            }
            return ci;
        });
        await setLocalCart(cart);
        return changed;
    }

    function init() {
        window.addEventListener('online', () => processQueueOnce());
        if (navigator.onLine) processQueueOnce();
    }

    return {
        init,
        enqueueAction,
        processQueueOnce,
        sendOrQueueAjax,
        getLocalCart,
        addToLocalCart,
        updateLocalCartItem,
        removeFromLocalCartByClientItemId,
        LOCAL_CART_KEY
    };
})();

///////////////////////////
// Global CSRF for normal web requests
///////////////////////////
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

OfflineSync.init();

/* ---------------------------
   Utility: format price
   --------------------------- */
function formatPrice(price) {
    return parseFloat(price || 0).toFixed(2);
}

/* ---------------------------
   UI: showResponseMessage
   Replaces your previous function but retains behavior
   --------------------------- */
function handleResponseData(response) {
    if (typeof response === 'string') {
        try { return JSON.parse(response); } catch (e) { return { message: response }; }
    }
    return response;
}
function showResponseMessage(response, type = 'success') {
    const data = handleResponseData(response);
    let message = '';

    if (typeof data === 'object') {
        message = data.message || data.error || JSON.stringify(data);
    } else {
        message = String(data);
    }

    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;

    $('#alert-container').html(alertHtml);

    setTimeout(() => {
        $('.alert').fadeOut('slow', function() { $(this).remove(); });
    }, 3000);

    console.log('Response:', data);
}

/* ---------------------------
   Cart UI helpers using local cache support
   --------------------------- */
function updateCartDisplay(cartItem) {
    // create table if missing
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
    const activePrice = cartItem.active_price ? formatPrice(cartItem.active_price) : 'N/A';
    const priceDisplay = cartItem.active_price ? `<span class="strikethrough">${originalPrice}</span>` : originalPrice;

    // ensure each offline-created item has a client-side id for updates/deletes
    if (!cartItem._clientItemId && !cartItem.id) {
        cartItem._clientItemId = `temp-${Date.now()}`;
    }

    const clientIdAttr = cartItem.id || cartItem._clientItemId;

    const rowHtml = `
        <tr data-product-id="${cartItem.product_id}" data-client-item-id="${clientIdAttr}">
            <td>${cartItem.product_id}</td>
            <td>${cartItem.product_name}</td>
            <td>${cartItem.description || ''}</td>
            <td>${priceDisplay}</td>
            <td>${activePrice}</td>
            <td>${cartItem.quantity}</td>
            <td>
                <form class="update-cart-form" data-item-id="${clientIdAttr}">
                    <div class="form-group">
                        <input type="number" min="1" name="quantity" value="${cartItem.quantity}" class="form-control mb-2">
                        <input type="number" name="active_price" step="0.01" placeholder="Enter active price" value="${cartItem.active_price || ''}" class="form-control">
                    </div>
                    <button class="btn btn-success mt-2 rounded update-cart-btn" type="submit">
                        <i class="fas fa-sync-alt"></i> Update
                    </button>
                </form>
            </td>
            <td>
                <button class="btn btn-danger btn-sm delete-item" data-item-id="${clientIdAttr}">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </td>
        </tr>
    `;

    if (existingRow.length) existingRow.replaceWith(rowHtml); else cartBody.append(rowHtml);

    updateTotalAmount();
}

async function updateTotalAmount() {
    if (navigator.onLine) {
        try {
            const res = await $.ajax({ url: '/calculateTotalAmount', method: 'GET' });
            if (res && typeof res.total_amount !== 'undefined') {
                $('#totalAmount').text(formatPrice(res.total_amount));
            } else {
                // fallback to local sum
                const localCart = await OfflineSync.getLocalCart();
                const total = (localCart || []).reduce((s, it) => s + ((it.active_price || it.price || 0) * (it.quantity || 1)), 0);
                $('#totalAmount').text(formatPrice(total));
            }
        } catch (err) {
            const localCart = await OfflineSync.getLocalCart();
            const total = (localCart || []).reduce((s, it) => s + ((it.active_price || it.price || 0) * (it.quantity || 1)), 0);
            $('#totalAmount').text(formatPrice(total));
        }
    } else {
        const localCart = await OfflineSync.getLocalCart();
        const total = (localCart || []).reduce((s, it) => s + ((it.active_price || it.price || 0) * (it.quantity || 1)), 0);
        $('#totalAmount').text(formatPrice(total));
    }
}

/* ---------------------------
   Load Cart Items (tries online; falls back to local cache)
   - Uses OfflineSync.sendOrQueueAjax for requests where appropriate
   --------------------------- */
function loadCartItems() {
    if (navigator.onLine) {
        $.ajax({
            url: '/getCartItems',
            method: 'GET',
            success: function (response) {
                if (response.cartItems && response.cartItems.length > 0) {
                    $('.custom-table-container p').remove();
                    $('#cart-items').remove();
                    response.cartItems.forEach(function (cartItem) {
                        updateCartDisplay(cartItem);
                    });
                    $('#totalAmount').text(formatPrice(response.total_amount || 0));
                    // Also cache for offline usage
                    localforage.setItem(OfflineSync.LOCAL_CART_KEY, response.cartItems);
                } else {
                    $('.custom-table-container').html('<p>The cart is empty.</p>');
                    $('#totalAmount').text('0.00');
                    localforage.setItem(OfflineSync.LOCAL_CART_KEY, []);
                }
            },
            error: async function () {
                // fallback to local
                const cached = await localforage.getItem(OfflineSync.LOCAL_CART_KEY);
                if (cached && cached.length) {
                    $('.custom-table-container p').remove();
                    $('#cart-items').remove();
                    cached.forEach(function (cartItem) {
                        updateCartDisplay(cartItem);
                    });
                    const total = cached.reduce((s, it) => s + ((it.active_price || it.price || 0) * (it.quantity || 1)), 0);
                    $('#totalAmount').text(formatPrice(total));
                } else {
                    $('.custom-table-container').html('<p>The cart is empty.</p>');
                    $('#totalAmount').text('0.00');
                }
            }
        });
    } else {
        // offline: load cached local cart
        localforage.getItem(OfflineSync.LOCAL_CART_KEY).then((cached) => {
            if (cached && cached.length) {
                $('.custom-table-container p').remove();
                $('#cart-items').remove();
                cached.forEach(function (cartItem) {
                    updateCartDisplay(cartItem);
                });
                const total = cached.reduce((s, it) => s + ((it.active_price || it.price || 0) * (it.quantity || 1)), 0);
                $('#totalAmount').text(formatPrice(total));
            } else {
                $('.custom-table-container').html('<p>The cart is empty.</p>');
                $('#totalAmount').text('0.00');
            }
        });
    }
}

/* ---------------------------
   Event handlers (converted to use sendOrQueueAjax for mutating calls)
   --------------------------- */
$(document).ready(function () {
    // show/hide initial success element
    $('#success').show();
    setTimeout(function () { $('#success').fadeOut('slow'); }, 1000);

    // scanner button
    $('#start-scan-btn').on('click', function () {
        const scannerContainer = document.getElementById('scanner-container');
        scannerContainer.style.display = 'block';
        const beep = new Audio('/sounds/short-beep-tone.mp3');

        function startScanner() {
            Quagga.init({
                inputStream: {
                    name: "Live",
                    type: "LiveStream",
                    target: document.querySelector('#barcode-scanner'),
                    constraints: { facingMode: "environment" }
                },
                decoder: { readers: ["ean_reader", "code_128_reader"] }
            }, function (err) {
                if (err) {
                    console.error(err);
                    return;
                }
                Quagga.start();
            });

            Quagga.onDetected(processScan);
        }

        function processScan(result) {
            const barcode = result.codeResult.code;
            Quagga.offDetected();
            Quagga.stop();
            beep.play();
            showResponseMessage('Processing scanned product...', 'info');

            // Use OfflineSync wrapper
            OfflineSync.sendOrQueueAjax({
                url: '/add-cart-by-barcode',
                method: 'POST',
                type: 'POST',
                data: { barcode: barcode, quantity: 1 },
                success: function (response) {
                    console.log('Scan Response:', response);
                    if (response.queued) {
                        showResponseMessage('Offline: scan queued — will add when online.', 'warning');
                    } else {
                        if (response.status === 'success' || response.cartItem) {
                            const item = response.cartItem || response;
                            // Adjust discount_price aliasing
                            item.discount_price = item.discount_price ?? item.active_price ?? null;
                            updateCartDisplay(item);
                            updateTotalAmount();
                            showResponseMessage(response.message || 'Product added successfully', 'success');
                        } else {
                            showResponseMessage(response.message || 'Product not found', 'danger');
                        }
                    }
                    // Restart scanner after delay
                    setTimeout(() => startScanner(), 1500);
                },
                error: function () {
                    showResponseMessage('Error occurred while adding product', 'danger');
                    setTimeout(() => startScanner(), 1500);
                }
            });
        }
        startScanner();
    });

    // Add product via select
    $('#product-select').change(function () {
        const productId = $(this).val();
        const quantity = parseInt($('#quantity').val() || 1, 10);

        if (!productId) return;

        // If offline, construct a local cart item to show immediately
        if (!navigator.onLine) {
            // use selected option data- attributes
            const option = $(this).find(`option[value="${productId}"]`);
            const price = parseFloat(option.data('price') || 0);
            const product_name = option.text().split('(')[0].trim();
            const localItem = {
                _clientItemId: `temp-${Date.now()}`,
                product_id: productId,
                product_name,
                description: option.data('description') || '',
                price,
                active_price: null,
                quantity
            };
            // add to local cart and UI
            OfflineSync.addToLocalCart(localItem).then(() => {
                updateCartDisplay(localItem);
                updateTotalAmount();
                showResponseMessage('Offline: product added to local cart and queued for sync.', 'warning');
            });
            // still enqueue the server action
            OfflineSync.sendOrQueueAjax({
                url: '/addCart',
                method: 'POST',
                type: 'POST',
                data: { product_id: productId, quantity: quantity },
                success: function (resp) {
                    // if we get real server response later, SyncController should map and replace local/temp ids
                    console.log('AddCart response', resp);
                }
            });
            return;
        }

        // Online path
        OfflineSync.sendOrQueueAjax({
            url: '/addCart',
            method: 'POST',
            type: 'POST',
            data: { product_id: productId, quantity: quantity },
            success: function (response) {
                if (response.cartItem) {
                    updateCartDisplay(response.cartItem);
                }
                if (response.message) {
                    showResponseMessage(response, response.status === 'success' ? 'success' : 'danger');
                }
            },
            error: function (xhr) {
                showResponseMessage(xhr.responseJSON?.message || 'Error adding item to cart', 'danger');
            }
        });
    });

    // M-Pesa modal handling (STK Push)
    document.getElementById('mpesaPaymentForm')?.addEventListener('submit', async function (e) {
        e.preventDefault();
        const phone = document.getElementById('phoneNumber').value;
        const amount = document.getElementById('mpesaAmount').value;
        const statusDiv = document.getElementById('paymentStatus');

        if (!navigator.onLine) {
            // queue the STK push
            OfflineSync.sendOrQueueAjax({
                url: "{{ route('mpesa.stkpush') }}".replace(/^{|}$/g,''), // ensure string literal works
                type: 'POST',
                method: 'POST',
                data: { phone, amount },
                success: function (resp) {
                    showResponseMessage('Offline: STK push queued. It will be attempted when online.', 'warning');
                    statusDiv.innerHTML = '<span class="text-warning">Queued STK push; will run when online.</span>';
                },
                error: function () {
                    statusDiv.innerHTML = '<span class="text-danger">Failed to queue payment.</span>';
                }
            });
            return;
        }

        // online: call server directly (existing behavior via fetch)
        try {
            statusDiv.innerText = 'Processing payment...';
            const res = await fetch("{{ route('mpesa.stkpush') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ phone, amount })
            });
            const data = await res.json();
            if (res.ok && data.ResponseCode === "0") {
                statusDiv.innerHTML = '<span class="text-success">STK push sent. Complete the payment by entering PIN on the phone.</span>';
            } else {
                statusDiv.innerHTML = '<span class="text-danger">Payment failed: ' + (data.errorMessage || 'Unknown error') + '</span>';
            }
        } catch (err) {
            statusDiv.innerHTML = '<span class="text-danger">Error contacting payment server.</span>';
        }
    });

    // Update cart item via delegated event (works for online/offline)
    $(document).on('submit', '.update-cart-form', function (e) {
        e.preventDefault();
        const form = $(this);
        const itemId = form.data('item-id'); // may be temp client id or server id
        const quantity = form.find('input[name="quantity"]').val();
        const active_price = form.find('input[name="active_price"]').val();

        // Build data payload
        const payload = { quantity, active_price };

        if (!navigator.onLine) {
            // update local cache and UI immediately
            OfflineSync.updateLocalCartItem(itemId, { quantity: parseInt(quantity, 10), active_price: active_price || null })
                .then(changed => {
                    if (changed) {
                        // update UI row values
                        const row = $(`tr[data-client-item-id="${itemId}"]`);
                        row.find('td').eq(5).text(quantity);
                        row.find('td').eq(4).text(active_price ? parseFloat(active_price).toFixed(2) : 'N/A');
                        updateTotalAmount();
                        showResponseMessage('Offline: update queued and applied locally.', 'warning');
                    }
                });
            // still enqueue the server update
            OfflineSync.sendOrQueueAjax({
                url: `/updateCart/${itemId}`,
                type: 'POST',
                method: 'POST',
                data: payload
            });
            return;
        }

        // Online: send to server
        OfflineSync.sendOrQueueAjax({
            url: `/updateCart/${itemId}`,
            type: 'POST',
            method: 'POST',
            data: payload,
            success: function (response) {
                if (response.status === 'success') {
                    updateCartDisplay(response.cartItem);
                    showResponseMessage(response, 'success');
                } else {
                    showResponseMessage(response.message || 'Error updating cart', 'danger');
                }
            },
            error: function (xhr) {
                showResponseMessage(xhr.responseJSON?.message || 'Error updating cart', 'danger');
            }
        });
    });

    // Delete item
    $(document).on('click', '.delete-item', function () {
        const itemId = $(this).data('item-id');
        if (!confirm('Are you sure you want to remove this item?')) return;

        if (!navigator.onLine) {
            // remove from local cart and UI
            OfflineSync.removeFromLocalCartByClientItemId(itemId).then(() => {
                $(`button[data-item-id="${itemId}"]`).closest('tr').remove();
                updateTotalAmount();
                showResponseMessage('Offline: delete queued and removed locally.', 'warning');
            });
            // enqueue server delete
            OfflineSync.sendOrQueueAjax({
                url: `/deleteCartItem/${itemId}`,
                type: 'POST',
                method: 'POST'
            });
            return;
        }

        // online delete
        OfflineSync.sendOrQueueAjax({
            url: `/deleteCartItem/${itemId}`,
            type: 'POST',
            method: 'POST',
            success: function (response) {
                if (response.status === 'success') {
                    $(`button[data-item-id="${itemId}"]`).closest('tr').remove();
                    updateTotalAmount();
                    showResponseMessage(response, 'success');
                } else {
                    showResponseMessage(response, 'danger');
                }
            },
            error: function () {
                showResponseMessage('Error deleting item', 'danger');
            }
        });
    });

    // Checkout dropdown actions: for POS we recommend routing through /checkout or sync if offline
    $(document).on('click', '.checkout-option', function (e) {
        e.preventDefault();
        const method = $(this).data('method');

        if (method === 'mpesa') {
            // open modal handled via data-bs-target
            $('#mpesaModal').modal('show');
            return;
        }

        // For checkout: if offline, enqueue the full cart payload to be processed server-side
        if (!navigator.onLine) {
            // gather local cart
            localforage.getItem(OfflineSync.LOCAL_CART_KEY).then(localCart => {
                OfflineSync.sendOrQueueAjax({
                    url: '/checkout',
                    type: 'POST',
                    method: 'POST',
                    data: { method, cart: localCart || [] },
                    success: function (resp) {
                        showResponseMessage('Offline: checkout queued. Will complete when online.', 'warning');
                        // Clear local cart UI and cache
                        localforage.setItem(OfflineSync.LOCAL_CART_KEY, []).then(() => {
                            $('.custom-table-container').html('<p>The cart is empty.</p>');
                            $('#totalAmount').text('0.00');
                        });
                    }
                });
            });
            return;
        }

        // online checkout: follow default navigation or AJAX call based on your current behavior
        if (method === 'cash') {
            // If your current checkout flow uses a GET to /checkout, trigger it:
            window.location.href = $(this).attr('href');
        } else {
            // fallback: use AJAX
            OfflineSync.sendOrQueueAjax({
                url: '/checkout',
                type: 'POST',
                method: 'POST',
                data: { method },
                success: function (response) {
                    if (response.success) {
                        showResponseMessage('Checkout successful', 'success');
                        loadCartItems(); // refresh
                    } else {
                        showResponseMessage(response.message || 'Checkout failed', 'danger');
                    }
                }
            });
        }
    });

    // Payment calculator form
    $('#payment-form').on('submit', function (e) {
        e.preventDefault();
        const cashGiven = $('#cash_given').val();

        if (!navigator.onLine) {
            // offline: compute locally using cached cart
            localforage.getItem(OfflineSync.LOCAL_CART_KEY).then(cart => {
                const total = (cart || []).reduce((s, it) => s + ((it.active_price || it.price || 0) * (it.quantity || 1)), 0);
                const balance = (cashGiven - total).toFixed(2);
                $('#payment-result').html(`<div>Total Amount: ${formatPrice(total)}<br>Balance: ${formatPrice(balance)}</div>`);
            });
            return;
        }

        // online: call server
        $.ajax({
            url: '/processPayment',
            method: 'GET',
            data: { cash_given: cashGiven },
            success: function (response) {
                $('#payment-result').html(`<div>Total Amount: ${response.total_amount}<br>Balance: ${response.balance}</div>`);
            },
            error: function (response) {
                const errorMessage = response.responseJSON?.error || 'Error processing payment';
                $('#payment-result').html(`<div class="alert alert-danger">${errorMessage}</div>`);
            }
        });
    });

    // Product search for cart
    function fetchProducts() {
        let query = $('#product-search').val();
        let category = $('#category-select').val();
        $.ajax({
            url: '/searchProductCart',
            method: 'GET',
            data: { query: query, category: category },
            success: function (data) {
                let options = '<option value="">Select a product</option>';
                data.forEach(product => {
                    options += `<option data-price="${product.price}" value="${product.id}">${product.product_name}</option>`;
                });
                $('#product-select').html(options);
            }
        });
    }
    $('#product-search').on('input', fetchProducts);
    $('#category-select').on('change', fetchProducts);

    // Initialize load
    loadCartItems();
    updateTotalAmount();
}); // end document.ready

// Periodically attempt to process offline queue every minute when online
setInterval(() => { if (navigator.onLine) OfflineSync.processQueueOnce(); }, 60000);

// Expose OfflineSync to window for debug
window.OfflineSync = OfflineSync;
