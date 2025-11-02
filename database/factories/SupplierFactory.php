<?php

namespace Database\Factories;

use App\Models\Supplier;
use App\Models\Business;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    protected $model = Supplier::class;

    public function definition()
    {
        return [
            'supplier_name' => $this->faker->company,
            'phone_number' => $this->faker->unique()->phoneNumber,
            'description' => $this->faker->sentence(8),
            'amount' => $this->faker->randomFloat(2, 10000, 500000),
            'balance' => $this->faker->randomFloat(2, 0, 10000),
            'status' => $this->faker->randomElement(['active', 'inactive', 'pending']),
            'location' => $this->faker->city,
            'business_id' => Business::factory(), // ensures supplier belongs to a business
        ];
    }


    protected static function sanitizePhoneNumber($phoneNumber)
    {
        // Remove all non-numeric characters
        return preg_replace('/\D/', '', $phoneNumber);
    }
}