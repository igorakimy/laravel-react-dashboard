<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class TemporaryFileController extends ApiController
{
    public function upload(Request $request): Response
    {
        $files = $request->allFiles();

        if (empty($files)) {
            return response([
                'status' => 'error',
                'message' => 'No files were uploaded'
            ], 422);
        }

        if (count($files) > 1) {
            return response([
                'status' => 'error',
                'message' => 'Only 1 file can be uploaded at a time'
            ], 422);
        }

        $requestKey = array_key_first($files);

        /** @var UploadedFile $file */
        $file = is_array($request->input($requestKey))
            ? $request->file($requestKey)[0]
            : $request->file($requestKey);

        $path = $file->storeAs(
            md5(now()->timestamp.'-'.Str::random(20)),
            $file->getClientOriginalName(),
            'temp'
        );

        $url = Storage::disk('temp')->url($path);

        return response([
            'status' => 'success',
            'url' => $url,
            'path' => $path
        ]);
    }
}
