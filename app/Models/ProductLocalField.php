<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductLocalField extends Pivot
{
    protected $table = 'product_local_fields';

    protected $fillable = [
        'product_id',
        'local_field_id',
        'value',
        'custom_props',
    ];

    protected $primaryKey = [
        'product_id',
        'local_field_id',
    ];

    public $incrementing = true;

    public $timestamps = true;
}
