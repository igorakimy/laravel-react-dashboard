<?php

namespace App\Models;

use App\Enums\FieldType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class LocalField extends Model
{
    use HasSlug;

    protected $guarded = [];

    protected $casts = [
        'field_type' => FieldType::class,
        'validations' => 'array',
        'properties' => 'array',
    ];

    /**
     * Integration fields.
     *
     * @return BelongsToMany
     */
    public function integrationFields(): BelongsToMany
    {
        return $this->belongsToMany(
            IntegrationField::class,
            'integration_field_local_field',
            'local_field_id',
            'integration_field_id'
        );
    }

    /**
     * Get slug options.
     *
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
