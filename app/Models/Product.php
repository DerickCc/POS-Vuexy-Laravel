<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'photo',
        'stock',
        'restock_threshold',
        'uom',
        'purchase_price',
        'selling_price',
        'remarks',
        'created_by',
        'updated_by',
    ];

    public static function boot()
    {
        parent::boot();

        // generate unique code after product is created
        static::created(function ($product) {
            $product->code = 'PRD' . str_pad($product->id, 7, '0', STR_PAD_LEFT);
            $product->save();
        });
    }

    function salesOrderProductDetails() {
        return $this->hasMany(SalesOrderProductDetail::class);
    }

    function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
