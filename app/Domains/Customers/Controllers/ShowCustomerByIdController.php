<?php

namespace App\Domains\Customers\Controllers;


use App\Http\Controllers\Controller;
use App\Domains\Customers\Models\Customer;
use App\Domains\Customers\Actions\GetCustomerByIdAction;

class ShowCustomerByIdController extends Controller
{
    public function __invoke( Customer $customer, GetCustomerByIdAction $action )
    {
        try{

            return response()->json([
                'success'=> true,
                'data' => $action->execute($customer)
            ], 200);

        }catch( \Exception $e ){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}