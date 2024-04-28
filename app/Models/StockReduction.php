<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockReduction extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_stock_detail_id',
        'so_product_detail_id',
        'quantity',
    ];

    function productStockDetailId(): BelongsTo
    {
        return $this->belongsTo(ProductStockDetail::class, 'product_stock_detail_id');
    }

    function soProductDetailId(): BelongsTo
    {
        return $this->belongsTo(SalesOrderProductDetail::class, 'so_product_detail_id');
    }
}
