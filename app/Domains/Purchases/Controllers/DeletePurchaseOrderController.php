<?php

namespace App\Domains\Purchases\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Purchases\Models\PurchaseOrder;
use App\Domains\Purchases\Actions\DeletePurchaseOrderAction;

class DeletePurchaseOrderController extends Controller
{
    public function __invoke(PurchaseOrder $purchaseOrder, DeletePurchaseOrderAction $action)
    {
        try {
            $result = $action->execute($purchaseOrder);

            if ($result !== true) {
                return response()->json([
                    'success' => false,
                    'message' => $result
                ], 403);
            }

            return response()->json([
                'success' => true,
                'message' => 'Orden de compra eliminada correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
