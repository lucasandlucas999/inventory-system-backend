<?php

namespace App\Domains\Purchases\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domains\Purchases\Models\PurchaseOrder;
use App\Domains\Purchases\Actions\UpdatePurchaseOrderAction;

class UpdatePurchaseOrderController extends Controller
{
    public function __invoke(Request $request, PurchaseOrder $purchaseOrder, UpdatePurchaseOrderAction $action)
    {
        try {
            $validated = $request->validate(
                [
                    'supplier_id' => 'sometimes|required|exists:suppliers,id',
                    'date' => 'sometimes|required|date',
                    'status' => 'sometimes|required|in:PENDING,PENDING_RECEIPT,COMPLETED,CANCELLED',
                ],
                [
                    'required' => 'El campo :attribute es obligatorio.',
                    'date' => 'El campo :attribute debe ser una fecha valida.',
                    'exists' => 'El :attribute seleccionado no existe.',
                    'in' => 'El campo :attribute debe ser uno de: PENDING, PENDING_RECEIPT, COMPLETED, CANCELLED.',
                ],
                [
                    'supplier_id' => 'proveedor',
                    'date' => 'fecha',
                    'status' => 'estado',
                ]
            );

            return response()->json([
                'success' => true,
                'data' => $action->execute($purchaseOrder, $validated)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
