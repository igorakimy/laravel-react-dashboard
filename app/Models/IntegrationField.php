<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class IntegrationField extends Model
{
    protected $guarded = [];

    protected $casts = [
        'values' => 'array',
    ];

    /**
     * Integration system.
     *
     * @return BelongsTo
     */
    public function integration(): BelongsTo
    {
        return $this->belongsTo(Integration::class);
    }

    /**
     * Local fields.
     *
     * @return BelongsToMany
     */
    public function localFields(): BelongsToMany
    {
        return $this->belongsToMany(
            LocalField::class,
            'integration_field_local_field',
            'integration_field_id',
            'local_field_id'
        );
    }
}
