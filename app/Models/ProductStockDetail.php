<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStockDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'purchase_price',
        'quantity',
    ];

    function stockReduction() {
        return $this->hasMany(StockReduction::class, 'product_stock_detail_id');
    }
}
