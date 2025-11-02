<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Sales;
use App\Models\Supplier;
use App\Models\SupplierInvoice;
use App\Models\SupplierInvoiceItem;
use App\Models\SupplierProduct;
use App\Models\ProductImportLog;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function viewImportLogs()
    {
        $logs = ProductImportLog::where('business_id', auth()->user()->business_id)
                    ->latest()
                    ->paginate(10);
    
        return view('admin.product_import_logs', compact('logs'));
    }
    

    public function view_category()
    {
        $data=Category::all();
        return view('admin.category',compact('data'));
    }
    public function add_category(Request $request)
    {
$request->validate([
'category'=>'required|string|max:255'
]);
Category::create($request->all());

return redirect()->back();
    }
    public function delete_category($id){
        $data=Category::find($id);
        $data->delete();
        return redirect()->back();
    }
    public function view_product(){
        $product=Product::all();
        $category=Category::all();
        return view('admin.product',compact('product','category'));
    }
   
        public function add_product(Request $request)
        {
   $request->validate([
'product_name'     => 'required|string|max:255',
    'description'      => 'required|string',
    'cost_price'            => 'required|numeric|min:0',
    'price'            => 'required|numeric|min:0',
    'discount_price'   => 'nullable|numeric|min:0|lte:price',
    'quantity'         => 'required|integer|min:0',
    'barcode'            => 'nullable|string|max:100',
    'category'         => 'required|string|max:100'
   ]);
   
    $product= new product;
    $product->product_name=$request->product_name;
    $product->description=$request->description;
     $product->cost_price=$request->cost_price;
    $product->price=$request->price;
    $product->discount_price=$request->discount_price;
    $product->quantity=$request->quantity;
    $product->barcode=$request->barcode;
    $product->category=$request->category;
    $product->save();

    
    return redirect()->back()->with('message','product added successfully');
        }
       
       public function homeAdmin()
{
    $totalProducts = Product::count();
    $totalCustomers = Customer::count();

    // Sum today's sales
    $todaySalesTotal = Sales::whereDate('created_at', Carbon::today())->where('status', 'active')->sum('total');

    // Sum all-time total sales
    $totalSales = Sales::where('status', 'active')->sum('total');

    // Sales data for last 7 days
    $salesData = Sales::selectRaw('DATE(created_at) as date, SUM(total) as total')
        ->where('created_at', '>=', Carbon::now()->subDays(7))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

    $labels = $salesData->pluck('date')->map(function ($date) {
        return Carbon::parse($date)->format('M d');
    });

    $totals = $salesData->pluck('total');

    return view('admin.home', compact(
        'totalProducts',
        'totalCustomers',
        'todaySalesTotal',
        'totalSales',
        'labels',
        'totals'
    ));
}

        public function show_product()
        {
            $show=Product::all();
            return view('admin.show_product',compact('show'));
        }
        public function delete_product($id)
        {
            $show=product::find($id);
            $show->delete();
            return redirect()->back();
        }
        
        public function clearAllproducts(Request $request)
        {
            $businessId = auth()->user()->business_id;
            // Delete all items from the products table
            DB::table('products') ->where('business_id', $businessId)->delete();
    
            return redirect()->back()->with('success', 'All items have been cleared from products');
        }


        public function documentation(){
            
            return view('admin.documentation');
        }
        public function filterSalesAdmin(Request $request)
        {
            $startDate = $request->input('from_date');
            $endDate = $request->input('to_date');
            
            if ($startDate && $endDate) {
                // Convert dates to include time part for accurate filtering
                $startDate = $startDate . ' 00:00:00';
                $endDate = $endDate . ' 23:59:59';
        
                $sales = Sales::whereBetween('updated_at', [$startDate, $endDate])->get();
            } else {
                return redirect()->back()->with('error', 'Please provide both start and end dates.');
            }
        
            if ($sales->isEmpty()) {
                return view('admin.sales', compact('sales'))->with('error', 'No sales records found for the selected period.');
            }
        
            return view('admin.sales', compact('sales'));
        }
        
        // Method to show the edit form
    public function edit_product($id)
    {
        // Retrieve the product from the database
        $product = Product::findOrFail($id);
        $category=Category::all();
        

        // Pass the product to the view
        return view('admin.edit_product',compact('product','category'));
    }

     public function stockReports_admin()
    {
        $threshold = 5; // Define your threshold here
        $products = Product::where('quantity', '<=', $threshold)->get();
        return view('admin.stock_reports', compact('products'));
    }
    

    // Method to handle form submission and update the product

