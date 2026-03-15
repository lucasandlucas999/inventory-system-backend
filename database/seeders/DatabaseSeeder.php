<?php

namespace Database\Seeders;

use App\Models\User;
use App\Domains\Products\Models\Category;
use App\Domains\Products\Models\Product;
use App\Domains\Customers\Models\Customer;
use App\Domains\Purchases\Models\Supplier;
use App\Domains\Purchases\Models\PurchaseOrder;
use App\Domains\Purchases\Models\PurchaseOrderDetail;
use App\Domains\Sales\Models\Invoice;
use App\Domains\Sales\Models\InvoiceDetail;
use App\Domains\Inventory\Models\StockMovement;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@inventory.local',
            'password' => bcrypt('password123'),
        ]);

        $this->command->info('Creating Categories and Products...');
        $categories = Category::factory(5)->create();
        $products = Product::factory(20)->create();

        $this->command->info('Creating Customers and Suppliers...');
        $customers = Customer::factory(10)->create();
        $suppliers = Supplier::factory(5)->create();

        $this->command->info('Creating Purchase Orders...');
        PurchaseOrder::factory(5)->create()->each(function ($order) use ($products) {
            PurchaseOrderDetail::factory(3)->create([
                'purchase_order_id' => $order->id,
                'product_id' => $products->random()->id,
            ]);
        });

        $this->command->info('Creating Invoices...');
        Invoice::factory(10)->create()->each(function ($invoice) use ($products) {
            InvoiceDetail::factory(3)->create([
                'invoice_id' => $invoice->id,
                'product_id' => $products->random()->id,
            ]);
        });

        $this->command->info('Creating Stock Movements...');
        StockMovement::factory(20)->create([
            'product_id' => $products->random()->id,
            'user_id' => $user->id,
        ]);
        
        $this->command->info('Database perfectly seeded with Domain Driven Design models!');
    }
}
