<?php

namespace App\Domains\Purchases\Actions;

use App\Models\AuditLog;
use App\Domains\Purchases\Models\Supplier;

class UpdateSupplierAction
{
    public function execute(Supplier $supplier, array $data): Supplier|string
    {
        if (auth()->user()->isAdmin()) {
            $oldData = $supplier->only([
                'document_number',
                'name',
                'phone',
                'email',
                'address',
                'is_active',
            ]);

            $supplier->update($data);

            $newData = $supplier->fresh()->only([
                'document_number',
                'name',
                'phone',
                'email',
                'address',
                'is_active',
            ]);

            $changes = [];
            foreach ($oldData as $field => $oldValue) {
                $newValue = $newData[$field];
                if ((string) $oldValue !== (string) $newValue) {
                    $changes[] = $field . ": '" . $oldValue . "' -> '" . $newValue . "'";
                }
            }

            $description = 'Actualizó el proveedor ID ' . $supplier->id;
            if (!empty($changes)) {
                $description .= '. Cambios: ' . implode(', ', $changes);
            }

            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'UPDATE',
                'description' => $description,
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
