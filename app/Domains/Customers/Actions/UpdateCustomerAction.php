<?php

namespace App\Domains\Customers\Actions;

use App\Models\AuditLog;
use App\Domains\Customers\Models\Customer;

class UpdateCustomerAction
{
    public function execute(Customer $customer, array $data)
    {
        if (auth()->user()->isAdmin()) {

            //auditoria
            $oldData = $customer->only([
                'document_number',
                'name',
                'address',
                'phone',
                'email',
                'credit_limit',
                'is_active',
            ]);

            //actualizar el cliente
            $customer->update($data);

            $newData = $customer->fresh()->only([
                'document_number',
                'name',
                'address',
                'phone',
                'email',
                'credit_limit',
                'is_active',
            ]);

            $changes = [];

            foreach ($oldData as $field => $oldValue) {
                $newValue = $newData[$field];

                if ((string) $oldValue !== (string) $newValue) {
                    $changes[] = $field . ": '" . $oldValue . "' -> '" . $newValue . "'";
                }
            }

            $description = 'Actualizó el cliente ID ' . $customer->id;

            if (!empty($changes)) {
                $description .= '. Cambios: ' . implode(', ', $changes);
            }

            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'UPDATE',
                'description' => $description,
                'affected_table' => 'customers',
                'record_id' => $customer->id,
                'ip_address' => request()->ip(),
                'date' => now(),
            ]);

            return $customer;
        }

        return 'Unauthorized user';
    }
}
