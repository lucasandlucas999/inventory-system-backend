<?php

namespace App\Domains\Sales\Actions;

use App\Domains\Sales\Models\Invoice;

class GetAllInvoicesAction
{
    public function execute()
    {
        if (auth()->user()->isAdmin()) {
            $query = Invoice::query()
                ->with(['customer', 'user', 'invoiceDetails.product']);

            // Filter by status
            if (request()->query('status')) {
                $query->where('status', request()->query('status'));
            }

            // Filter by customer
            if (request()->query('customer_id')) {
                $query->where('customer_id', request()->query('customer_id'));
            }

            return $query->get();
        }

        return 'Unauthorized user';
    }
}
