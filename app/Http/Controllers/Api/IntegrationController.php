<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Integration;
use Illuminate\Http\Response;

final class IntegrationController extends ApiController
{
    public function index(): Response
    {
        $integrations = Integration::query()->get();

        return response($integrations);
    }
}
