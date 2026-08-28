<?php

use App\Models\Business;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Receipt;
use App\Models\Role;
use App\Models\Sales;
use App\Models\User;

it('blocks cross-business receipt downloads', function () {
    $businessA = Business::factory()->create(['is_active' => true]);
    $businessB = Business::factory()->create(['is_active' => true]);
    $role = Role::firstOrCreate(['name' => 'user']);

    $userA = User::factory()->create([
        'business_id' => $businessA->id,
        'role_id' => $role->id,
    ]);

    $userB = User::factory()->create([
        'business_id' => $businessB->id,
        'role_id' => $role->id,
    ]);

    $cart = Cart::create([
        'business_id' => $businessA->id,
        'status' => 'active',
        'checked_out' => true,
    ]);

    Receipt::create([
        'business_id' => $businessA->id,
        'cart_id' => $cart->id,
        'cashier_id' => $userA->id,
        'customer_name' => 'Walk-in Customer',
        'payment_status' => 'cash',
        'items' => [[
            'product_name' => 'Soap',
            'quantity' => 2,
            'price' => 50,
            'line_total' => 100,
        ]],
        'total' => 100,
    ]);

    $this->actingAs($userB);

    $this->get('/download-receipt/'.$cart->id)
        ->assertForbidden();
});

it('keeps the stored receipt total even after a restore changes live sales totals', function () {
    $business = Business::factory()->create(['is_active' => true]);
    $role = Role::firstOrCreate(['name' => 'user']);
    $user = User::factory()->create([
        'business_id' => $business->id,
        'role_id' => $role->id,
    ]);

    $cart = Cart::create([
        'business_id' => $business->id,
        'status' => 'active',
    ]);

    Receipt::create([
        'business_id' => $business->id,
        'cart_id' => $cart->id,
        'cashier_id' => $user->id,
        'customer_name' => 'Walk-in Customer',
        'payment_status' => 'cash',
        'items' => [[
            'product_name' => 'Milk',
            'quantity' => 3,
            'price' => 40,
            'line_total' => 120,
        ]],
        'total' => 120,
    ]);

    Sales::create([
        'business_id' => $business->id,
        'cart_id' => $cart->id,
        'product_name' => 'Milk',
        'description' => 'Milk',
        'price' => 40,
        'active_price' => 40,
        'discount_price' => 0,
        'quantity' => 3,
        'total' => 75,
        'payment_status' => 'cash',
        'status' => 'restored',
    ]);

    $this->actingAs($user);

    $response = $this->get('/download-receipt/'.$cart->id);

    $response->assertOk()
        ->assertHeader('Content-Disposition');

    expect((float) Receipt::where('cart_id', $cart->id)->first()->total)->toBe(120.0);
    expect((float) Sales::where('cart_id', $cart->id)->sum('total'))->toBe(75.0);
});

it('stores the linked customer name for debt sales', function () {
    $business = Business::factory()->create(['is_active' => true]);
    $role = Role::firstOrCreate(['name' => 'user']);
    $user = User::factory()->create([
        'business_id' => $business->id,
        'role_id' => $role->id,
    ]);

    $customer = Customer::create([
        'business_id' => $business->id,
        'customer_name' => 'Jane Doe',
        'phone_number' => '0712345678',
        'location' => 'Nairobi',
        'total_debt' => 0,
    ]);

    $cart = Cart::create([
        'business_id' => $business->id,
        'status' => 'active',
    ]);

    Receipt::create([
        'business_id' => $business->id,
        'cart_id' => $cart->id,
        'cashier_id' => $user->id,
        'customer_name' => $customer->customer_name,
        'payment_status' => 'debt',
        'items' => [[
            'product_name' => 'Rice',
            'quantity' => 1,
            'price' => 150,
            'line_total' => 150,
        ]],
        'total' => 150,
    ]);

    $this->actingAs($user);

    $this->get('/download-receipt/'.$cart->id)
        ->assertOk();

    expect(Receipt::first()->customer_name)->toBe('Jane Doe');
});
