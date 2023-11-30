<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Media;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class MediaController extends ApiController
{
    /**
     * Show media by id.
     *
     * @param  Media  $media
     *
     * @return Response
     */
    public function show(Media $media): Response
    {
        return response($media);
    }

    /**
     * Update media.
     *
     * @param  Media  $media
     * @param  Request  $request
     *
     * @return Response
     */
    public function update(Media $media, Request $request): Response
    {
        if ($media->model_type === Product::class)
        {
            $media->setCustomProperty('alt', $request->input('alt'));
            $media->setCustomProperty('integrations', $request->input('integrations', []));
            $media->setCustomProperty('tooltip', $request->input('tooltip'));
            $media->setCustomProperty('primary', $request->input('primary'));
        }

        $media->save();

        return response($media);
    }

    /**
     * Update multiple medias.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function bulkUpdate(Request $request): Response
    {
        $data = $request->all();

        $mediaIds = $data['ids'];

        $medias = Media::query()->whereIn('id', $mediaIds)->get();

        foreach ($medias as $media) {
            $media->setCustomProperty('integrations', $data['integrations']);
            $media->save();
        }

        return response([
            'status' => 'success',
            'message' => 'Medias successfully updated!'
        ]);
    }

    /**
     * Bulk delete
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function bulkDelete(Request $request): Response
    {
        $ids = $request->query('ids');

        Media::query()->whereIn('id', explode(',', $ids))->delete();

        return response([
            'status' => 'success',
            'message' => 'Medias successfully deleted!'
        ]);
    }
}
