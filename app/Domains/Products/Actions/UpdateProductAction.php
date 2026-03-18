<?php

namespace App\Domains\Products\Actions;

use App\Models\AuditLog;
use App\Domains\Products\Models\Product;

class UpdateProductAction
{
    public function execute(Product $product, array $data)
    {
        if (auth()->user()->isAdmin()) {

            //auditoria
            $oldData = $product->only([
                'code',
                'name',
                'category_id',
                'purchase_price',
                'sale_price',
                'current_stock',
                'minimum_stock',
                'is_active',
            ]);

            //actualizar el producto
            $product->update($data);

            $newData = $product->fresh()->only([
                'code',
                'name',
                'category_id',
                'purchase_price',
                'sale_price',
                'current_stock',
                'minimum_stock',
                'is_active',
            ]);


            $changes = [];

            foreach ($oldData as $field => $oldValue) {
                $newValue = $newData[$field];

                if ((string) $oldValue !== (string) $newValue) {
                    $changes[] = $field . ": '" . $oldValue . "' -> '" . $newValue . "'";
                }
            }

            $description = 'Actualizó el producto ID ' . $product->id;

            if (!empty($changes)) {
                $description .= '. Cambios: ' . implode(', ', $changes);
            }

            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'UPDATE',
                'description' => $description,
                'affected_table' => 'products',
                'record_id' => $product->id,
                'ip_address' => request()->ip(),
                'date' => now(),
            ]);

            return $product->load('category');
        }


        return 'Unauthorized user';
    }
}
