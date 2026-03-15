<?php

namespace Database\Factories\Domains\Purchases;

use App\Domains\Purchases\Models\PurchaseOrder;
use App\Domains\Purchases\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseOrderFactory extends Factory
{
    protected $model = PurchaseOrder::class;

    public function definition(): array
    {
        return [
            'order_number' => $this->faker->unique()->bothify('PO-####-????'),
            'supplier_id' => Supplier::factory(),
            'user_id' => User::factory(),
            'date' => $this->faker->date(),
            'total_amount' => $this->faker->randomFloat(2, 100, 10000),
            'status' => $this->faker->randomElement(['PENDING', 'PENDING_RECEIPT', 'COMPLETED', 'CANCELLED']),
        ];
    }
}
