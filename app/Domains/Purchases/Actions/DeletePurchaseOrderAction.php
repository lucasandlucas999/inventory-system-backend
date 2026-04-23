<?php

namespace App\Domains\Purchases\Actions;

use App\Models\AuditLog;
use App\Domains\Purchases\Models\PurchaseOrder;

class DeletePurchaseOrderAction
{
    public function execute(PurchaseOrder $purchaseOrder): bool|string
    {
        if (auth()->user()->isAdmin()) {
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'DELETE',
                'description' => 'Eliminó la orden de compra ID ' . $purchaseOrder->id . ' (N°: ' . $purchaseOrder->order_number . ') con estado: ' . $purchaseOrder->status . '. Total: ' . $purchaseOrder->total_amount,
                'affected_table' => 'purchase_orders',
                'record_id' => $purchaseOrder->id,
                'ip_address' => request()->ip(),
                'date' => now(),
            ]);

            $purchaseOrder->delete();

            return true;
        }

        return 'Unauthorized user';
    }
}
