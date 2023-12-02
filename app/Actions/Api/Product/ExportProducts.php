<?php

namespace App\Actions\Api\Product;

use App\Actions\Api\ApiAction;
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
    public function handle(ProductExportData $exportData): ProductExportDoneData
    {
        $exportFormat = $exportData->export_as;
        $fileName     = Carbon::now()->format('Y_m_d-h_i_s').'-products.'.$exportFormat;
        $filePath     = md5(now().'-'.Str::random(20)).'/'.$fileName;

        Storage::disk('temp')->put($filePath, '');

        $this->writeProductsToExcelFile($filePath);

        $file = Storage::disk('temp')->path($filePath);

        $headers = [
            'Content-Type'        => ContentType::fromExtension($exportFormat)->value,
            'Content-Disposition' => 'attachment; filename='.$fileName,
        ];

        return ProductExportDoneData::from([
            'file'    => $file,
            'headers' => $headers,
        ]);
    }

    private function writeProductsToExcelFile(string $path): void
    {
        $columns = [
            'name',
            'sku',
            'quantity',
            'cost_price',
            'selling_price',
            'margin',
            'width',
            'height',
            'barcode',
            'location',
            'description',
        ];

        $products = Product::query()->get($columns);

        // prepare excel writer.
        $writer = SimpleExcelWriter::streamDownload(
            downloadName: $path,
            writerCallback: function (WriterInterface $writer) use ($products, $columns) {
                /** @var Options|\OpenSpout\Writer\CSV\Options $options */
                $options = $writer->getOptions();
                if ($writer instanceof Writer) {
                    $columnsLengths = array_values($this->getProductColumnsLengths($products, $columns)->toArray());
                    for ($i = 0; $i < count($columnsLengths); $i++) {
                        $options->setColumnWidth($columnsLengths[$i], $i + 1);
                    }
                }
            }
        );

        // configure header styles.
        $headerStyle = (new Style())->setFontBold();

        // add header.
        $writer->setHeaderStyle($headerStyle)
               ->addHeader(collect($columns)->map(fn($c) => ucfirst($c))->toArray());

        // write products to excel.
        for ($i = 0; $i < $products->count(); $i++) {
            $writer->addRow($products[$i]->toArray());

            if ($i % 500 === 0) {
                flush();
            }
        }

        // stop writing.
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
        $columns = collect(Schema::getColumnListing('products'))->filter(fn($c) => in_array($c, $columnsNames));

        $collection = collect();

        foreach ($columns as $column) {
            $lengths = $products->map(function ($product) use ($column) {
                return strlen($product->$column);
            });

            $maxLength = $lengths->max();

            $columnLength = strlen($column);

            if ($maxLength < $columnLength) {
                $maxLength = $columnLength;
            }

            $collection->put($column, $maxLength);
        }

        return $collection;
    }
}
