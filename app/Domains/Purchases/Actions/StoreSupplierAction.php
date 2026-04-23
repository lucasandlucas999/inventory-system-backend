<?php

namespace App\Domains\Purchases\Actions;

use App\Models\AuditLog;
use App\Domains\Purchases\Models\Supplier;

class StoreSupplierAction
{
    public function execute(array $data): Supplier
    {
        if (auth()->user()->isAdmin()) {
            $supplier = Supplier::create($data);

            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'CREATE',
                'description' => 'Creó el proveedor ID ' . $supplier->id . ' con el nombre: ' . $supplier->name . ' y documento: ' . $supplier->document_number,
                'affected_table' => 'suppliers',
                'record_id' => $supplier->id,
                'ip_address' => request()->ip(),
                'date' => now(),
            ]);

            return $supplier;
        }

        return 'Unauthorized user';
    }
}
