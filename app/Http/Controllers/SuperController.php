<?php

namespace App\Http\Controllers;


use App\Models\Product;
use App\Models\CartItem;
use App\Models\Cart;
use App\Models\Sales;
use App\Models\User;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Category;
use App\Models\Debt;
use App\Models\DebtItem;
use App\Models\HeldCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class SuperController extends Controller
{
    //
    public function homeUser(){
        
        return view('user.home');
    }
    public function viewSales(){
        $sales = Sales::all();
        
        return view('sales.view_sales', compact('sales'));
    }
    public function home(){
        $usertype=Auth::user()->usertype;
if($usertype=='1'){
return view('admin.home');
}
else
{
    return view('/welcome');
}
    }
    

public function show(){
    
}
    public function deleteSale($id){
        $sales = Sales::find($id);
        if ($sales) {
            $sales->delete();
            return redirect()->back()->with('success', 'Sale deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Sale not found');
        }
    }
    public function addToCartAll(Request $request)
    {
        $productIds = $request->input('product_ids');
        $cartId = session('cart_id');
    
        if (!$cartId) {
            // Create a new cart if no active cart exists
            $cart = Cart::create(['status' => 'active']);
            session(['cart_id' => $cart->id]);
            $cartId = $cart->id;
        }
    
        foreach ($productIds as $productId) {
            $product = Product::find($productId);
            if ($product) {
                if ($product->quantity > 0) {
                    CartItem::create([
                        'cart_id' => $cartId,
                        'product_id' => $product->id,
                        'product_name' => $product->product_name,
                        'description' => $product->description,
                        'price' => $product->price,
                        'discount_price' => $product->discount_price,
                        'quantity' => 1,
                    ]);
                } else {
                    return response()->json(['error' => "Product {$product->product_name} is out of stock"], 400);
                }
            }
        }
    
        return response()->json(['success' => 'Products added to cart successfully']);
    }

    public function getProductByBarcode($barcode)
{
    $product = Product::where('barcode', $barcode)->first();

    if ($product) {
        return response()->json(['status' => 'success', 'product' => $product]);
    } else {
        return response()->json(['status' => 'error', 'message' => 'Product not found.'], 404);
    }
}
public function addCartByBarcode(Request $request)
{
    try {
        $barcode = $request->input('barcode');
        $quantity = max(1, (int)$request->input('quantity', 1));

        $product = Product::where('barcode', $barcode)->first();

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found with this barcode.'
            ], 404);
        }

        if (!$product->in_stock || $product->quantity < $quantity) {
            return response()->json([
                'status' => 'error',
                'message' => 'Insufficient stock.'
            ], 400);
        }

        // Add to cart
        $cartId = session('cart_id');
        $cart = Cart::find($cartId);

        if (!$cart) {
            $cart = Cart::create();
            session(['cart_id' => $cart->id]);
        }

        $cartItem = CartItem::where('cart_id', $cart->id)
                            ->where('product_id', $product->id)
                            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;

            if ($newQuantity > $product->quantity) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Requested quantity exceeds available stock.'
                ]);
            }

            $cartItem->quantity = $newQuantity;
            $cartItem->save();
        } else {
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'product_name' => $product->product_name,
                'description' => $product->description,
                'price' => $product->price,
                'discount_price' => $product->discount_price,
            ]);
        }

        // Decrease stock
        $product->quantity -= $quantity;
        if ($product->quantity <= 0) {
            $product->in_stock = false;
        }
        $product->save();

        // Refresh cartItem with full data (in case we need to send it to frontend)
        $cartItem->refresh();

        return response()->json([
            'status' => 'success',
            'message' => 'Product added successfully to cart.',
            'cartItem' => [
                'id' => $cartItem->id,
                'product_id' => $cartItem->product_id,
                'product_name' => $cartItem->product_name,
                'description' => $cartItem->description,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->price,
                'discount_price' => $cartItem->discount_price, // assumed to be the active one
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong: ' . $e->getMessage()
        ], 500);
    }
}



