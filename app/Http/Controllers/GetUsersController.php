<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Actions\GetUsersAction;
use App\Http\Resources\GetUsersResource;

class GetUsersController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, GetUsersAction $action)
    {
        try {
            return GetUsersResource::collection($action->execute($request));
        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error on get users: ' . $e->getMessage(),
            ]);
        }
    }
}
