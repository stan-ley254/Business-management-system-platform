<?php

namespace Database\Factories;

use App\Models\Business;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Business>
 */
class BusinessFactory extends Factory
{
    protected $model = Business::class;

    /**
     * Define the model's default state.
     *
     * @return array<string,mixed>
     */
    public function definition(): array
    {
        return [
            'name'                    => $this->faker->company(),
            'type'                    => $this->faker->randomElement(['pos', 'service',]),
            'next_payment_due'        => $this->faker->optional()->dateTimeBetween('now', '+6 months'),
            'is_active'               => $this->faker->boolean(85),
            'mpesa_short_code'        => $this->faker->optional()->numerify('#####'),
            'mpesa_consumer_key'      => $this->faker->optional()->sha256(),
            'mpesa_consumer_secret'   => $this->faker->optional()->sha256(),
            'mpesa_passkey'           => $this->faker->optional()->bothify('passkey-####-????'),
            'mpesa_initiator_name'    => $this->faker->optional()->userName(),
            'mpesa_security_credential'=> $this->faker->optional()->sha1(),
        ];
    }
}
