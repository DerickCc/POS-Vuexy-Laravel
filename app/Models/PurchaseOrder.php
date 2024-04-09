<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_code',
        'purchase_date',
        'supplier_id',
        'total_item',
        'grand_total',
        'status',
        'remarks',
        'created_by',
        'updated_by',
    ];

    public static function boot()
    {
        parent::boot();

        static::created(function ($po) {
            $po->po_code = 'PO' . str_pad($po->id, 7, '0', STR_PAD_LEFT);
            $po->save();
        });
    }

    function supplierId(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
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
