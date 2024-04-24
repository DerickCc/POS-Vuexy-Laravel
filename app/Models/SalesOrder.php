<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'so_code',
        'sales_date',
        'customer_id',
        'payment_type',
        'sub_total',
        'discount',
        'grand_total',
        'paid_amount',
        'return_amount',
        'remarks',
        'status',
        'created_by',
        'updated_by',
    ];

    public static function boot()
    {
        parent::boot();

        static::created(function ($so) {
            $so->so_code = 'SO' . str_pad($so->id, 7, '0', STR_PAD_LEFT);
            $so->save();
        });
    }

    function customerId(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    function soProductDetail() {
        return $this->hasMany(SalesOrderProductDetail::class, 'so_id');
    }

    function soServiceDetail() {
        return $this->hasMany(SalesOrderServiceDetail::class, 'so_id');
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