public function filterSales(Request $request)
{
    $startDate = $request->input('from_date');
    $endDate = $request->input('to_date');
    
    if ($startDate && $endDate) {
        // Convert dates to include time part for accurate filtering
        $startDate = $startDate . ' 00:00:00';
        $endDate = $endDate . ' 23:59:59';

        $sales = Sales::whereBetween('updated_at', [$startDate, $endDate])->get();
        
    } else {
        return redirect()->back()->with('success', 'Please provide both start and end dates.');
    }

    if ($sales->isEmpty()) {
        return view('sales.view_sales', compact('sales'))->with('success', 'No sales records found for the selected period.');
    }

    return view('sales.view_sales', compact('sales'))->with('success','Sales Filtered Successfully');
}


    public function viewCart(){
        $product = Product::all();
        $category = Product::all();
        $customer = Customer::all();
        $cartItem = CartItem::all();
        return view('cart.cart', compact('cartItem','customer','product','category'));
    }

    public function stockReports()
    {
        $threshold = 5; // Define your threshold here
        $products = Product::where('quantity', '<=', $threshold)->get();
        return view('cart.stock_reports', compact('products'));
    }
    
    public function viewProduct(){
        if (Auth::check()) {
            $usertype = Auth::user()->usertype;
            if ($usertype != 1) {
                $product = Product::all();
                return view('products.view_product', compact('product'));
            } else {
                return redirect('login');
            }
        } else {
            return redirect('login');
        }
    }

    public function removeFromCart(Request $request, $cartItemId){
        $cartItem = CartItem::find($cartItemId);
        if ($cartItem) {
            $cartItem->delete();
            return redirect()->back()->with('success', 'Product removed');
        } else {
            return redirect()->back()->with('success', 'Product not found');
        }
    }

    public function removeProduct($id){
        $product = Product::find($id);
        $product->delete();
        return redirect()->back()->with('success', 'Product deleted successfully');
    }

    public function clearAllItems(Request $request)
{
    $cartId = session('cart_id');

    if (!$cartId) {
        return redirect()->back()->with('error', 'No active cart found.');
    }

    $cartItems = CartItem::where('cart_id', $cartId)->get();

    foreach ($cartItems as $item) {
        // Restore stock
        $product = Product::find($item->product_id);

        if ($product) {
            $product->quantity += $item->quantity;

            // Optionally re-flag it as in stock
            if (!$product->in_stock && $product->quantity > 0) {
                $product->in_stock = true;
            }

            $product->save();
        }

        // Delete the item
        $item->delete();
    }

    return redirect()->back()->with('success', 'All items have been cleared and stock restored.');
}


    public function createCart(){
        $customer = Customer::all();
        return view('cart.cart', compact('customer'));
    }
    public function addToCart(Request $request, $productId) {
        $quantity = $request->input('quantity', 1);
    
        $cart = Cart::findOrNew(session('cart_id'));
        if(!$cart->exists) {
            $cart->save();
            session(['cart_id' => $cart->id]);
        }
    
        $product = Product::findOrFail($productId);
        
        if (!$product->in_stock || $product->quantity < $quantity) {
            return redirect()->back()->with('error', 'This product is currently out of stock.');
        }
    
        $cartItem = CartItem::where('cart_id', $cart->id)
                            ->where('product_id', $productId)
                            ->first();
    
        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            if ($newQuantity > $product->quantity) {
                return redirect()->back()->with('success', 'The requested quantity exceeds the available stock.');
            }
            $cartItem->quantity = $newQuantity;
            $cartItem->save();
        } else {
            if ($quantity > $product->quantity) {
                return redirect()->back()->with('success', 'The requested quantity exceeds the available stock.');
            }
            $cartItem = new CartItem;
            $cartItem->cart_id = $cart->id;
            $cartItem->product_id = $productId;
            $cartItem->quantity = $quantity;
            $cartItem->product_name = $product->product_name;
            $cartItem->description = $product->description;
            $cartItem->price = $product->price;
            $cartItem->discount_price = $product->discount_price;
            $cartItem->save();
        }
    
        // Check if product quantity is below threshold
        $remainingQuantity = $product->quantity - $cartItem->quantity;
        if ($remainingQuantity <= 0) {
            return redirect()->back()->with('error', 'This product is currently out of stock.');
        } elseif ($remainingQuantity <= 5) {
            return redirect()->back()->with('success', 'Product added to cart. Note: Only ' . $remainingQuantity . ' items remaining in stock.');
        }
    
        return redirect()->back()->with('success', 'Product added to cart');
    }
    
    public function searchProductCart(Request $request)
    {
        $query = $request->input('query');
        $category = $request->input('category');

        $products = Product::where('product_name', 'LIKE', "%{$query}%")
                            ->where('in_stock', true);
        
        if ($category) {
            $products->where('category', $category);
        }

        $products = $products->get(['id', 'product_name']);
        
        return response()->json($products);
    }

    public function relatedProducts(Request $request)
    {
        $productId = $request->input('product_id');
        $product = Product::findOrFail($productId);
        
        $relatedProducts = Product::where('product_name', 'LIKE', "%{$product->product_name}%")
                                  ->where('in_stock', true)
                                  ->get(['id', 'product_name']);
        
        return response()->json($relatedProducts);
    }
    public function getCartItem($productId)
{
    $cartId = session('cart_id');
    $cartItem = CartItem::where('cart_id', $cartId)
                       ->where('product_id', $productId)
                       ->first();
    
    return response()->json($cartItem);
}

    public function addCart(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validate request
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1'
            ]);

            $productId = $request->input('product_id');
            $quantity = max(1, (int)$request->input('quantity', 1));

            // Find or create cart
            $cartId = session('cart_id');
            $cart = Cart::find($cartId);
            
            if (!$cart) {
                $cart = Cart::create();
                session(['cart_id' => $cart->id]);
            }

            // Get product and check stock
            $product = Product::find($productId);

            if (!$product) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Product not found.',
                    'status' => 'error'
                ], 404);
            }

            if (!$product->in_stock || $product->quantity < $quantity) {
                DB::rollBack();
                return response()->json([
                    'message' => 'This product is currently out of stock or has insufficient quantity.',
                    'status' => 'error'
                ], 400);
            }

            // Find or create cart item
            $cartItem = CartItem::where('cart_id', $cart->id)
                              ->where('product_id', $productId)
                              ->first();

            if ($cartItem) {
                // Update existing cart item
                $newQuantity = $cartItem->quantity + $quantity;
                
                if ($newQuantity > $product->quantity) {
                    DB::rollBack();
                    return response()->json([
                        'message' => 'The requested quantity exceeds the available stock.',
                        'status' => 'error'
                    ], 400);
                }

                $cartItem->quantity = $newQuantity;
                $cartItem->save();
            } else {
                // Create new cart item
                if ($quantity > $product->quantity) {
                    DB::rollBack();
                    return response()->json([
                        'message' => 'The requested quantity exceeds the available stock.',
                        'status' => 'error'
                    ], 400);
                }

                $cartItem = new CartItem([
                    'cart_id' => $cart->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'product_name' => $product->product_name,
                    'description' => $product->description,
                    'price' => $product->price,
                    'discount_price' => $product->discount_price
                ]);
                
                $cartItem->save();
            }

            // Update product quantity - SQLite compatible way
            $product->quantity = $product->quantity - $quantity;
            if ($product->quantity <= 0) {
                $product->in_stock = false;
            }
            $product->save();

            DB::commit();

            // Prepare response message based on remaining stock
            $remainingQuantity = $product->quantity;
            $message = 'Product added to cart successfully.';
            
            if ($remainingQuantity <= 0) {
                $message = 'Product added to cart. This product is now out of stock.';
            } elseif ($remainingQuantity <= 5) {
                $message = "Product added to cart. Note: Only {$remainingQuantity} items remaining in stock.";
            }

            // Calculate total amount - SQLite compatible way
            $totalAmount = $this->calculateTotalAmount($cart->id);

            // Get fresh cart item data
            $cartItem = CartItem::find($cartItem->id);

            return response()->json([
                'message' => $message,
                'status' => 'success',
                'cartItem' => $cartItem,
                'remainingQuantity' => $remainingQuantity,
                'total_amount' => $totalAmount,
                'cart_count' => $this->getCartCount($cart->id)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in addCart: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'An error occurred while adding the product to cart.',
                'status' => 'error'
            ], 500);
        }
    }



    private function getCartCount($cartId)
    {
        return CartItem::where('cart_id', $cartId)->sum('quantity');
    }

    public function getCartItems()
    {
        try {
            $cartId = session('cart_id');
            if (!$cartId) {
                return response()->json([
                    'cartItems' => [],
                    'total_amount' => 0
                ]);
            }

            $cartItems = CartItem::where('cart_id', $cartId)->get();
            $totalAmount = $this->calculateTotalAmount($cartId);

            return response()->json([
                'cartItems' => $cartItems,
                'total_amount' => $totalAmount
            ]);

        } catch (\Exception $e) {
            Log::error('Error in getCartItems: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'An error occurred while fetching cart items.',
                'status' => 'error'
            ], 500);
        }
    }
    public function updateCartItem(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            
            $cartItem = CartItem::findOrFail($id);
            $product = Product::find($cartItem->product_id);
            
            // Calculate quantity difference
            $newQuantity = (int)$request->input('quantity', 0);
            $quantityDifference = $newQuantity - $cartItem->quantity;
            
            // Check if new quantity is valid
            if ($quantityDifference > 0 && $product->quantity < $quantityDifference) {
                return response()->json([
                    'message' => 'Not enough stock available',
                    'status' => 'error'
                ], 400);
            }
            
            // Update cart item
            $cartItem->quantity = $newQuantity;
            $cartItem->active_price = $request->input('active_price');
            $cartItem->save();
            
            // Update product quantity
            $product->quantity -= $quantityDifference;
            $product->in_stock = $product->quantity > 0;
            $product->save();
            
            DB::commit();
    
            // Get fresh cart item data
            $cartItem = CartItem::find($cartItem->id);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Cart updated successfully',
                'cartItem' => $cartItem,
                'total_amount' => $this->calculateTotalAmount(session('cart_id'))
            ]);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error updating cart item',
                'status' => 'error'
            ], 500);
        }
    }

    public function calculateTotalAmount(){
        $cartId = session('cart_id');
        $totalAmount = 0;

        if ($cartId) {
            $cartItems = CartItem::where('cart_id', $cartId)->get();
            foreach ($cartItems as $cartItem) {
                $product = Product::find($cartItem->product_id);
                $price = $cartItem->active_price ?? $product->price;
                $totalAmount += $price * $cartItem->quantity;
            }
        }

        return response()->json(['total_amount' => $totalAmount]);
    }

    public function processPayment(Request $request)
    {
        $cartId = session('cart_id');
        $totalAmount = 0;
        $cashGiven = $request->input('cash_given');
    
        if ($cartId) {
            $cartItems = CartItem::where('cart_id', $cartId)->get();
            foreach ($cartItems as $cartItem) {
                $product = Product::find($cartItem->product_id);
                $price = $cartItem->active_price ?? $product->price;
                $totalAmount += $price * $cartItem->quantity;
            }
        }
    
        if ($cashGiven < $totalAmount) {
            return response()->json(['error' => 'Insufficient cash provided.'], 400);
        }
    
        $balance = $cashGiven - $totalAmount;
    
        // Here, you can add logic to clear the cart or save the transaction
    
        return response()->json(['total_amount' => $totalAmount, 'balance' => $balance]);
    }
    

    public function updateCart(Request $request, $cartItemId)
    {
        try {
            DB::beginTransaction();
            
            $cartItem = CartItem::findOrFail($cartItemId);
            $product = Product::find($cartItem->product_id);
            
            if (!$product) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product not found'
                ], 404);
            }
    
            // Calculate quantity difference
            $newQuantity = (int)$request->input('quantity', 0);
            $quantityDifference = $newQuantity - $cartItem->quantity;
            
            // Check stock availability
            if ($quantityDifference > 0 && $product->quantity < $quantityDifference) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Not enough stock available'
                ], 400);
            }
            
            // Update cart item
            $cartItem->quantity = $newQuantity;
            $cartItem->active_price = $request->input('active_price');
            $cartItem->save();
            
            // Update product stock
            $product->quantity -= $quantityDifference;
            $product->in_stock = $product->quantity > 0;
            $product->save();
            
            DB::commit();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Cart updated successfully',
                'cartItem' => $cartItem,
                'total_amount' => $this->calculateTotalAmount(session('cart_id'))
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Error updating cart item'
            ], 500);
        }
    }
    public function holdCart(){
        $cartId = session('cart_id');
        $cartItems = CartItem::where('cart_id', $cartId)->get();

        if ($cartItems->isNotEmpty()) {
            foreach ($cartItems as $cartItem) {
                HeldCart::create([
                    'cart_id' => $cartId,
                    'product_name' => $cartItem->product_name,
                    'product_id'=>$cartItem->product_id,
                    'description' => $cartItem->description,
                    'price' => $cartItem->price,
                    'discount_price' => $cartItem->discount_price,
                    'quantity' => $cartItem->quantity,
                    'active_price' => $cartItem->active_price,
                ]);
            }

            CartItem::where('cart_id', $cartId)->delete();
            session()->forget('cart_id');

            return redirect()->back()->with('success','Sale is on hold');
        }

        return redirect()->back()->with('success','No items in cart to hold');
    }

    public function resumeCart($heldCartId){
        $currentCartId = session('cart_id');
        if ($currentCartId) {
            return redirect('/viewCart')->with('success', 'You already have an active cart. Please hold or clear it before resuming another cart.');
        }

        $heldCarts = HeldCart::where('cart_id', $heldCartId)->get();

        if ($heldCarts->isNotEmpty()) {
            $cart = Cart::create(['status' => 'active']);
            session(['cart_id' => $cart->id]);

            foreach ($heldCarts as $heldCart) {
                CartItem::create([
                    'cart_id' => $cart->id, // Use the newly created cart ID
                    'product_id'=>$heldCart->product_id,
                    'product_name' => $heldCart->product_name,
                    'description' => $heldCart->description,
                    'price' => $heldCart->price,
                    'discount_price' => $heldCart->discount_price,
                    'quantity' => $heldCart->quantity,
                    'active_price' => $heldCart->active_price,
                ]);

                $heldCart->delete();
            }

            return redirect('/viewCart')->with('success', 'Sale resumed successfully');
        }

        return redirect('/viewProduct')->with('success', 'Unable to resume cart'); 
    }

    public function getHeldCarts(Request $request)
{
    $heldCart = HeldCart::paginate(2);
    return view('cart.heldcarts', compact('heldCart'));
}



    public function deleteCart($heldCartId){
        $heldCarts = HeldCart::where('cart_id', $heldCartId)->get();
        if ($heldCarts->isNotEmpty()) {
            foreach ($heldCarts as $heldCart) {
                $heldCart->delete();
            }
            return redirect()->back()->with('success','Cart deleted successfully');
        }
        return redirect()->back()->with('success','Unable to delete cart');
    }

    // Other existing methods...

 public function checkout(Request $request)
{
    $cartId = session('cart_id');

    if (!$cartId) {
        return redirect()->back()->with('error', 'No active cart found.');
    }

    $cart = Cart::find($cartId);
    if (!$cart) {
        return redirect()->back()->with('error', 'Cart not found.');
    }

    $cartItems = CartItem::where('cart_id', $cartId)->get();

    if ($cartItems->isEmpty()) {
        return redirect()->back()->with('error', 'Cart is empty.');
    }

    $productIds = $cartItems->pluck('product_id')->unique();
    $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

    $salesToInsert = [];
    $cartTotal = 0;

    foreach ($cartItems as $cartItem) {
        $product = $products[$cartItem->product_id] ?? null;
        if (!$product) continue;

        $price = $cartItem->active_price ?? $product->price;
        $lineTotal = $price * $cartItem->quantity;
        $cartTotal += $lineTotal;

        // Update product inventory
        if ($product->quantity >= $cartItem->quantity) {
            $product->quantity -= $cartItem->quantity;
        } else {
            $product->quantity = 0;
            $product->in_stock = false;
        }
        $product->save();

        $salesToInsert[] = [
            'cart_id'        => $cartItem->cart_id,
            'product_name'   => $cartItem->product_name,
            'description'    => $cartItem->description,
            'price'          => $cartItem->price,
            'active_price'   => $cartItem->active_price,
            'discount_price' => $cartItem->discount_price,
            'quantity'       => $cartItem->quantity,
            'total'          => $lineTotal, // Correct: per-product total
            'business_id'    => $cart->business_id,
            'updated_at'     => now(),
            'created_at'     => now(),
        ];
    }

    // Bulk insert sales
    if (!empty($salesToInsert)) {
        Sales::insert($salesToInsert);
    }

    // Clear the cart
    CartItem::where('cart_id', $cartId)->delete();

    // Mark cart as checked out and optionally save total amount
    $cart->checked_out = true;
    //$cart->total_amount = $cartTotal; // Optional: only if `total_amount` exists in your `carts` table
    $cart->save();

    // Clear session
    session()->forget('cart_id');

    return redirect()->back()->with('success', 'Product Sold!');
}

