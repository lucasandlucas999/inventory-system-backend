<?php

namespace App\Domains\Customers\Actions;

use App\Domains\Customers\Models\Customer;

class StoreCustomerAction
{
    public function execute(array $data): Customer
    {
        if( auth()->user()->isAdmin()) {
            $customer = Customer::create($data);

            return $customer;
        }
        
        return 'Unauthorized user';
    }
}