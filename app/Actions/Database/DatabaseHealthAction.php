<?php

namespace App\Actions\Database;

use Illuminate\Support\Facades\DB;
use Exception;

class DatabaseHealthAction
{
    /**
     * Verifica el estado de la conexión a la base de datos.
     *
     * @return array
     */
    public function execute(): array
    {
        try {
            DB::connection()->getPdo();
            return [
                'status' => 'OK',
                'database' => DB::connection()->getDatabaseName(),
                'message' => 'Connected successfully to the database.',
            ];
        } catch (Exception $e) {
            return [
                'status' => 'ERROR',
                'message' => 'Could not connect to the database. Error: ' . $e->getMessage(),
            ];
        }
    }
}
