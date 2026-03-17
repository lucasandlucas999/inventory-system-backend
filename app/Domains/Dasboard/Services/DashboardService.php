<?php

namespace App\Domains\Dasboard\Services;
use App\Domains\Products\Models\Product;
use App\Domains\Sales\Models\Invoice;
use App\Domains\Purchases\Models\PurchaseOrder;

class DashboardService
{
    public function getDashboardData()
    {
        $totalProducts = $this->getTotalProducts();
        $totalSales = $this->getCurrentMonthTotalSales();
        $totalPurchases = $this->getCurrentMonthTotalPurchases();
        return [
            'total_products' => $totalProducts,
            'total_sales' => $totalSales,
            'total_purchases' => $totalPurchases,
        ];
    }

    private function getTotalProducts()
    {
        return Product::count();
    }

    private function getCurrentMonthTotalSales()
    {
        $query = Invoice::query()->where('status', 'PAID')
            ->where('created_at', '>=', now()->subMonth())
            ->sum('total_amount');
        return $query;
    }

    private function getCurrentMonthTotalPurchases()
    {
        $query = PurchaseOrder::query()->where('status', 'COMPLETED')
            ->where('created_at', '>=', now()->subMonth())
            ->sum('total_amount');
        return $query;
    }




}