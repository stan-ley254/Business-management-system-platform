<?php

use App\Models\Business;
use App\Models\Product;
use App\Models\Role;
use App\Models\Supplier;
use App\Models\SupplierProduct;
use App\Models\SupplierInvoice;
use App\Models\SupplierInvoiceItem;
use App\Models\User;

it('stores barcode when creating new product via storeNewProducts', function () {
    $business = Business::factory()->create(['is_active' => true]);
    $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
    $admin = User::factory()->create(['business_id' => $business->id, 'role_id' => $roleAdmin->id]);

    $supplier = Supplier::factory()->create(['business_id' => $business->id]);
    $supplierProduct = SupplierProduct::factory()->create([
        'supplier_id' => $supplier->id,
        'business_id' => $business->id,
        'supplier_product_name' => 'Test SKU',
        'barcode' => 'SP-NEW-123',
    ]);

    $productData = [
        'product_name' => 'Test SKU',
        'description' => 'From supplier',
        'category' => 'General',
        'cost_price' => 100,
        'price' => 120,
        'discount_price' => null,
        'quantity' => 5,
        'barcode' => $supplierProduct->barcode,
    ];

    $this->actingAs($admin)
        ->post('/admin/new-products/store', ['products' => [$productData]])
        ->assertRedirect();

    $created = Product::where('business_id', $business->id)->where('product_name', 'Test SKU')->first();
    expect($created)->not->toBeNull();
    expect($created->barcode)->toBe('SP-NEW-123');
});

it('does not overwrite existing product barcode when restocking', function () {
    $business = Business::factory()->create(['is_active' => true]);
    $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
    $admin = User::factory()->create(['business_id' => $business->id, 'role_id' => $roleAdmin->id]);

    // existing product with barcode
    $existing = Product::create([
        'business_id' => $business->id,
        'product_name' => 'Milk',
        'price' => 50,
        'quantity' => 10,
        'cost_price' => 40,
        'barcode' => 'EXIST-001',
        'in_stock' => true,
        'description' => 'Existing milk product',
        'category' => 'General',
    ]);

    $supplier = Supplier::factory()->create(['business_id' => $business->id]);
    $supplierProduct = SupplierProduct::factory()->create([
        'supplier_id' => $supplier->id,
        'business_id' => $business->id,
        'supplier_product_name' => 'Milk',
        'barcode' => 'SP-DIFF-999',
    ]);

    // create invoice and item referencing the product_name
    $invoice = SupplierInvoice::create([
        'supplier_id' => $supplier->id,
        'business_id' => $business->id,
        'status' => 'confirmed',
        'invoice_number' => 'INV-TEST-1',
        'date' => now(),
        'created_by' => $admin->name,
    ]);

    SupplierInvoiceItem::create([
        'supplier_invoice_id' => $invoice->id,
        'supplier_product_id' => $supplierProduct->id,
        'product_name' => 'Milk',
        'cost_price' => 45,
        'quantity' => 5,
        'subtotal' => 225,
        'barcode' => $supplierProduct->barcode,
        'business_id' => $business->id,
    ]);

    $this->actingAs($admin)
        ->post(route('supplier.invoice.restock', ['invoice' => $invoice->id]))
        ->assertRedirect();

    $existing->refresh();
    expect($existing->barcode)->toBe('EXIST-001');
});

it('fills empty barcode on existing product when restocking if barcode was null', function () {
    $business = Business::factory()->create(['is_active' => true]);
    $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
    $admin = User::factory()->create(['business_id' => $business->id, 'role_id' => $roleAdmin->id]);

    // existing product without barcode
    $existing = Product::create([
        'business_id' => $business->id,
        'product_name' => 'Soap',
        'price' => 30,
        'quantity' => 2,
        'cost_price' => 20,
        'barcode' => null,
        'in_stock' => true,
        'description' => 'Existing soap product',
        'category' => 'General',
    ]);

    $supplier = Supplier::factory()->create(['business_id' => $business->id]);
    $supplierProduct = SupplierProduct::factory()->create([
        'supplier_id' => $supplier->id,
        'business_id' => $business->id,
        'supplier_product_name' => 'Soap',
        'barcode' => 'SP-SOAP-321',
    ]);

    $invoice = SupplierInvoice::create([
        'supplier_id' => $supplier->id,
        'business_id' => $business->id,
        'status' => 'confirmed',
        'invoice_number' => 'INV-TEST-2',
        'date' => now(),
        'created_by' => $admin->name,
    ]);

    SupplierInvoiceItem::create([
        'supplier_invoice_id' => $invoice->id,
        'supplier_product_id' => $supplierProduct->id,
        'product_name' => 'Soap',
        'cost_price' => 25,
        'quantity' => 10,
        'subtotal' => 250,
        'barcode' => $supplierProduct->barcode,
        'business_id' => $business->id,
    ]);

    // Sanity checks before restock
    expect(Product::where('business_id', $business->id)->where('product_name', 'Soap')->exists())->toBeTrue();
    $itemRecord = SupplierInvoiceItem::where('supplier_invoice_id', $invoice->id)->first();
    expect($itemRecord)->not->toBeNull();
    // invoice item may not store barcode on the item (not fillable), ensure supplierProduct relation has it
    expect($itemRecord->barcode)->toBeNull();
    $this->assertNotNull($itemRecord->supplierProduct);
    expect($itemRecord->supplierProduct->barcode)->toBe('SP-SOAP-321');

    $beforeQty = $existing->quantity;

    $response = $this->actingAs($admin)
        ->post(route('supplier.invoice.restock', ['invoice' => $invoice->id]));

    // If the controller queues new products the session key 'pending_new_products' will be set
    $pending = session('pending_new_products');

    // For this test we expect the existing product branch to run (no pending new products)
    expect($pending)->toBeNull();

    $response->assertRedirect();

    $existing->refresh();

    // ensure restock actually ran for this existing product
    expect($existing->quantity)->toBeGreaterThan($beforeQty);
    expect($existing->barcode)->toBe('SP-SOAP-321');
});
