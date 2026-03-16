<?php

namespace App\Domains\Inventory\Actions;

use App\Domains\Inventory\Models\StockMovement;

class GetAllStockMovementsAction
{
    public function execute()
    {
        if (auth()->user()->isAdmin()) {
            $query = StockMovement::query()
                ->with(['product', 'user']);

            // Filter by type
            if (request()->query('type')) {
                $query->where('type', request()->query('type'));
            }

            // Filter by product
            if (request()->query('product_id')) {
                $query->where('product_id', request()->query('product_id'));
            }

            return $query->orderBy('date', 'desc')->get();
        }

        return 'Unauthorized user';
    }
}
