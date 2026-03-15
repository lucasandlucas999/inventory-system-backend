<?php

namespace App\Domains\Products\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domains\Products\Actions\GetAllProductsAction;

class IndexProductsController extends Controller
{

    public function __invoke(Request $request, GetAllProductsAction $action)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $action->execute()
            ], 200);
        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
