<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'laundry_item_id',
        'amount',
        'unit_price',
        'quantity',
        'method',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'quantity' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function laundryItem(): BelongsTo
    {
        return $this->belongsTo(LaundryItem::class);
    }
}