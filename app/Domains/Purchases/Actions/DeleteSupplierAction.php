<?php

namespace App\Domains\Purchases\Actions;

use App\Models\AuditLog;
use App\Domains\Purchases\Models\Supplier;

class DeleteSupplierAction
{
    public function execute(Supplier $supplier): bool|string
    {
        if (auth()->user()->isAdmin()) {
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'DELETE',
                'description' => 'Eliminó el proveedor ID ' . $supplier->id . ' con el nombre: ' . $supplier->name . ' y documento: ' . $supplier->document_number,
                'affected_table' => 'suppliers',
                'record_id' => $supplier->id,
                'ip_address' => request()->ip(),
                'date' => now(),
            ]);

            $supplier->delete();

            return true;
        }

        return 'Unauthorized user';
    }
}
