<?php

namespace App\Domains\Dasboard\Controllers;

use App\Domains\Dasboard\Services\DashboardService;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{

    public function __construct(private DashboardService $dashboardService)
    {
    }

    public function __invoke()
    {
        $data = $this->dashboardService->getDashboardData();
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}