public function update_product(Request $request, $id)
{
    // Validate the submitted form data
    $request->validate([
        'product_name'    => 'required|string|max:255',
        'description'     => 'nullable|string',
        'cost_price'      => 'required|numeric|min:0',
        'price'           => 'required|numeric|min:0',
        'discount_price'  => 'nullable|numeric|min:0|lte:price',
        'quantity'        => 'required|integer|min:0',
        'barcode'         => 'nullable|string|max:100',
        'category'        => 'required|string|max:100',
        // Add validation rules for other fields as needed
    ]);

    // Retrieve the product from the database
    $product = Product::findOrFail($id);

    // Update the product with the new information
    $product->product_name   = $request->product_name;
    $product->description    = $request->description;
    $product->cost_price     = $request->cost_price;
    $product->price          = $request->price;
    $product->discount_price = $request->discount_price;
    $product->quantity       = $request->quantity;
    $product->barcode        = $request->barcode;
    $product->category       = $request->category;

    $product->save();

    // Redirect the user to a relevant page
    return redirect()->back()->with('message', 'Product updated successfully.');
}
    public function importProducts(Request $request)
{
    $validator = Validator::make($request->all(), [
        'file' => 'required|mimes:csv,txt|max:2048',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    try {
        $path = $request->file('file')->getRealPath();
        $productsArray = array_map('str_getcsv', file($path));

        if (empty($productsArray)) {
            return redirect()->back()->with('error', 'The uploaded file is empty or invalid.');
        }

        $header = array_map('trim', array_shift($productsArray));
        $requiredHeaders = ['product_name', 'description','cost_price', 'price', 'discount_price', 'quantity','barcode', 'in_stock', 'category'];

        if ($header !== $requiredHeaders) {
            return redirect()->back()->with('error', 'The uploaded file does not have the required headers.');
        }

        $businessId = auth()->user()->business_id;
        $admin = auth()->user();

        $imported = 0;
        $updated = 0;

        foreach ($productsArray as $productRow) {
            $data = array_combine($header, $productRow);

            $productValidator = Validator::make($data, [
                'product_name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'cost_price' => 'nullable|numeric',
                'price' => 'required|numeric',
                'discount_price' => 'nullable|numeric',
                'in_stock' => 'required|numeric',
                'quantity' => 'required|integer',
                'barcode'=>'nullable|numeric',
                'category' => 'required|string|max:255',
            ]);

            if ($productValidator->fails()) {
                return redirect()->back()->with('error', 'Invalid product data in file.');
            }

            $existingProduct = Product::where('product_name', $data['product_name'])
                ->where('business_id', $businessId)
                ->first();

 $costPrice = isset($data['cost_price']) && is_numeric($data['cost_price'])
                ? (float) $data['cost_price']
                : ($existingProduct->cost_price ?? 0);

            if ($existingProduct) {
                $existingProduct->quantity += (int) $data['quantity'];
                 $existingProduct->cost_price = $costPrice;
                $existingProduct->price = $data['price'];
                $existingProduct->discount_price = $data['discount_price'];
                $existingProduct->in_stock = $data['in_stock'];
                $existingProduct->save();
                $updated++;
            } else {
                Product::create([
                    'product_name' => $data['product_name'],
                    'description' => $data['description'],
                     'cost_price' => $costPrice,
                    'price' => $data['price'],
                    'discount_price' => $data['discount_price'],
                    'quantity' => $data['quantity'],
                    'barcode' => $data['barcode'],
                    'in_stock' => $data['in_stock'],
                    'category' => $data['category'],
                    'business_id' => $businessId,
                ]);
                $imported++;
            }

            // Log the import
            ProductImportLog::create([
                'business_id' => $businessId,
                'product_name' => $data['product_name'],
                'quantity_added' => $data['quantity'],
                 'cost_price' => $costPrice,
                'imported_by' => $admin->name,
            ]);
        }

        return redirect()->back()->with('success', "Products imported. New: $imported, Updated: $updated.");
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
    }
}
   // Step 1: Export and store the file
public function exportSales()
{
    try {
        $sales = Sales::orderBy('created_at', 'desc')->get();

        $header = ['Sale ID', 'Product Name', 'Description', 'Price', 'Active Price', 'Quantity', 'Total', 'Date'];
        $csvContent = implode(',', $header) . "\n";

        foreach ($sales as $sale) {
            $csvContent .= implode(',', [
                $sale->id,
                $this->escapeCsv($sale->product_name),
                $this->escapeCsv($sale->description),
                $sale->price,
                $sale->active_price ?? 'N/A',
                $sale->quantity,
                $sale->total,
                $sale->created_at,
            ]) . "\n";
        }

        $fileName = 'sales_' . now()->format('Y_m_d_H_i_s') . '.csv';
        Storage::disk('public')->put($fileName, $csvContent);

        // Redirect to a dedicated download route
        return redirect()->route('download.sales.csv', ['filename' => $fileName])
                         ->with('success', 'Sales exported successfully.');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'An error occurred while exporting the sales data.');
    }
}

private function escapeCsv($value)
{
    return '"' . str_replace('"', '""', $value) . '"';
}

public function downloadCsv($filename)
{
    $filePath = storage_path('app/public/' . $filename);

    if (!file_exists($filePath)) {
        return redirect()->back()->with('error', 'The file could not be found.');
    }

    // Return download and auto-delete the file after send
    return response()->download($filePath)->deleteFileAfterSend(true);
}


    public function exportProductImportLogs()
{
    try {
        $logs = ProductImportLog::all();

        $csvContent = '';
        $header = ['Log ID', 'Product Name', 'Quantity Imported', 'Action', 'Imported By', 'Date'];
        $csvContent .= implode(',', $header) . "\n";

        foreach ($logs as $log) {
            $csvContent .= implode(',', [
                $log->id,
                $log->product_name,
                $log->quantity_added,
                ucfirst($log->action),
                $log->imported_by,
                $log->created_at,
            ]) . "\n";
        }

        // Generate filename
        $fileName = 'product_import_logs_' . date('Y_m_d_H_i_s') . '.csv';
        $filePath = storage_path('app/public/' . $fileName);

        // Store the file
        Storage::disk('public')->put($fileName, $csvContent);

        // Return as download
        return response()->download($filePath)->deleteFileAfterSend(true);
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'An error occurred while exporting the import logs.');
    }
}
    
    public function search(Request $request){
$search = $request->search;
$show = Product::where(function($query) use ($search){
    $query->where('product_name','like',"%$search%")
    ->orWhere('description','like',"$search")
    ->orWhere('category','like',"$search%");
    
})->get();
 $message = $show->count() > 0
        ? 'Search successful — found '
        : 'No products found matching your search.';
return view('admin.show_product',compact('show'));
    }
   
    public function searchSales(Request $request){
        $searchSales = $request->searchSales;
        $sales = Sales::where(function($query) use ($searchSales){
            $query->where('product_name','like',"%$searchSales%")
            ->orWhere('description','like',"$searchSales")
            ->orWhere('updated_at','like',"$searchSales%");
            
        })->get();
        return view('admin.sales',compact('sales'));
            }
            public function restoreSale($id)
{
    $sale = Sales::findOrFail($id);

    if ($sale->status === 'restored') {
        return redirect()->back()->with('error', 'This sale has already been restored.');
    }

    // Restore product stock
    $product = Product::where('product_name', $sale->product_name)->first();
    if ($product) {
        $product->quantity += $sale->quantity;
        $product->in_stock = true;
        $product->save();
    }

    // Mark sale as restored
    $sale->status = 'restored';
    $sale->restored_at = now();
    $sale->save();

    return redirect()->back()->with('success', 'Sale restored successfully and stock updated.');
}

 // 🔹 View all products for a specific supplier
    public function showSupplierProducts($supplierId)
    {
        $supplier = Supplier::findOrFail($supplierId);
        $products = SupplierProduct::where('supplier_id', $supplierId)->get();

        return view('admin.supplier.products.index', compact('supplier', 'products'));
    }

    // 🔹 Show create form for supplier product
    public function createSupplierProduct($supplierId)
    {
        $supplier = Supplier::findOrFail($supplierId);
        $systemProducts = Product::where('business_id', auth()->user()->business_id)->get();

        return view('admin.supplier.products.create', compact('supplier', 'systemProducts'));
    }

    // 🔹 Store new supplier product
    public function storeSupplierProduct(Request $request, $supplierId)
    {
        $request->validate([
            'supplier_product_name' => 'required|string|max:255',
            'barcode' => 'nullable|string|max:255',
            'default_cost_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'linked_product_id' => 'nullable|exists:products,id',
        ]);

        $supplier = Supplier::findOrFail($supplierId);

        SupplierProduct::create([
            
            'supplier_id' => $supplier->id,
            'business_id' => $supplier->business_id,
            'supplier_product_name' => $request->supplier_product_name,
            'barcode' => $request->barcode,
            'default_cost_price' => $request->default_cost_price,
            'description' => $request->description,
            'linked_product_id' => $request->linked_product_id,
        ]);

        return redirect()->route('suppliers.products', $supplier->id)->with('success', 'Supplier product added successfully.');
    }

    // 🔹 Edit supplier product
    public function editSupplierProduct($id)
    {
        $product = SupplierProduct::findOrFail($id);
        $supplier = Supplier::findOrFail($product->supplier_id);
        $systemProducts = Product::where('business_id', auth()->user()->business_id)->get();

        return view('admin.supplier.products.edit', compact('product', 'supplier', 'systemProducts'));
    }

    // 🔹 Update supplier product
    public function updateSupplierProduct(Request $request, $id)
    {
        $product = SupplierProduct::findOrFail($id);

        $request->validate([
            'supplier_product_name' => 'required|string|max:255',
            'barcode' => 'nullable|string|max:255',
            'default_cost_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'linked_product_id' => 'nullable|exists:products,id',
        ]);

        $product->update([
            'supplier_product_name' => $request->supplier_product_name,
            'barcode' => $request->barcode,
            'default_cost_price' => $request->default_cost_price,
            'description' => $request->description,
            'linked_product_id' => $request->linked_product_id,
        ]);

        return redirect()->route('suppliers.products', $product->supplier_id)->with('success', 'Supplier product updated successfully.');
    }

    // 🔹 Delete supplier product
    public function destroySupplierProduct($id)
    {
        $product = SupplierProduct::findOrFail($id);
        $supplierId = $product->supplier_id;
        $product->delete();

        return redirect()->route('suppliers.products', $supplierId)->with('success', 'Supplier product deleted successfully.');
    }
