<?php

namespace App\Domains\Products\Actions;

use App\Domains\Products\Models\Category;

class GetAllCategoriesAction
{
    public function execute()
    {
        if (auth()->user()->isAdmin()) {
            $query = Category::query();

            // Filter by active status if provided
            if (request()->query('is_active') !== null) {
                $query->where('is_active', request()->query('is_active'));
            }

            // Include products count if requested
            if (request()->query('with_products_count')) {
                $query->withCount('products');
            }

            return $query->get();
        }

        return 'Unauthorized user';
    }
}
