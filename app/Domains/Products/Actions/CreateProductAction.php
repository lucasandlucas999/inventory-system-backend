<?php

namespace App\Domains\Products\Actions;

use App\Domains\Products\Models\Product;

class CreateProductAction
{
    public function execute ( array $data)
    {
        if ( auth()->user()->isAdmin()){
            return Product::create($data);
        }

        return 'Unauthorized user';
    }
}