<?php

namespace App\Domains\Sales\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domains\Sales\Actions\GetAllInvoicesAction;

class IndexInvoicesController extends Controller
{

    public function __invoke(Request $request, GetAllInvoicesAction $action)
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
