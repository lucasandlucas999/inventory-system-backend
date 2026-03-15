<?php

namespace App\Domains\Customers\Models;

use Database\Factories\Domains\Customers\CustomerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Domains\Sales\Models\Invoice;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'document_number',
        'name',
        'address',
        'phone',
        'email',
        'credit_limit',
        'is_active',
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected static function newFactory()
    {
        return CustomerFactory::new ();
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
