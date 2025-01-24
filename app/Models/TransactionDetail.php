<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionDetail extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'transaction_id',
        'product_id',
        'price',
        'quantity',
        'subtotal',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