public function addByBarcode(Request $request)
{
    $barcode = $request->barcode;

    $product = Product::where('barcode', $barcode)->first();

    if (!$product) {
        return response()->json(['message' => 'Product not found'], 404);
    }

    CartItem::create([
        'cart_id' => session('cart_id') ?? Cart::create(['business_id' => auth()->user()->business_id])->id,
        'product_id' => $product->id,
        'product_name' => $product->product_name,
        'description' => $product->description,
        'price' => $product->price,
        'quantity' => 1,
    ]);

    return response()->json(['message' => 'Product added to cart']);
}


    public function deleteCartItem($id)
    {
        try {
            DB::beginTransaction();
            
            $cartItem = CartItem::findOrFail($id);
            $product = Product::find($cartItem->product_id);
            
            if ($product) {
                // Return the quantity back to product stock
                $product->quantity += $cartItem->quantity;
                $product->in_stock = $product->quantity > 0;
                $product->save();
            }
            
            // Delete the cart item
            $productId = $cartItem->product_id;
            $cartItem->delete();
            
            DB::commit();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Item removed from cart',
                'product_id' => $productId
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Error removing item from cart'
            ], 500);
        }
    }
    // Customer Methods
    public function viewCustomer()
    {
        $customers = Customer::all();
        $debts = Debt::all();
        return view('customers.view_customer', compact('customers','debts'));
    }

    public function createCustomer(){

        return view('customers.create_customer');
    }

    public function storeCustomer(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:15',
            'location' => 'nullable|string|max:255',
            'total_debt' => 'nullable|numeric'

        ]);

        Customer::create($request->all());

        return redirect()->back()->with('success', 'Customer Created Successfully');
    }

    public function editCustomer($id)
    {
        $customer = Customer::findOrFail($id);
        
        return view('customers.edit_customer', compact('customer'));
    }

    public function updateCustomer(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $request->validate([

            'customer_name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:15',
            'location' => 'nullable|string|max:255',
            'total_debt' => 'nullable|numeric'

        ]);

        $customer->update($request->all());

        return redirect('/viewCustomer')->with('success', 'Customer Updated Successfully');
    }

    public function destroyCustomer($id)
    {
        $customer = Customer::find($id);
        $customer->delete();

        return redirect()->back()->with('success', 'Customer Deleted Successfully');
    }

    public function searchCustomer(Request $request){
        $searchCustomer = $request->searchCustomer;
        $customers= Customer::where(function($query) use ($searchCustomer){
            $query->where('customer_name','like',"%$searchCustomer%")
            ->orWhere('phone_number','like',"$searchCustomer");
            
        })->get();
        return view('customers.view_customer',compact('customers'));
    }
    // Supplier Methods
    public function viewSupplier()
    {
        $suppliers = Supplier::all();
        return view('suppliers.view_supplier', compact('suppliers'));
    }
