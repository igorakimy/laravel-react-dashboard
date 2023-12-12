<?php

namespace App\Http\Controllers\Api;

use App\Actions\Api\Product\DeleteProduct;
use App\Actions\Api\Product\ExportProducts;
use App\Actions\Api\Product\FetchPaginatedProducts;
use App\Actions\Api\Product\DeleteProductImage;
use App\Actions\Api\Product\FetchProductsForSelect;
use App\Actions\Api\Product\ImportProducts;
use App\Actions\Api\Product\ShowProduct;
use App\Actions\Api\Product\StoreProduct;
use App\Actions\Api\Product\UpdateProduct;
use App\Actions\Api\Product\UploadProductImage;
use App\Data\Product\ProductExportData;
use App\Data\Product\ProductFilteringData;
use App\Data\Product\ProductImportData;
use App\Data\Product\ProductPaginationData;
use App\Data\Product\ProductSortingData;
use App\Data\Product\ProductStoreData;
use App\Data\Product\ProductUpdateData;
use App\Data\Product\ProductUploadImagesData;
use App\Http\Controllers\ApiController;
use App\Models\Media;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Exceptions\MediaCannotBeDeleted;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response as RespCode;
use Throwable;

final class ProductController extends ApiController
{
    public function __construct(
        private readonly FetchPaginatedProducts $fetchPaginatedProductsAction,
        private readonly FetchProductsForSelect $fetchProductsForSelectAction,
        private readonly ShowProduct $showProductAction,
        private readonly StoreProduct $storeProductAction,
        private readonly UpdateProduct $updateProductAction,
        private readonly DeleteProduct $deleteProductAction,
        private readonly UploadProductImage $uploadProductImageAction,
        private readonly DeleteProductImage $deleteProductImageAction,
        private readonly ExportProducts $exportProductsAction,
        private readonly ImportProducts $importProductsAction,
    ) {
    }

    /**
     * Show products list.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function index(Request $request): Response
    {
        $pagination = ProductPaginationData::fromRequest($request);
        $sorting = ProductSortingData::fromRequest($request);
        $filtering = ProductFilteringData::fromRequest($request);

        if ($request->input('kind') === 'select') {
            $products = $this->fetchProductsForSelectAction->handle(
                sorting: $sorting,
                filtering: $filtering,
            );
        } else {
            $products = $this->fetchPaginatedProductsAction->handle(
                pagination: $pagination,
                sorting: $sorting,
                filtering: $filtering,
            );
        }

        return response($products);
    }

    /**
     * Create new product.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function store(Request $request): Response
    {
        $data = ProductStoreData::fromRequest($request);

        $product = $this->storeProductAction->handle($data);

        return response($product, RespCode::HTTP_CREATED);
    }

    /**
     * Show the product.
     *
     * @param  Product  $product
     *
     * @return Response
     */
    public function show(Product $product): Response
    {
        $product = $this->showProductAction->handle($product);

        return response($product);
    }

    /**
     * Update the product.
     *
     * @param  Request  $request
     * @param  Product  $product
     *
     * @return Response
     * @throws Exception
     */
    public function update(Request $request, Product $product): Response
    {
        $updatingData = ProductUpdateData::fromRequest($request);

        $product = $this->updateProductAction->handle(
            $product,
            $updatingData
        );

        return response($product);
    }

    /**
     * Delete the product.
     *
     * @param  Product  $product
     *
     * @return Response
     */
    public function destroy(Product $product): Response
    {
        $product = $this->deleteProductAction->handle($product);

        return response($product, RespCode::HTTP_NO_CONTENT);
    }

    /**
     * Delete several products.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function bulkDestroy(Request $request): Response
    {
        foreach (explode(',', $request->query('ids', '')) as $id) {
            $this->deleteProductAction->handle($id);
        }

        return response([
            'status' => 'success',
            'message' => 'Products successfully deleted',
        ]);
    }

    /**
     * Upload product image.
     *
     * @param  Request  $request
     * @param  Product  $product
     * @param  string  $collection
     *
     * @return Response
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function uploadMedia(Request $request, Product $product, string $collection): Response
    {
        $uploadedData = ProductUploadImagesData::fromRequest($request, $collection);

        /** @var Media $media */
        $media = $this->uploadProductImageAction->handle(
            $product,
            $uploadedData,
            $collection,
        );

        $mediaID = $media?->id;

        return response(['id' => $mediaID], RespCode::HTTP_OK);
    }

    /**
     * Delete uploaded image
     *
     * @param  Product  $product
     * @param  string  $id
     *
     * @return Response
     * @throws MediaCannotBeDeleted
     */
    public function deleteMedia(Product $product, string $id): Response
    {
        $this->deleteProductImageAction->handle($product, $id);

        return response('', RespCode::HTTP_NO_CONTENT);
    }

    /**
     * Export products to csv/xlsx formats.
     *
     * @param  Request  $request
     *
     * @return BinaryFileResponse
     */
    public function export(Request $request): BinaryFileResponse
    {
        $data = ProductExportData::fromRequest($request);

        $res = $this->exportProductsAction->handle($data);

        return response()->download(
            file: $res->file,
            headers: $res->headers
        );
    }

    /**
     * Import products from csv format.
     *
     * @param  Request  $request
     *
     * @return Response
     * @throws Throwable
     */
    public function import(Request $request): Response
    {
        $importData = ProductImportData::fromRequest($request);

        $this->importProductsAction->handle($importData);

        return response(['status' => 'success']);
    }
}
