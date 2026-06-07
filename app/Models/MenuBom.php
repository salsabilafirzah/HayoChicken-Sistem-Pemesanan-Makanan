<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuBom extends Model
{
    use HasFactory;

    protected $table = 'menu_bom';

    protected $fillable = [
        'product_id',
        'raw_material_id',
        'quantity_needed',
    ];

    /**
     * Get the product associated with the BOM entry.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the raw material associated with the BOM entry.
     */
    public function rawMaterial(): BelongsTo
    {
        return $this->belongsTo(RawMaterial::class);
    }
}
