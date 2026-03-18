<?php

namespace App\Domains\Products\Actions;

use App\Domains\Products\Models\Product;

class GetProductByIdAction
{
    public function execute(Product $product)
    {
        if (auth()->user()->isAdmin()) {
            return $product->load('category');
        }

        return 'Unauthorized user';
    }
}
