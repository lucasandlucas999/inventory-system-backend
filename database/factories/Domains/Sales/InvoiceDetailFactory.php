<?php

namespace Database\Factories\Domains\Sales;

use App\Domains\Products\Models\Product;
use App\Domains\Sales\Models\Invoice;
use App\Domains\Sales\Models\InvoiceDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceDetailFactory extends Factory
{
    protected $model = InvoiceDetail::class;

    public function definition(): array
    {
        $quantity = $this->faker->randomFloat(2, 1, 50);
        $price = $this->faker->randomFloat(2, 10, 500);

        return [
            'invoice_id' => Invoice::factory(),
            'product_id' => Product::factory(),
            'quantity' => $quantity,
            'unit_price' => $price,
            'subtotal' => $quantity * $price,
        ];
    }
}
