<?php

namespace App\Domains\Customers\Controllers;

use App\Domains\Customers\Actions\UpdateCustomerAction;
use App\Domains\Customers\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UpdateCustomerController extends Controller
{
    public function __invoke(Request $request, Customer $customer, UpdateCustomerAction $action)
    {
        try {

            $validated = $request->validate(

                [
                    'document_number' => 'sometimes|required|string|max:255|unique:customers,document_number,' . $customer->id,
                    'name' => 'sometimes|required|string|max:255',
                    'address' => 'sometimes|nullable|string|max:255',
                    'phone' => 'sometimes|nullable|string|max:20',
                    'email' => 'sometimes|nullable|email|max:255|unique:customers,email,' . $customer->id,
                    'credit_limit' => 'sometimes|required|numeric|min:0',
                    'is_active' => 'sometimes|required|boolean',
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
                'data' => $action->execute($customer, $validated)
            ],200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
