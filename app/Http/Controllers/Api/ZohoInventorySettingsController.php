<?php

namespace App\Http\Controllers\Api;

use App\Actions\Api\Setting\FetchZohoInventorySettings;
use App\Actions\Api\Setting\UpdateZohoInventorySettings;
use App\Data\Setting\ZohoInventorySettingsData;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class ZohoInventorySettingsController extends ApiController
{
    public function __construct(
        private readonly FetchZohoInventorySettings $fetchZohoInventorySettingsAction,
        private readonly UpdateZohoInventorySettings $updateZohoInventorySettingsAction,
    ) {
    }

    /**
     * Get all Zoho Inventory settings list.
     *
     * @return Response
     */
    public function index(): Response
    {
        $settings = $this->fetchZohoInventorySettingsAction->handle();

        return response($settings);
    }

    /**
     * Update Zoho Inventory settings.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function update(Request $request): Response
    {
        $settingsData = ZohoInventorySettingsData::fromRequest($request);

        $updatedSettingsData = $this->updateZohoInventorySettingsAction->handle($settingsData);

        return response($updatedSettingsData);
    }
}
