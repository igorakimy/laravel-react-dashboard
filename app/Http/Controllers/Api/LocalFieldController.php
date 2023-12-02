<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\LocalField;
use Illuminate\Http\Response;

final class LocalFieldController extends ApiController
{
    /**
     * Get all local fields.
     *
     * @return Response
     */
    public function index(): Response
    {
        $localFields = LocalField::query()->orderBy('order')->get();

        return response($localFields);
    }
}
