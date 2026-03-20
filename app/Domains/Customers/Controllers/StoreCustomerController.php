<?php

namespace App\Domains\Customers\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Domains\Customers\Actions\StoreCustomerAction;


class StoreCustomerController extends Controller
{
    public function __invoke(Request $request, StoreCustomerAction $action)
    {
        try {

            $validated = $request->validate(
                [
                    'document_number' => 'required|string|max:255|unique:customers,document_number',
                    'name' => 'required|string|max:255',
                    'address' => 'nullable|string|max:255',
                    'phone' => 'nullable|string|max:20',
                    'email' => 'nullable|email|max:255|unique:customers,email',
                    'credit_limit' => 'nullable|numeric|min:0',
                    'is_active' => 'nullable|boolean',
                ],
                [
                    'required' => 'El campo :attribute es obligatorio.',
                    'string' => 'El campo :attribute debe ser un texto.',
                    'numeric' => 'El campo :attribute debe ser numerico.',
                    'min' => 'El campo :attribute no puede ser menor a :min.',
                    'max' => 'El campo :attribute no puede superar :max caracteres.',
                    'boolean' => 'El campo :attribute debe ser verdadero o falso.',
                    'email' => 'El campo :attribute debe ser un correo electronico valido.',
                    'unique' => 'El valor de :attribute ya esta en uso.',
                ],
                [
                    'document_number' => 'numero de documento',
                    'name' => 'nombre',
                    'address' => 'direccion',
                    'phone' => 'telefono',
                    'email' => 'correo electronico',
                    'credit_limit' => 'limite de credito',
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
