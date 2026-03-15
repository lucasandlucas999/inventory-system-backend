<?php

namespace App\Http\Controllers;

use App\Actions\Database\DatabaseHealthAction;
use Illuminate\Http\JsonResponse;

class DatabaseHealthController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(DatabaseHealthAction $action): JsonResponse
    {
        $result = $action->execute();

        if ($result['status'] === 'OK') {
            return response()->json($result, 200);
        }

        return response()->json($result, 503);
    }
}
