<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\SupplierProduct;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        // Create suppliers each with their own products
        Supplier::factory()
            ->count(10)
            ->create()
            ->each(function ($supplier) {
                SupplierProduct::factory()
                    ->count(rand(5, 10))
                    ->create([
                        'supplier_id' => $supplier->id,
                        'business_id' => $supplier->business_id,
                    ]);
            });
    }
}
