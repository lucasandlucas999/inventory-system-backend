<?php

namespace App\Domains\Inventory\Models;

use App\Domains\Products\Models\Product;
use App\Models\User;
use Database\Factories\Domains\Inventory\StockMovementFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockMovement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'type',
        'quantity',
        'date',
        'reference',
        'user_id',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'date' => 'date',
    ];

    protected static function newFactory()
    {
        return StockMovementFactory::new();
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
