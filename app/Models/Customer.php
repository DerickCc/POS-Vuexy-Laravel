<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'address',
        'license_plate',
        'phone_no',
        'member',
        'created_by',
        'updated_by',
    ];

    public static function boot()
    {
        parent::boot();

        // generate unique code
        static::created(function ($customer) {
            $customer->code = 'CUS' . str_pad($customer->id, 7, '0', STR_PAD_LEFT);
            $customer->save();
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
