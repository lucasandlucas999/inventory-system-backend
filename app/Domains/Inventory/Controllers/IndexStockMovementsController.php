<?php

namespace App\Domains\Inventory\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domains\Inventory\Actions\GetAllStockMovementsAction;

class IndexStockMovementsController extends Controller
{

    public function __invoke(Request $request, GetAllStockMovementsAction $action)
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
