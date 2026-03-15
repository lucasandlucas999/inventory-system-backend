<?php

namespace App\Domains\Purchases\Actions;

use App\Domains\Purchases\Models\Supplier;

class GetAllSuppliersAction
{
    public function execute()
    {
        if (auth()->user()->isAdmin()) {
            $query = Supplier::query();

            // Filter by active status if provided
            if (request()->query('is_active') !== null) {
                $query->where('is_active', request()->query('is_active'));
            }

            return $query->get();
        }

        return 'Unauthorized user';
    }
}
