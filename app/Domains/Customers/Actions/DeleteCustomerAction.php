<?php

namespace App\Domains\Customers\Actions;

use App\Models\AuditLog;
use App\Domains\Customers\Models\Customer;

class DeleteCustomerAction
{
    public function execute(Customer $customer)
    {
        if (auth()->user()->isAdmin()) {
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'DELETE',
                'description' => 'Elimino el cliente ID ' . $customer->id . ' con el nombre: ' . $customer->name . ' y documento: ' . $customer->document_number,
                'affected_table' => 'customers',
                'record_id' => $customer->id,
                'ip_address' => request()->ip(),
                'date' => now(),
            ]);

            $customer->delete();

            return true;
        }

        return 'Unauthorized user';
    }
}
