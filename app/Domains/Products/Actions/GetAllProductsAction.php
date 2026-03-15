<?php

namespace App\Domains\Products\Actions;

use App\Domains\Products\Models\Product;

class GetAllProductsAction
{
    public function execute()
    {
        if (auth()->user()->isAdmin()) {
            $query = Product::query()->with('category');

            // Filter by category
            if (request()->query('category_id')) {
                $query->where('category_id', request()->query('category_id'));
            }

            // Filter by active status
            if (request()->query('is_active') !== null) {
                $query->where('is_active', request()->query('is_active'));
            }

            // Filter by low stock
            if (request()->query('low_stock')) {
                $query->whereColumn('current_stock', '<=', 'minimum_stock');
            }

            return $query->get();
        }

        return 'Unauthorized user';
    }
}
