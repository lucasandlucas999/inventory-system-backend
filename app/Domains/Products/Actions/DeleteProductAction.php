<?php

namespace App\Domains\Products\Actions;

use App\Models\AuditLog;
use App\Domains\Products\Models\Product;

class DeleteProductAction
{
    public function execute(Product $product)
    {
        if (auth()->user()->isAdmin()) {
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'DELETE',
                'description' => 'Elimino el producto ID ' . $product->id . ' con codigo: ' . $product->code . ' y nombre: ' . $product->name,
                'affected_table' => 'products',
                'record_id' => $product->id,
                'ip_address' => request()->ip(),
                'date' => now(),
            ]);

            $product->delete();

            return true;
        }

        return 'Unauthorized user';
    }
}