public function createSupplier(){
    return view('suppliers.create_supplier');
}
    public function storeSupplier(Request $request)
    {
        $request->validate([
            'supplier_name' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'phone_number' => 'nullable|string|max:15',
            'description' => 'nullable|string',
            'status' => 'nullable|string',
            'location' => 'nullable|string|max:255',
        ]);

        Supplier::create($request->all());

        return redirect()->back()->with('success', 'Supplier Created Successfully');
    }

    public function editSupplier($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('suppliers.edit_supplier', compact('supplier'));
    }

    public function updateSupplier(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        $request->validate([
'supplier_name' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'phone_number' => 'nullable|string|max:15',
            'description' => 'nullable|string',
            'status' => 'nullable|string',
            'location' => 'nullable|string|max:255'
        ]);
        $supplier->update($request->all());

        return redirect('/viewSupplier')->with('success', 'Supplier Updated Successfully');
    }

    public function destroySupplier($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return redirect()->back()->with('success', 'Supplier Deleted Successfully');
    }
    public function viewDebtItems($debtId)
    {
        $debt = Debt::with('items', 'customer')->find($debtId);
        if (!$debt) {
            return redirect()->back()->with('error', 'Debt not found.');
        }
        return view('cart.view_debtitems', compact('debt'));
    }
    
    // Debts Logic
    public function addToDebt(Request $request)
{
    $cartId = session('cart_id');
    $totalAmount = 0;
    $cartItems = collect(); // Initialize an empty collection

    if ($cartId) {
        $cartItems = CartItem::where('cart_id', $cartId)->get();
        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('success', 'Cart is empty. Cannot add to debt.');
        }

        foreach ($cartItems as $cartItem) {
            $product = Product::find($cartItem->product_id);
            $price = $cartItem->active_price ?? $product->price;
            $totalAmount += $price * $cartItem->quantity;
        }
    } else {
        return redirect()->back()->with('success', 'Cart is not found. Cannot add to debt.');
    }

    $customerId = $request->input('customer_id'); // Ensure we're using the correct input name
    $customer = Customer::find($customerId);
    if (!$customer) {
        return redirect()->back()->with('success', 'Customer not found');
    }

    $debt = new Debt();
    $debt->customer_id = $customer->id;
    $debt->amount = $totalAmount;
    $debt->status = 0;
    $debt->save();

    // Save each cart item to the debts table
    foreach ($cartItems as $cartItem) {
        $debt->items()->create([
            'product_name' => $cartItem->product_name,
            'description' => $cartItem->description,
            'price' => $cartItem->price,
            'active_price' => $cartItem->active_price,
            'quantity' => $cartItem->quantity,
        ]);

        // Reduce the quantity in stock
        $product = Product::find($cartItem->product_id);
        if ($product) {
            $product->quantity -= $cartItem->quantity;
            $product->save();
        }

        // Save to Sales table
        $sale = new Sales();
        $sale->cart_id = $cartItem->cart_id;
        $sale->product_name = $cartItem->product_name;
        $sale->description = $cartItem->description;
        $sale->price = $cartItem->price;
        $sale->quantity = $cartItem->quantity;
        $sale->total = $totalAmount;
        $sale->save();
    }

    CartItem::where('cart_id', $cartId)->delete();
    session()->forget('cart_id');

    return redirect()->back()->with('success', 'Items added to debt');
}

