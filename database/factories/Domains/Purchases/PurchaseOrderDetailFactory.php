<?php

namespace Database\Factories\Domains\Purchases;

use App\Domains\Products\Models\Product;
use App\Domains\Purchases\Models\PurchaseOrder;
use App\Domains\Purchases\Models\PurchaseOrderDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseOrderDetailFactory extends Factory
{
    protected $model = PurchaseOrderDetail::class;

    public function definition(): array
    {
        $quantity = $this->faker->randomFloat(2, 1, 50);
        $cost = $this->faker->randomFloat(2, 5, 200);

        return [
            'purchase_order_id' => PurchaseOrder::factory(),
            'product_id' => Product::factory(),
            'quantity' => $quantity,
            'unit_cost' => $cost,
            'subtotal' => $quantity * $cost,
        ];
    }
}
