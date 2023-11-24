<?php

namespace App\Actions\Integration;

use App\Data\Integration\IntegrationData;
use App\Data\IntegrationField\IntegrationFieldData;
use App\Models\Integration;
use App\Models\IntegrationField;
use Exception;
use Illuminate\Support\Str;
use Spatie\LaravelData\DataCollection;

final class FetchAllIntegrationFields extends IntegrationAction
{
    public function __construct(
        private readonly StoreOrUpdateIntegrationFields $storeOrUpdateIntegrationFields,
    ) {
    }

    /**
     * @throws Exception
     */
    public function handle(string $integration): DataCollection
    {
        $integrationSlug = Str::replace('-', '_', $integration);

        /** @var Integration $integration */
        $integration = Integration::query()->where('slug', $integrationSlug)->first();
        $integrationData = IntegrationData::from($integration);

        // store or update integration fields from api to db.
        $this->storeOrUpdateIntegrationFields->handle($integrationData);

        $integrationFields = IntegrationField::query()->with('integration')->where(
            'integration_id',
            $integration->id
        )->get();

        return IntegrationFieldData::collection($integrationFields)
                                   ->include('integration');
    }
}
