<?php

namespace Database\Factories\Domains\Customers;

use App\Domains\Customers\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'document_number' => $this->faker->unique()->numerify('###########'),
            'name' => $this->faker->company(),
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'credit_limit' => $this->faker->randomFloat(2, 1000, 50000),
            'is_active' => $this->faker->boolean(90),
        ];
    }
}
