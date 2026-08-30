<?php

use App\Models\Business;
use App\Models\Receipt;
use App\Models\Role;
use App\Models\User;

it('user sees only their receipts for the business', function () {
    $business = Business::factory()->create(['is_active' => true]);
    $role = Role::firstOrCreate(['name' => 'user']);

    $user1 = User::factory()->create(['business_id' => $business->id, 'role_id' => $role->id]);
    $user2 = User::factory()->create(['business_id' => $business->id, 'role_id' => $role->id]);

    $r1 = Receipt::create([
        'business_id' => $business->id,
        'cart_id' => 1111,
        'cashier_id' => $user1->id,
        'customer_name' => 'Walk-in',
        'payment_status' => 'cash',
        'items' => [['product_name' => 'A','quantity'=>1,'price'=>10,'line_total'=>10]],
        'total' => 10,
    ]);

    $r2 = Receipt::create([
        'business_id' => $business->id,
        'cart_id' => 2222,
        'cashier_id' => $user2->id,
        'customer_name' => 'Walk-in2',
        'payment_status' => 'cash',
        'items' => [['product_name' => 'B','quantity'=>1,'price'=>20,'line_total'=>20]],
        'total' => 20,
    ]);

    $this->actingAs($user1);

    $this->get('/viewReceipts')
        ->assertOk()
        ->assertSee((string) $r1->cart_id)
        ->assertDontSee((string) $r2->cart_id);
});

it('admin sees all receipts for the business', function () {
    $business = Business::factory()->create(['is_active' => true]);
    $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
    $roleUser = Role::firstOrCreate(['name' => 'user']);

    $admin = User::factory()->create(['business_id' => $business->id, 'role_id' => $roleAdmin->id]);
    $user = User::factory()->create(['business_id' => $business->id, 'role_id' => $roleUser->id]);

    $r1 = Receipt::create([
        'business_id' => $business->id,
        'cart_id' => 3333,
        'cashier_id' => $admin->id,
        'customer_name' => 'AdminCust',
        'payment_status' => 'cash',
        'items' => [['product_name' => 'X','quantity'=>1,'price'=>30,'line_total'=>30]],
        'total' => 30,
    ]);

    $r2 = Receipt::create([
        'business_id' => $business->id,
        'cart_id' => 4444,
        'cashier_id' => $user->id,
        'customer_name' => 'UserCust',
        'payment_status' => 'cash',
        'items' => [['product_name' => 'Y','quantity'=>1,'price'=>40,'line_total'=>40]],
        'total' => 40,
    ]);

    $this->actingAs($admin);

    $this->get('/view_receipts')
        ->assertOk()
        ->assertSee((string) $r1->cart_id)
        ->assertSee((string) $r2->cart_id);
});
