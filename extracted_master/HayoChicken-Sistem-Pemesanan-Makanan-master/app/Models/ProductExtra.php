<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductExtra extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'additional_price',
        'is_available',
        'sort_order',
    ];

    /**
     * Get the product that owns the extra.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
