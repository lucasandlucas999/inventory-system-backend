<?php

namespace App\Domains\Purchases\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domains\Purchases\Models\Supplier;
use App\Domains\Purchases\Actions\UpdateSupplierAction;

class UpdateSupplierController extends Controller
{
    public function __invoke(Request $request, Supplier $supplier, UpdateSupplierAction $action)
    {
        try {
            $validated = $request->validate(
                [
                    'document_number' => 'sometimes|required|string|max:255|unique:suppliers,document_number,' . $supplier->id,
                    'name' => 'sometimes|required|string|max:255',
                    'phone' => 'sometimes|nullable|string|max:20',
                    'email' => 'sometimes|nullable|email|max:255|unique:suppliers,email,' . $supplier->id,
                    'address' => 'sometimes|nullable|string|max:255',
                    'is_active' => 'sometimes|required|boolean',
                ],
                [
                    'required' => 'El campo :attribute es obligatorio.',
                    'string' => 'El campo :attribute debe ser un texto.',
                    'max' => 'El campo :attribute no puede superar :max caracteres.',
                    'boolean' => 'El campo :attribute debe ser verdadero o falso.',
                    'email' => 'El campo :attribute debe ser un correo electronico valido.',
                    'unique' => 'El valor de :attribute ya esta en uso.',
                ],
                [
                    'document_number' => 'numero de documento',
                    'name' => 'nombre',
                    'phone' => 'telefono',
                    'email' => 'correo electronico',
                    'address' => 'direccion',
                    'is_active' => 'estado',
                ]
            );

            return response()->json([
                'success' => true,
                'data' => $action->execute($supplier, $validated)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
