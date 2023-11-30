<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Meta extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = [
        'category_id',
        'field_type',
        'name',
        'slug',
        'is_required',
        'value'
    ];

    // =================== //
    //      RELATIONS      //
    // =================== //

    /**
     * Meta field category.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // ======================= //
    //      OTHER METHODS      //
    // ======================= //

    /**
     * @return SlugOptions
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
                          ->generateSlugsFrom('name')
                          ->saveSlugsTo('slug')
                          ->usingSeparator('_');
    }
}
