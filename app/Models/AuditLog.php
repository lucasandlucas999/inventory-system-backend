<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{ //campos que permitimos que se modifiquen
    protected $fillable = [
        'user_id', 'action', 'description', 'affected_table', 'record_id', 'ip_address', 'date',
    ];




    // Relación: Un log de auditoría pertenece a un Usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
