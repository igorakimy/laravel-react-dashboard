<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\CategoryField
 *
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryField newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryField newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryField query()
 * @property int $id
 * @property int|null $category_id
 * @property int|null $field_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryField whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryField whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryField whereFieldId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryField whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryField whereUpdatedAt($value)
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
