<?php

namespace App\Services\ZohoCrm\Resources;

use App\Data\Integration\ZohoCrm\Record\Product\ProductResponseData;
use App\Data\Integration\ZohoCrm\Record\Product\ProductsResponseData;
use App\Enums\ZohoCrmModule;
use App\Services\ZohoCrm\ZohoCrmService;
use Spatie\LaravelData\Data;

final class RecordResource
{
    public function __construct(
       public readonly ZohoCrmService $service,
    ) {
    }

    /**
     * Get module records with specified fields.
     *
     * @param  ZohoCrmModule  $module
     * @param  array  $fields
     *
     * @return Data
     */
    public function getList(ZohoCrmModule $module, array $fields): Data
    {
        $url = "/$module->value";

        $request = $this->service->buildRequestWithToken();

        $response = $this->service->get($request, $url, [
            'fields' => implode(',', $fields),
        ]);

        return match ($module) {
            ZohoCrmModule::PRODUCTS => ProductsResponseData::fromResponse($response),
            default => Data::from(),
        };
    }

    /**
     * Get module record by id.
     *
     * @param  ZohoCrmModule  $module
     * @param  string  $id
     *
     * @return Data
     */
    public function getByID(ZohoCrmModule $module, string $id): Data
    {
        $url = "/$module->value/$id";

        $request = $this->service->buildRequestWithToken();

        $response = $this->service->get($request, $url);

        return match ($module) {
            ZohoCrmModule::PRODUCTS => ProductResponseData::fromResponse($response),
            default => Data::from(),
        };
    }
}
