<?php

namespace App\Http\Controllers\Api;

use App\Actions\Api\Import\FetchExcelHeaders;
use App\Data\Import\ImportData;
use App\Data\Import\ImportHeadersData;
use App\Http\Controllers\ApiController;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class ImportController extends ApiController
{
    public function __construct(
        public readonly FetchExcelHeaders $fetchExcelHeadersAction,
    ) {
    }

    /**
     * Get list of headers from Excel/CSV file.
     *
     * @param  Request  $request
     *
     * @return Response
     * @throws FileNotFoundException
     */
    public function headers(Request $request): Response
    {
        $data = ImportData::fromRequest($request);

        $excelHeaders = $this->fetchExcelHeadersAction->handle($data);

        return response(ImportHeadersData::from($excelHeaders));
    }
}
