<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Meta;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class MetaController extends ApiController
{
    /**
     * Get list of meta fields.
     *
     * @return Response
     */
    public function index(): Response
    {
        $metas = Meta::query()->get();

        return response($metas);
    }

    /**
     * Show one meta field.
     *
     * @param  Meta  $meta
     *
     * @return Response
     */
    public function show(Meta $meta): Response
    {
        return response($meta);
    }

    /**
     * Store a new meta field.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function store(Request $request): Response
    {
        $data = $request->all();

        $meta = Meta::query()->create($data);

        return response($meta);
    }

    /**
     * Update meta field.
     *
     * @param  Request  $request
     * @param  Meta  $meta
     *
     * @return Response
     */
    public function update(Request $request, Meta $meta): Response
    {
        $data = $request->all();

        $meta->update($data);

        return response($meta);
    }

    /**
     * Delete meta field.
     *
     * @param  Meta  $meta
     *
     * @return Response
     */
    public function destroy(Meta $meta): Response
    {
        $meta->delete();

        return response($meta);
    }
}
