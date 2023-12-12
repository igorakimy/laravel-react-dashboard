<?php

namespace App\Actions\Api\Import;

use App\Actions\Api\ApiAction;
use App\Data\Import\ImportData;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

final class FetchExcelHeaders extends ApiAction
{
    /**
     * @throws FileNotFoundException
     */
    public function handle(ImportData $data): array
    {
        $filePath = $data->filepath;

        $headers = [];

        // check if temp file exist.
        if (Storage::disk('temp')->exists($filePath)) {
            // get path to import file
            $file = Storage::disk('temp')->path($filePath);

            // read first line, which contain headers.
            $firstLine = File::lines($file)->first();

            // get headers from first line.
            if ($firstLine) {
                $headers = array_map(function ($el) {
                    return str_replace('\u{FEFF}', '', $el);
                }, explode(',', $firstLine));
            }
        }

        return $headers;
    }
}
