<?php

namespace App\Domains\Purchases\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domains\Purchases\Actions\StoreSupplierAction;

class StoreSupplierController extends Controller
{
    public function __invoke(Request $request, StoreSupplierAction $action)
    {
        try {
            $validated = $request->validate(
                [
                    'document_number' => 'required|string|max:255|unique:suppliers,document_number',
                    'name' => 'required|string|max:255',
                    'phone' => 'nullable|string|max:20',
                    'email' => 'nullable|email|max:255|unique:suppliers,email',
                    'address' => 'nullable|string|max:255',
                    'is_active' => 'nullable|boolean',
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
