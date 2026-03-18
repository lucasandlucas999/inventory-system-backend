<?php

namespace App\Domains\Products\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Products\Models\Product;
use App\Domains\Products\Actions\GetProductByIdAction;

class ShowProductController extends Controller
{
    public function __invoke(Product $product, GetProductByIdAction $action)
    {

        try {

            return response()->json([
                'success' => true,
                'data' => $action->execute($product)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
