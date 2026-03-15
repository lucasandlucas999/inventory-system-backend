<?php

namespace App\Domains\Customers\Actions;
use App\Domains\Customers\Models\Customer;

class GetAllCustomersAction
{
    public function execute()
    {
        if (auth()->user()->isAdmin()) {
            $status = request()->query('status');
            $customers = Customer::query()->with(['invoices' => function ($query) use ($status) {
                if ($status) {
                    $query->where('status', $status);
                }
            }]);
            if (request()->query('customerid')) {
                $customers->where('id', '=', request()->query('customerid'));
            }
            if ($status) {
                $customers->whereHas('invoices', function ($query) use ($status) {
                    $query->where('status', $status);
                });
            }
            return $customers->get();
        }
        return 'Unauthorized user';
    }
}