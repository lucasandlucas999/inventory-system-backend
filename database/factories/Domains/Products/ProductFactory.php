<?php

namespace Database\Factories\Domains\Products;

use App\Domains\Products\Models\Category;
use App\Domains\Products\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $purchasePrice = $this->faker->randomFloat(2, 10, 500);
        return [
            'code' => $this->faker->unique()->ean13(),
            'name' => $this->faker->words(3, true),
            'category_id' => Category::factory(),
            'purchase_price' => $purchasePrice,
            'sale_price' => $purchasePrice * $this->faker->randomFloat(2, 1.2, 2.0),
            'current_stock' => $this->faker->numberBetween(10, 100),
            'minimum_stock' => $this->faker->numberBetween(5, 20),
            'is_active' => $this->faker->boolean(90),
        ];
    }
}
