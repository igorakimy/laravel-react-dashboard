<?php

namespace App\Actions\Integration;

use App\Data\Integration\FieldsMappedData;
use App\Models\Integration;
use Illuminate\Support\Str;
use Spatie\LaravelData\DataCollection;

final class FetchAllMappedFields extends IntegrationAction
{
    public function handle(string $integration): DataCollection
    {
        $integrationSlug = Str::replace('-', '_', $integration);

        /** @var Integration $integration */
        $integration = Integration::query()->where(
            'slug',
            $integrationSlug
        )->firstOrFail();

        $integrationFields = $integration
            ->fields()
            ->whereHas('localFields')
            ->get();

        $primaryField = $integration
            ->fields()
            ->where('is_primary', true)
            ->first();

        if ($primaryField) {
            $integrationFields->push($primaryField);
        }

        $integrationFields = $integrationFields->sortBy('is_primary');

        return FieldsMappedData::collection($integrationFields);
    }
}
