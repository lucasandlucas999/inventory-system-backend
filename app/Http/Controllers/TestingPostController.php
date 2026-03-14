<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Actions\TestingPostAction;


class TestingPostController extends Controller
{
    /**
     * Handle the incoming post request
     */
    public function __invoke(Request $request, TestingPostAction $action)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $action->execute($request),
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error on post: ' . $e->getMessage(),
            ]);
        }
    }
}
