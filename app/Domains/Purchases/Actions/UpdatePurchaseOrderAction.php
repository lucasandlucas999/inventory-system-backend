<?php

namespace App\Domains\Purchases\Actions;

use App\Models\AuditLog;
use App\Domains\Purchases\Models\PurchaseOrder;
use App\Domains\Inventory\Models\StockMovement;
use App\Domains\Products\Models\Product;
use Illuminate\Support\Facades\DB;

class UpdatePurchaseOrderAction
{
    public function execute(PurchaseOrder $purchaseOrder, array $data): PurchaseOrder|string
    {
        if (auth()->user()->isAdmin()) {
            return DB::transaction(function () use ($purchaseOrder, $data) {
                $oldStatus = $purchaseOrder->status;

                $oldData = $purchaseOrder->only(['supplier_id', 'date', 'status']);

                $purchaseOrder->update($data);

                $newData = $purchaseOrder->fresh()->only(['supplier_id', 'date', 'status']);
                $newStatus = $newData['status'];

                $changes = [];
                foreach ($oldData as $field => $oldValue) {
                    $newValue = $newData[$field];
                    if ((string) $oldValue !== (string) $newValue) {
                        $changes[] = $field . ": '" . $oldValue . "' -> '" . $newValue . "'";
                    }
                }

                $stockNote = '';

                if ($oldStatus !== 'COMPLETED' && $newStatus === 'COMPLETED') {
                    $this->applyStockIn($purchaseOrder);
                    $stockNote = '. Stock incrementado por recepción de mercadería.';
                } elseif ($oldStatus === 'COMPLETED' && $newStatus === 'CANCELLED') {
                    $this->applyStockOut($purchaseOrder);
                    $stockNote = '. Stock revertido por cancelación de orden completada.';
                }

                $description = 'Actualizó la orden de compra ID ' . $purchaseOrder->id . ' (N°: ' . $purchaseOrder->order_number . ')';
                if (!empty($changes)) {
                    $description .= '. Cambios: ' . implode(', ', $changes);
                }
                $description .= $stockNote;

                AuditLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'UPDATE',
                    'description' => $description,
                    'affected_table' => 'purchase_orders',
                    'record_id' => $purchaseOrder->id,
                    'ip_address' => request()->ip(),
                    'date' => now(),
                ]);

                return $purchaseOrder->fresh()->load('details', 'supplier', 'user');
            });
        }

        return 'Unauthorized user';
    }

    private function applyStockIn(PurchaseOrder $order): void
    {
        foreach ($order->details as $detail) {
            StockMovement::create([
                'product_id' => $detail->product_id,
                'type' => 'IN',
                'quantity' => $detail->quantity,
                'date' => $order->date,
                'reference' => $order->order_number,
                'user_id' => auth()->id(),
                'notes' => 'Compra completada - Orden N°: ' . $order->order_number,
            ]);

            Product::where('id', $detail->product_id)
                ->increment('current_stock', $detail->quantity);
        }
    }

    private function applyStockOut(PurchaseOrder $order): void
    {
        foreach ($order->details as $detail) {
            StockMovement::create([
                'product_id' => $detail->product_id,
                'type' => 'OUT',
                'quantity' => $detail->quantity,
                'date' => now()->toDateString(),
                'reference' => $order->order_number,
                'user_id' => auth()->id(),
                'notes' => 'Cancelación de compra completada - Orden N°: ' . $order->order_number,
            ]);

            Product::where('id', $detail->product_id)
                ->decrement('current_stock', $detail->quantity);
        }
    }
}
