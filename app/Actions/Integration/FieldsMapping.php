<?php

namespace App\Actions\Integration;

use App\Data\Integration\FieldsMappingData;
use App\Enums\Integration as IntegrationSystem;
use App\Models\Integration;
use App\Models\IntegrationField;
use Illuminate\Support\Str;

final class FieldsMapping extends IntegrationAction
{
    public function handle(string $integration, FieldsMappingData $fieldsMappingData): void
    {
        $integrationSystem = IntegrationSystem::from(Str::replace('-', '_', $integration));

        /** @var Integration $integration */
        $integration = Integration::query()->where('slug', $integrationSystem->value)->firstOrFail();

        $fieldsMappingCollection = collect($fieldsMappingData->fields);

        $integration->fields()->each(function (IntegrationField $field) {
            $field->localFields()->detach();
        });

        $integration->fields()->each(function(IntegrationField $field) use ($fieldsMappingCollection) {

            $fieldsIDs = $fieldsMappingCollection
                ->pluck('local_field', 'integration_field')
                ->toArray();

            if ( ! in_array($field->id, array_keys($fieldsIDs))) {
                return;
            }

            $localFieldID = $fieldsIDs[$field->id];

            if ($localFieldID !== 0) {
                $field->localFields()->attach($localFieldID);
            }
        });

        $integration->fields()->update([
            'is_primary' => false,
        ]);

        $integration->fields()->find($fieldsMappingCollection->where(
            'local_field', 0)->first()['integration_field'] ?? 0)?->update([
            'is_primary' => true,
        ]);
    }
}
