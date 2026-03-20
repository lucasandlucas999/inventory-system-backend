<?php

namespace App\Domains\Customers\Actions;

use App\Domains\Customers\Models\Customer;

class GetCustomerByIdAction
{
    public function execute(Customer $customer)
    {
        if (auth()->user()->isAdmin()) {
            return $customer;
        }

        return 'Unauthorized user';
    }
}