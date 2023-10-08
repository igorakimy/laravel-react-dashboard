<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\CategoryField
 *
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryField newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryField newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryField query()
 * @mixin \Eloquent
 */
class CategoryField extends Pivot
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
