<?php

namespace App\Domains\Customers\Actions;

use App\Domains\Customers\Models\Customer;
use Illuminate\Support\Facades\DB;

class RegisterCustomerAction
{
    public function execute(array $data): ?Customer
    {
        if (auth()->user() && auth()->user()->isAdmin()) {
            return DB::transaction(function () use ($data) {
                return Customer::create($data);
            });
        }
        
        throw new \Exception('Unauthorized user');
    }
}
