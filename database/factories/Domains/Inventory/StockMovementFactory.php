<?php

namespace Database\Factories\Domains\Inventory;

use App\Domains\Inventory\Models\StockMovement;
use App\Domains\Products\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockMovementFactory extends Factory
{
    protected $model = StockMovement::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'type' => $this->faker->randomElement(['IN', 'OUT', 'ADJUSTMENT']),
            'quantity' => $this->faker->randomFloat(2, 1, 100),
            'date' => $this->faker->date(),
            'reference' => $this->faker->bothify('REF-##??'),
            'user_id' => User::factory(),
            'notes' => $this->faker->sentence(),
        ];
    }
}
