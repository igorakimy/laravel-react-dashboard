<?php

namespace App\Http\Controllers\Api;

use App\Actions\Api\Setting\FetchZohoBooksSettings;
use App\Actions\Api\Setting\UpdateZohoBooksSettings;
use App\Data\Setting\ZohoBooksSettingsData;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class ZohoBooksSettingsController extends ApiController
{
    public function __construct(
        private readonly FetchZohoBooksSettings $fetchZohoBooksSettingsAction,
        private readonly UpdateZohoBooksSettings $updateZohoBooksSettingsAction,
    ) {
    }

    /**
     * Get all Zoho Books settings list.
     *
     * @return Response
     */
    public function index(): Response
    {
        $settings = $this->fetchZohoBooksSettingsAction->handle();

        return response($settings);
    }

    /**
     * Update Zoho Books settings.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function update(Request $request): Response
    {
        $settingsData = ZohoBooksSettingsData::fromRequest($request);

        $updatedSettingsData = $this->updateZohoBooksSettingsAction->handle($settingsData);

        return response($updatedSettingsData);
    }

    public function destroy()
    {

    }
}