public function searchSupplier(Request $request){
    $searchSupplier = $request->searchSupplier;
    $suppliers= Supplier::where(function($query) use ($searchSupplier){
        $query->where('supplier_name','like',"%$searchSupplier%")
        ->orWhere('phone_number','like',"$searchSupplier");
        
    })->get();
    return view('suppliers.view_supplier',compact('suppliers'));
}

public function searchSalesCart(Request $request){
    $searchSalesCart = $request->searchSalesCart;
    $sales= Sales::where(function($query) use ($searchSalesCart){
        $query->where('product_name','like',"%$searchSalesCart%")
        ->orWhere('updated_at','like',"$searchSalesCart");
        
    })->get();
    return view('sales.view_sales',compact('sales'));
}
public function searchProduct(Request $request){
    $searchProduct = $request->searchProduct;
    $product= Product::where(function($query) use ($searchProduct){
        $query->where('product_name','like',"%$searchProduct%")
        ->orWhere('description','like',"$searchProduct")
        ->orWhere('category','like',"$searchProduct%");
        
    })->get();
    return view('products.view_product',compact('product'))->with('success','Product search successful');
        }

        public function searchDebt(Request $request){
            $searchDebt = $request->searchDebt;
            $debts= Debt::where(function($query) use ($searchDebt){
                $query->where('status','like',"%$searchDebt%");
            })->get();
            return view('cart.view_debts',compact('debts'));
                }
            
    public function viewDebts()
    {
        $debts = Debt::paginate(5);
        return view('cart.view_debts', compact('debts'));
    }

    public function settleDebt(Request $request)
{
    $debt = Debt::findOrFail($request->debt_id);
    $amount = $request->input('amount');

    if ($amount > $debt->amount) {
        return redirect()->back()->with('success', 'The amount entered is more than the debt available');
    }
   

    $debt->amount -= $amount;
    if ($debt->amount <= 0) {
        $debt->status = 1;
        $debt->items()->delete(); // Clear debt items
    }
    $debt->save();

    // Update customer's total debt
    $debt->updateCustomerTotalDebt();

    if($amount < $debt->amount){
        return redirect()->back()->with('success','Dedt is partially paid');
    }else
    return redirect()->back()->with('success', 'Debt settled');
}
}
