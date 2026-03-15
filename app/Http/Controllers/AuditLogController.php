<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditLog;

class AuditLogController extends Controller
{

    public function index()
    {
        //Traemos todos los logs, ordenados por fecha(los mas nuevos)
        $logs = AuditLog::with('user')->orderBy('date', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $logs
        ]);
    }
}
