<?php

namespace App\Domains\Dasboard\Services;
use App\Domains\Products\Models\Product;
use App\Domains\Sales\Models\Invoice;

class DashboardService
{
    public function getDashboardData()
    {
        $totalProducts = $this->getTotalProducts();
        $totalSales = $this->getTotalSales();
        return [
            'total_products' => $totalProducts,
            'total_sales' => $totalSales,
        ];
    }

    private function getTotalProducts()
    {
        return Product::count();
    }

    private function getTotalSales()
    {
        return Invoice::where('status', 'PAID')->sum('total_amount');
    }

}