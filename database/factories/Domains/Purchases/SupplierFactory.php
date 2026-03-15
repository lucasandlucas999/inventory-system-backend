<?php

namespace Database\Factories\Domains\Purchases;

use App\Domains\Purchases\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    protected $model = Supplier::class;

    public function definition(): array
    {
        return [
            'document_number' => $this->faker->unique()->numerify('###########'),
            'name' => $this->faker->company(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->companyEmail(),
            'address' => $this->faker->address(),
            'is_active' => $this->faker->boolean(90),
        ];
    }
}
