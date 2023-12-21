<?php

namespace App\Http\Controllers\Api;

use App\Actions\Api\Setting\FetchZohoCrmSettings;
use App\Actions\Api\Setting\UpdateZohoCrmSettings;
use App\Data\Setting\ZohoCrmSettingsData;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class ZohoCrmSettingsController extends ApiController
{
    public function __construct(
        private readonly FetchZohoCrmSettings $fetchZohoCrmSettingsAction,
        private readonly UpdateZohoCrmSettings $updateZohoCrmSettingsAction,
    ) {
    }

    /**
     * Get all Zoho CRM settings list.
     *
     * @return Response
     */
    public function index(): Response
    {
        $settings = $this->fetchZohoCrmSettingsAction->handle();

        return response($settings);
    }

    /**
     * Update Zoho CRM settings.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function update(Request $request): Response
    {
        $settingsData = ZohoCrmSettingsData::fromRequest($request);

        $updatedSettingsData = $this->updateZohoCrmSettingsAction->handle($settingsData);

        return response($updatedSettingsData);
    }
}