public function viewSupplier(){
    $suppliers=Supplier::all();
    return view('admin.supplier.view-supplier',compact('suppliers')); 
}
    public function view_sales(){
        $sales=Sales::all();
        return view('admin.sales',compact('sales'));
    }
    public function show_orders(){
        $orders=Order::all();
        return view('admin.show_orders',compact('orders'));
    }


    public function createSupplierInvoice($supplierId)
{
    $businessId = auth()->user()->business_id;
 $admin = auth()->user();
    // Create a new draft invoice if not already existing
    $invoice = SupplierInvoice::firstOrCreate([
        'supplier_id' => $supplierId,
        'business_id' => $businessId,
        'status' => 'draft',
    ], [
        'invoice_number' => 'INV-' . strtoupper(uniqid()),
        'date' => now(),
        'created_by' => $admin->name,
    ]);

    return redirect()->route('suppliers.products', $supplierId)
        ->with('success', 'Draft invoice created. You can now add products to it.');
}

public function addToDraftInvoice(Request $request, $supplierProductId)
{
    $businessId = auth()->user()->business_id;
    $supplierProduct = \App\Models\SupplierProduct::findOrFail($supplierProductId);
    $supplierId = $supplierProduct->supplier_id;

    // Find or create draft invoice for this supplier
    $invoice = SupplierInvoice::firstOrCreate([
        'supplier_id' => $supplierId,
        'business_id' => $businessId,
        'status' => 'draft',
    ], [
        'invoice_number' => 'INV-' . strtoupper(uniqid()),
        'date' => now(),
    ]);

    // Check if product already added
    $existingItem = SupplierInvoiceItem::where('supplier_invoice_id', $invoice->id)
        ->where('supplier_product_id', $supplierProductId)
        ->first();

    if ($existingItem) {
        $existingItem->quantity += 1; // Increment if re-added
        $existingItem->save();
    } else {
        SupplierInvoiceItem::create([
            'supplier_invoice_id' => $invoice->id,
            'supplier_product_id' => $supplierProductId,
            'product_name' => $request->name ?? $supplierProduct->supplier_product_name,
            'cost_price' => $request->price ?? $supplierProduct->default_cost_price,
            'quantity' => 1,
        ]);
    }

    return redirect()->route('suppliers.invoices.draft', $invoice->id)
        ->with('success', 'Product added to draft invoice.');
}

