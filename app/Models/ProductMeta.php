<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductMeta extends Model
{
    protected $fillable = [
        'product_id',
        'meta_id',
        'value',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function meta(): BelongsTo
    {
        return $this->belongsTo(Meta::class);
    }
}
