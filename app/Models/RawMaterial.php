<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RawMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'unit',
        'current_stock',
        'minimum_threshold',
    ];



    /**
     * Get the BOM items for the raw material.
     */
    public function menuBoms(): HasMany
    {
        return $this->hasMany(MenuBom::class);
    }
}
