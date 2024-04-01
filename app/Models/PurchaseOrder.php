<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'purchase_date',
        'supplier_id',
        'total_item',
        'total_price',
        'status',
        'remarks',
        'created_by',
        'updated_by',
    ];
}
