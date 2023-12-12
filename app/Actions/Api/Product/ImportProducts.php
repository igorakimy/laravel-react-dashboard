<?php

namespace App\Actions\Api\Product;

use App\Actions\Api\ApiAction;
use App\Data\Product\ProductImportData;
use App\Jobs\ImportProductsCsv;
use App\Models\LocalField;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Str;
use Spatie\SimpleExcel\SimpleExcelReader;
use Throwable;

final class ImportProducts extends ApiAction
{
    /**
     * @throws Throwable
     */
    public function handle(ProductImportData $importData): string
    {
        // get path to import file
        $filePath = $importData->filepath;

        // get Excel file from temporary storage
        $file = Storage::disk('temp')->path($filePath);

        // create reader instance
        $reader = SimpleExcelReader::create($file);

        // get list original headers of CSV file
        $originalHeaders = $reader->getHeaders();

        // form new list of headers
        $newHeaders = $this->getProductColumns(
            $importData->mapped_columns,
            $originalHeaders
        );

        // list of import jobs
        $imports = [];

        // add chunks with rows to import jobs
        $reader
            ->useHeaders($newHeaders)
            ->getRows()
            ->chunk(60)
            ->each(function (LazyCollection $row) use (&$imports) {
                $imports[] = new ImportProductsCsv($row->collect());
            });

        // run batch of jobs to start import
        $batch = Bus::batch($imports)
            ->then(function(Batch $batch) {

            })->catch(function (Batch $batch, Throwable $e) {

            })->finally(function (Batch $batch) {

            })->dispatch();

        return $batch->id;
    }

    /**
     * Get list of product columns, which mapped with existing headers in csv file.
     *
     * @param  array  $mappedColumns
     * @param  array  $existingHeaders
     *
     * @return array
     */
    private function getProductColumns(array $mappedColumns, array $existingHeaders): array
    {
        $orderedColumns = [];

        $mappedColumnsCollection = collect($mappedColumns);

        foreach ($existingHeaders as $existingHeader) {

            $localFieldID = $mappedColumnsCollection
                ->where('import_header', $existingHeader)
                ->pluck('local_field')->first();

            $localField = LocalField::query()
                                    ->select(['slug'])
                                    ->where('id', $localFieldID)
                                    ->orWhere('name', $existingHeader)
                                    ->first()?->slug;

            if (! $localField) {
                $localField = Str::slug($existingHeader, '_');
            }

            $orderedColumns[] = $localField;
        }

        return $orderedColumns;
    }
}
