<?php

namespace App\Domains\Purchases\Actions;

use App\Domains\Purchases\Models\PurchaseOrder;

class GetAllPurchaseOrdersAction
{
    public function execute()
    {
        if (auth()->user()->isAdmin()) {
            $query = PurchaseOrder::query()
                ->with(['supplier', 'user', 'purchaseOrderDetails.product']);

            // Filter by status
            if (request()->query('status')) {
                $query->where('status', request()->query('status'));
            }

            // Filter by supplier
            if (request()->query('supplier_id')) {
                $query->where('supplier_id', request()->query('supplier_id'));
            }

            return $query->get();
        }

        return 'Unauthorized user';
    }
}
