<?php

namespace App\Domains\Products\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domains\Products\Actions\CreateProductAction;

class StoreProductController extends Controller 
{
    public function __invoke( Request $request, CreateProductAction $action )
    {
        try {
            $validated = $request->validate(
                
             [
                'code' => 'required|string|max:255|unique:products,code',
                'name' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'purchase_price' => 'required|numeric|min:0',
                'sale_price' => 'required|numeric|min:0',
                'current_stock' => 'required|numeric|min:0',
                'minimum_stock' => 'required|numeric|min:0',
                'is_active' => 'required|boolean',
            ],
            [
                'required' => 'El campo :attribute es obligatorio.',
                'numeric' => 'El campo :attribute debe ser numérico.',
                'min' => 'El campo :attribute no puede ser negativo.',
                'boolean' => 'El campo :attribute debe ser verdadero o falso.',
                'exists' => 'La :attribute seleccionada no existe.',
                'unique' => 'El :attribute ya está en uso.',
            ],
            [
                'code' => 'código',
                'name' => 'nombre',
                'category_id' => 'categoría',
                'purchase_price' => 'precio de compra',
                'sale_price' => 'precio de venta',
                'current_stock' => 'stock actual',
                'minimum_stock' => 'stock mínimo',
                'is_active' => 'estado',
            ]
            
            );

            return response()->json([
                'success' =>true,
                'data' =>$action->execute($validated)
            ], 201);
        }catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}