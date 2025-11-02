<?php

namespace Database\Factories;

use App\Models\SupplierProduct;
use App\Models\Supplier;
use App\Models\Business;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierProductFactory extends Factory
{
    protected $model = SupplierProduct::class;

    public function definition()
    {
        return [
            'supplier_id' => Supplier::factory(),
            'business_id' => Business::factory(),
            'supplier_product_name' => $this->faker->word() . ' ' . $this->faker->randomElement(['1kg', '500ml', 'Box', 'Pack']),
            'barcode' => $this->faker->ean13,
            'default_cost_price' => $this->faker->randomFloat(2, 50, 1500),
            'description' => $this->faker->sentence(6),
            'linked_product_id' => null, // can later be linked to actual Product factory if needed
        ];
    }
}
