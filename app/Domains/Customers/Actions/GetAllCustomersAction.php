<?php

namespace App\Domains\Customers\Actions;
use App\Domains\Customers\Models\Customer;
use Illuminate\Support\Facades\Log;

class GetAllCustomersAction
{
    public function execute()
    {
        // $status = request()->query('status');

        // $customers = Customer::query()->with(['invoices' => function ($query) use ($status) {
        //     if ($status) {
        //         $query->where('status', $status);
        //     }
        // }]);

        // if (request()->query('customerid')) {
        //     $customers->where('id', '=', request()->query('customerid'));
        // }

        // if ($status) {
        //     $customers->whereHas('invoices', function ($query) use ($status) {
        //         $query->where('status', $status);
        //     });
        // }

        // return $customers->get();

        $query = Customer::with(['invoices' => function ($query) {
            $query->select('id', 'invoice_number', 'status');
        }])->when(request()->query('customerid'), function ($query) {
            $query->where('id', '=', request()->query('customerid'));
        })->select('id', 'name')->get();

        return $query;

    }
}