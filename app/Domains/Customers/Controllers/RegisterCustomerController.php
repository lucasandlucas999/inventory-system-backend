<?php

namespace App\Domains\Customers\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Customers\Actions\RegisterCustomerAction;
use App\Domains\Customers\Requests\RegisterCustomerRequest;

class RegisterCustomerController extends Controller
{
    public function __invoke(RegisterCustomerRequest $request, RegisterCustomerAction $action)
    {
        try {
            $customer = $action->execute($request->validated());

            return response()->json([
                'success' => true,
                'data' => $customer,
                'message' => 'Customer registered successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $e->getMessage() === 'Unauthorized user' ? 403 : 500);
        }
    }
}
