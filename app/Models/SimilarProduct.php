<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\SimilarProduct
 *
 * @method static \Illuminate\Database\Eloquent\Builder|SimilarProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SimilarProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SimilarProduct query()
 * @mixin \Eloquent
 */
class SimilarProduct extends Pivot
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * @var array
     */
    protected $guarded = [];
}
