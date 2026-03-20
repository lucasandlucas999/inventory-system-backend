<?php

namespace App\Domains\Products\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domains\Products\Models\Product;
use App\Domains\Products\Actions\UpdateProductAction;

class UpdateProductController extends Controller
{
    public function __invoke(Request $request, Product $product, UpdateProductAction $action)
    {
        try {
            $validated = $request->validate(
                [
                    'code' => 'sometimes|required|string|max:255|unique:products,code,' . $product->id,
                    'name' => 'sometimes|required|string|max:255',
                    'category_id' => 'sometimes|required|exists:categories,id',
                    'purchase_price' => 'sometimes|required|numeric|min:0',
                    'sale_price' => 'sometimes|required|numeric|min:0',
                    'current_stock' => 'sometimes|required|numeric|min:0',
                    'minimum_stock' => 'sometimes|required|numeric|min:0',
                    'is_active' => 'sometimes|required|boolean',
                ],
                [
                    'required' => 'El campo :attribute es obligatorio.',
                    'string' => 'El campo :attribute debe ser un texto.',
                    'numeric' => 'El campo :attribute debe ser numérico.',
                    'min' => 'El campo :attribute no puede ser menor a :min.',
                    'max' => 'El campo :attribute no puede superar :max caracteres.',
                    'boolean' => 'El campo :attribute debe ser verdadero o falso.',
                    'exists' => 'El valor seleccionado para :attribute no existe.',
                    'unique' => 'El valor de :attribute ya está en uso.',
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
                'success' => true,
                'data' => $action->execute($product, $validated)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
