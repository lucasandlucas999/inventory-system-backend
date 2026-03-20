<?php

namespace App\Domains\Customers\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Customers\Models\Customer;
use App\Domains\Customers\Actions\DeleteCustomerAction;

class DeleteCustomerController extends Controller
{
    public function __invoke(Customer $customer, DeleteCustomerAction $action)
    {
        try {
            $result = $action->execute($customer);

            if ($result !== true) {
                return response()->json([
                    'success' => false,
                    'message' => $result
                ], 403);
            }

            return response()->json([
                'success' => true,
                'message' => 'Customer deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
