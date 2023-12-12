<?php

namespace App\Actions\Api\Product;

use App\Actions\Api\ApiAction;
use App\Data\Product\ProductData;
use App\Data\Product\ProductExportData;
use App\Data\Product\ProductExportDoneData;
use App\Enums\ContentType;
use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\WriterInterface;
use OpenSpout\Writer\XLSX\Options;
use OpenSpout\Writer\XLSX\Writer;
use Spatie\SimpleExcel\SimpleExcelWriter;

final class ExportProducts extends ApiAction
{
    public function __construct(
        public readonly FetchProducts $fetchProductsAction,
    ) {
    }

    public function handle(ProductExportData $exportData): ProductExportDoneData
    {
        // get Excel file format
        $exportFormat = $exportData->export_as;

        // get file name
        $fileName = Carbon::now()->format('Y_m_d-h_i_s').'-products.'.$exportFormat;

        // get path to file
        $filePath = md5(now().'-'.Str::random(20)).'/'.$fileName;

        // store a new Excel file to temporary storage
        Storage::disk('temp')->put($filePath, '');

        // write products to Excel file
        $this->writeProductsToExcelFile($filePath);

        // get Excel file from temporary storage
        $file = Storage::disk('temp')->path($filePath);

        // get special headers for downloading csv/xlsx files
        $headers = [
            'Content-Type' => ContentType::fromExtension($exportFormat)->value,
            'Content-Disposition' => 'attachment; filename='.$fileName,
        ];

        // return Excel file path and headers for download
        return ProductExportDoneData::from([
            'file'    => $file,
            'headers' => $headers,
        ]);
    }

    /**
     * Write products to Excel file.
     *
     * @param  string  $path
     *
     * @return void
     */
    private function writeProductsToExcelFile(string $path): void
    {
        // get products column for export
        $columns = Product::columnsForExcel();
        $relationColumns = Product::relationColumnsForExcel();
        $allColumns = array_merge($columns, $relationColumns);

        // get products
        $products = Product::query()->with($relationColumns)->get();

        // prepare excel writer
        $writer = SimpleExcelWriter::streamDownload(
            downloadName: $path,
            writerCallback: function (WriterInterface $writer) use ($products, $columns) {
                /** @var Options|\OpenSpout\Writer\CSV\Options $options */
                $options = $writer->getOptions();
                // set writer options
                if ($writer instanceof Writer) {
                    $columnsLengths = array_values($this->getProductColumnsLengths($products, $columns)->toArray());
                    for ($i = 0; $i < count($columnsLengths); $i++) {
                        $options->setColumnWidth($columnsLengths[$i], $i + 1);
                    }
                }
            }
        );

        // configure header styles
        $headerStyle = (new Style())->setFontBold();

        // add header
        $writer->setHeaderStyle($headerStyle)
               ->addHeader(collect($allColumns)->map(fn($c) => ucfirst($c))->toArray());

        // get collection of products with relations
        $productsCollection = ProductData::collection($products)
                                         ->include(...$relationColumns)
                                         ->toCollection();

        // write products to excel
        for ($i = 0; $i < $products->count(); $i++) {
            // write formed row with product
            $writer->addRow($this->formExportRow($productsCollection[$i], $allColumns));

            // flush buffer every 500 records
            if ($i % 500 === 0) {
                flush();
            }
        }

        // stop writing
        $writer->toBrowser();
    }

    /**
     * Get collection of columns and max length value of each column.
     *
     * @param  Collection  $products
     * @param  array  $columnsNames
     *
     * @return Collection
     */
    private function getProductColumnsLengths(Collection $products, array $columnsNames): Collection
    {
        // get collection of products columns,
        // which existing in columnsNames list
        $columns = collect(Schema::getColumnListing('products'))->filter(fn($c) => in_array($c, $columnsNames));

        // form returnable collection of lengths for each column
        $collection = collect();

        foreach ($columns as $column) {
            // get max length of column through all products
            $maxLength = $products->map(function ($product) use ($column) {
                return strlen($product->$column);
            })->max();

            // get default column length for exporting
            $columnLength = strlen($column);

            // set max length to default column length if are less
            if ($maxLength < $columnLength) {
                $maxLength = $columnLength;
            }

            // put this length of column to returnable
            // collection for current column
            $collection->put($column, $maxLength);
        }

        return $collection;
    }

    /**
     * Form product data for export.
     *
     * @param  ProductData  $data
     * @param  array  $columns
     *
     * @return array
     */
    private function formExportRow(ProductData $data, array $columns): array
    {
        $row = [];

        foreach ($columns as $column) {
            if ( ! in_array($column, Product::relationColumnsForExcel())) {
                $row[$column] = $data->$column;
                continue;
            }

            switch ($column) {
                case 'color':
                case 'material':
                case 'vendor':
                case 'type':
                    $row[$column] = $data->$column->name;
                    break;
                case 'categories':
                    $row[$column] = implode(
                        ',',
                        $data->$column->toCollection()->map(fn($c) => $c->name)->toArray()
                    );
            }
        }

        return $row;
    }
}
