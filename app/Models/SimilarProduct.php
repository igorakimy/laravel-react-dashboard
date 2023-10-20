<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\SimilarProduct
 *
 * @method static \Illuminate\Database\Eloquent\Builder|SimilarProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SimilarProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SimilarProduct query()
 * @property int $id
 * @property int|null $product_id
 * @property int|null $similar_product_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SimilarProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SimilarProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SimilarProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SimilarProduct whereSimilarProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SimilarProduct whereUpdatedAt($value)
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
