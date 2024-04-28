<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesOrderProductDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'so_id',
        'product_id',
        'ori_selling_price',
        'selling_price',
        'quantity',
        'total_price',
        'profit',
        'created_by',
        'updated_by',
    ];

    function soId(): BelongsTo
    {
        return $this->belongsTo(SalesOrder::class, 'so_id');
    }

    function productId(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    function stockReduction() {
        return $this->hasMany(StockReduction::class, 'so_product_detail_id');
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
