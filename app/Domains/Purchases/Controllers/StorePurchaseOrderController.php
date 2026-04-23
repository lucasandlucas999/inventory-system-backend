<?php

namespace App\Domains\Purchases\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domains\Purchases\Actions\StorePurchaseOrderAction;

class StorePurchaseOrderController extends Controller
{
    public function __invoke(Request $request, StorePurchaseOrderAction $action)
    {
        try {
            $validated = $request->validate(
                [
                    'order_number' => 'nullable|string|max:255|unique:purchase_orders,order_number',
                    'supplier_id' => 'required|exists:suppliers,id',
                    'date' => 'required|date',
                    'status' => 'nullable|in:PENDING,PENDING_RECEIPT,COMPLETED,CANCELLED',
                    'details' => 'required|array|min:1',
                    'details.*.product_id' => 'required|exists:products,id',
                    'details.*.quantity' => 'required|numeric|min:0.01',
                    'details.*.unit_cost' => 'required|numeric|min:0',
                ],
                [
                    'required' => 'El campo :attribute es obligatorio.',
                    'string' => 'El campo :attribute debe ser un texto.',
                    'numeric' => 'El campo :attribute debe ser numerico.',
                    'min' => 'El campo :attribute no puede ser menor a :min.',
                    'max' => 'El campo :attribute no puede superar :max caracteres.',
                    'array' => 'El campo :attribute debe ser un arreglo.',
                    'exists' => 'El :attribute seleccionado no existe.',
                    'in' => 'El campo :attribute debe ser uno de: PENDING, PENDING_RECEIPT, COMPLETED, CANCELLED.',
                    'unique' => 'El valor de :attribute ya esta en uso.',
                ],
                [
                    'order_number' => 'numero de orden',
                    'supplier_id' => 'proveedor',
                    'date' => 'fecha',
                    'status' => 'estado',
                    'details' => 'detalles',
                    'details.*.product_id' => 'producto',
                    'details.*.quantity' => 'cantidad',
                    'details.*.unit_cost' => 'costo unitario',
                ]
            );

            return response()->json([
                'success' => true,
                'data' => $action->execute($validated)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
