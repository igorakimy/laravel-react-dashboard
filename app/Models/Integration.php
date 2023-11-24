<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Integration extends Model
{
    protected $guarded = [];

    /**
     * Integration fields.
     *
     * @return HasMany
     */
    public function fields(): HasMany
    {
        return $this->hasMany(IntegrationField::class, 'integration_id');
    }
}
