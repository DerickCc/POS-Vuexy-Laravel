<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockReduction extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_stock_detail_id',
        'so_product_detail_id',
        'quantity',
    ];
}
