<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'pic',
        'address',
        'phone_no',
        'receivables',
        'remarks',
        'created_by',
        'updated_by',
    ];

    public static function boot()
    {
        parent::boot();

        // generate unique code after supplier is created
        static::created(function ($supplier) {
            $supplier->code = 'SUP' . str_pad($supplier->id, 7, '0', STR_PAD_LEFT);
            $supplier->save();
        });
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
