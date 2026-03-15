<?php

namespace App\Domains\Products\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domains\Products\Actions\GetAllCategoriesAction;

class IndexCategoriesController extends Controller
{

    public function __invoke(Request $request, GetAllCategoriesAction $action)
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
