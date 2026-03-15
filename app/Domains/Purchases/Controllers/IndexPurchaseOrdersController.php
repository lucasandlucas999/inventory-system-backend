<?php

namespace App\Domains\Purchases\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domains\Purchases\Actions\GetAllPurchaseOrdersAction;

class IndexPurchaseOrdersController extends Controller
{

    public function __invoke(Request $request, GetAllPurchaseOrdersAction $action)
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
