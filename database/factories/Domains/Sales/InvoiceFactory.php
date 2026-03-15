<?php

namespace Database\Factories\Domains\Sales;

use App\Domains\Customers\Models\Customer;
use App\Domains\Sales\Models\Invoice;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        return [
            'invoice_number' => $this->faker->unique()->bothify('INV-####-????'),
            'customer_id' => Customer::factory(),
            'user_id' => User::factory(),
            'date' => $this->faker->date(),
            'total_amount' => $this->faker->randomFloat(2, 100, 10000),
            'status' => $this->faker->randomElement(['PENDING', 'PAID', 'CANCELLED']),
        ];
    }
}
