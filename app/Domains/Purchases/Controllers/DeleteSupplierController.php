<?php

namespace App\Domains\Purchases\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Purchases\Models\Supplier;
use App\Domains\Purchases\Actions\DeleteSupplierAction;

class DeleteSupplierController extends Controller
{
    public function __invoke(Supplier $supplier, DeleteSupplierAction $action)
    {
        try {
            $result = $action->execute($supplier);

            if ($result !== true) {
                return response()->json([
                    'success' => false,
                    'message' => $result
                ], 403);
            }

            return response()->json([
                'success' => true,
                'message' => 'Proveedor eliminado correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
