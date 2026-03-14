<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Actions\TestingGetAction;

class TestingGetController extends Controller
{
    /**
     * Handle the incoming get request
     */
    public function __invoke(Request $request, TestingGetAction $action)
    {
        try {
            return response()->json([
                'success' => true,
                'message' => $action->execute(),
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error on get: ' . $e->getMessage(),
            ]);
        }
    }
}
