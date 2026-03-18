<?php

namespace App\Domains\Products\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Products\Models\Product;
use App\Domains\Products\Actions\DeleteProductAction;

class DeleteProductController extends Controller
{
    public function __invoke(Product $product, DeleteProductAction $action)
    {
        try {
            $result = $action->execute($product);

            return response()->json([
                'success' => true,
                'data' => $result,
                'message' => 'Producto eliminado correctamente.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
