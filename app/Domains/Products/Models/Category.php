<?php

namespace App\Domains\Products\Models;

use Database\Factories\Domains\Products\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function newFactory()
    {
        return CategoryFactory::new();
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
