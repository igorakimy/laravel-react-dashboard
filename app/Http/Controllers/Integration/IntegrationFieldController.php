<?php

namespace App\Http\Controllers\Integration;

use App\Actions\Integration\FetchAllIntegrationFields;
use App\Actions\Integration\FetchAllMappedFields;
use App\Actions\Integration\FieldsMapping;
use App\Data\Integration\FieldsMappingData;
use App\Http\Controllers\ApiController;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class IntegrationFieldController extends ApiController
{
    public function __construct(
        private readonly FetchAllIntegrationFields $fetchAllIntegrationFieldsAction,
        private readonly FieldsMapping $fieldsMappingAction,
        private readonly FetchAllMappedFields $fetchAllMappedFieldsAction,
    ) {
    }

    /**
     * Get all integration system fields.
     *
     * @param  string  $integration
     *
     * @return Response
     * @throws Exception
     */
    public function index(string $integration): Response
    {
        $integrationFields = $this->fetchAllIntegrationFieldsAction->handle($integration);

        return response([
            'status' => 'success',
            'data' => $integrationFields
        ]);
    }

    /**
     * Mapping integration system fields.
     *
     * @param  Request  $request
     * @param  string  $integration
     *
     * @return Response
     */
    public function mapping(Request $request, string $integration): Response
    {
        $mappingFieldsData = FieldsMappingData::fromRequest($request);

        $this->fieldsMappingAction->handle($integration, $mappingFieldsData);

        return response([
            'status' => 'success',
            'data' => []
        ]);
    }

    /**
     * Get fields mapped to integration system.
     *
     * @param  string  $integration
     *
     * @return Response
     */
    public function mapped(string $integration): Response
    {
        $mappedFields = $this->fetchAllMappedFieldsAction->handle($integration);

        return response($mappedFields);
    }
}