public function viewDraftInvoice($supplierId)
{
    // Find the latest (or only) draft invoice for this supplier
    $invoice = SupplierInvoice::with(['items.supplierProduct', 'supplier'])
        ->where('supplier_id', $supplierId)
        ->where('status', 'draft')
        ->latest()
        ->first();

    if (!$invoice) {
        return redirect()->back()->with('error', 'No draft invoice found for this supplier.');
    }

    return view('admin.supplier_invoice_draft', compact('invoice'));
}


public function confirmDraftInvoice($invoiceId)
{
    $invoice = SupplierInvoice::with('items')->findOrFail($invoiceId);

    if ($invoice->items->isEmpty()) {
        return redirect()->back()->with('error', 'Cannot confirm an empty invoice.');
    }

    $invoice->status = 'confirmed';
    $invoice->confirmed_at = now();
    $invoice->save();

    return redirect()->route('admin.restockFromInvoice', $invoice->id)
        ->with('success', 'Invoice confirmed successfully. Proceed to restocking.');
}

public function restockFromInvoice($invoiceId)
{
    $businessId = auth()->user()->business_id;
    $invoice = SupplierInvoice::with('items')->findOrFail($invoiceId);
    $newProducts = [];

    foreach ($invoice->items as $item) {
        $product = Product::where('business_id', $businessId)
            ->where('product_name', $item->product_name)
            ->first();

        if ($product) {
            // ✅ Existing product: update stock & weighted average cost
            $oldQty = $product->quantity;
            $oldCost = $product->cost_price ?? 0;
            $newQty = $item->quantity;
            $newCost = $item->cost_price;

            $weightedCost = (($oldCost * $oldQty) + ($newCost * $newQty)) / max(($oldQty + $newQty), 1);
            $product->cost_price = round($weightedCost, 2);
            $product->quantity = $oldQty + $newQty;
            $product->in_stock = true;
            $product->save();

            // Record inventory movement
            InventoryMovement::create([
                'product_id' => $product->id,
                'type' => 'restock',
                'quantity' => $newQty,
                'reference' => $invoice->invoice_number,
                'cost_price' => $newCost,
                'business_id' => $businessId,
            ]);
        } else {
            // 🆕 New product — queue for setup
            $newProducts[] = [
                'supplier_invoice_item_id' => $item->id,
                'product_name' => $item->product_name,
                'description' => $item->description,
                'category' => $item->category,
                'cost_price' => $item->cost_price,
                'suggested_price' => round($item->cost_price * 1.2, 2),
                'quantity' => $item->quantity,
            ];
        }
    }

    if (!empty($newProducts)) {
        // Store pending new products in session
        session(['pending_new_products' => $newProducts]);
        return redirect()->route('admin.showNewProductSetup');
    }

    return redirect()->back()->with('success', 'Stock successfully updated from invoice.');
}


    public function viewCustomeradmin()
    {
        $customers = Customer::all();
        return view('admin.view_customer', compact('customers'));
    }

    public function createCustomeradmin(){

        return view('customers.view_customer');
    }

    public function storeCustomeradmin(Request $request)
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

    public function editCustomeradmin($id)
    {
        $customer = Customer::findOrFail($id);
        
        return view('admin.edit_customer', compact('customer'));
    }

    public function updateCustomeradmin(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:15',
            'location' => 'nullable|string|max:255',
            'total_debt' => 'nullable|numeric'

        ]);

        $customer->update($request->all());

        return redirect('/viewCustomeradmin')->with('success', 'Customer Updated Successfully');
    }

    public function destroyCustomeradmin($id)
    {
        $customer = Customer::find($id);
        $customer->delete();

        return redirect()->back()->with('success', 'Customer Deleted Successfully');
    }

    public function searchCustomeradmin(Request $request){
        $searchCustomeradmin = $request->searchCustomeradmin;
        $customers= Customer::where(function($query) use ($searchCustomeradmin){
            $query->where('customer_name','like',"%$searchCustomeradmin%")
            ->orWhere('phone_number','like',"$searchCustomeradmin");
            
        })->get();
        return view('admin.view_customer',compact('customers'));
    }
}

