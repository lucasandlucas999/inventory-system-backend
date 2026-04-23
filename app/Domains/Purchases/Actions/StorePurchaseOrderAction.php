<?php

namespace App\Domains\Purchases\Actions;

use App\Models\AuditLog;
use App\Domains\Purchases\Models\PurchaseOrder;
use App\Domains\Purchases\Models\PurchaseOrderDetail;
use App\Domains\Inventory\Models\StockMovement;
use App\Domains\Products\Models\Product;
use Illuminate\Support\Facades\DB;

class StorePurchaseOrderAction
{
    public function execute(array $data): PurchaseOrder|string
    {
        if (auth()->user()->isAdmin()) {
            return DB::transaction(function () use ($data) {
                $details = $data['details'];
                unset($data['details']);

                $totalAmount = collect($details)->sum(fn($d) => $d['quantity'] * $d['unit_cost']);
                $status = $data['status'] ?? 'PENDING';

                $order = PurchaseOrder::create([
                    'order_number' => $data['order_number'] ?? 'PO-' . strtoupper(uniqid()),
                    'supplier_id' => $data['supplier_id'],
                    'user_id' => auth()->id(),
                    'date' => $data['date'],
                    'total_amount' => $totalAmount,
                    'status' => $status,
                ]);

                foreach ($details as $detail) {
                    PurchaseOrderDetail::create([
                        'purchase_order_id' => $order->id,
                        'product_id' => $detail['product_id'],
                        'quantity' => $detail['quantity'],
                        'unit_cost' => $detail['unit_cost'],
                        'subtotal' => $detail['quantity'] * $detail['unit_cost'],
                    ]);
                }

                if ($status === 'COMPLETED') {
                    $this->applyStockIn($order, $details);
                }

                AuditLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'CREATE',
                    'description' => 'Creó la orden de compra ID ' . $order->id . ' (N°: ' . $order->order_number . ') con ' . count($details) . ' detalle(s). Total: ' . $totalAmount . ($status === 'COMPLETED' ? '. Stock actualizado.' : ''),
                    'affected_table' => 'purchase_orders',
                    'record_id' => $order->id,
                    'ip_address' => request()->ip(),
                    'date' => now(),
                ]);

                return $order->load('details', 'supplier', 'user');
            });
        }

        return 'Unauthorized user';
    }

    private function applyStockIn(PurchaseOrder $order, array $details): void
    {
        foreach ($details as $detail) {
            StockMovement::create([
                'product_id' => $detail['product_id'],
                'type' => 'IN',
                'quantity' => $detail['quantity'],
                'date' => $order->date,
                'reference' => $order->order_number,
                'user_id' => auth()->id(),
                'notes' => 'Compra - Orden N°: ' . $order->order_number,
            ]);

            Product::where('id', $detail['product_id'])
                ->increment('current_stock', $detail['quantity']);
        }
    }
}
