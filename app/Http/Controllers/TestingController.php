<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Actions\TestingAction;

class TestingController extends Controller
{
    public function __invoke(Request $request, TestingAction $action)
    {
        $action->execute();
    }
}
