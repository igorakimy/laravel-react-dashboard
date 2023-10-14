<?php

namespace App\Http\Controllers\Api;

use App\Actions\Api\Product\DeleteProduct;
use App\Actions\Api\Product\FetchPaginatedProducts;
use App\Actions\Api\Product\DeleteProductImage;
use App\Actions\Api\Product\ShowProduct;
use App\Actions\Api\Product\StoreProduct;
use App\Actions\Api\Product\UpdateProduct;
use App\Actions\Api\Product\UploadProductImage;
use App\Data\Product\ProductFilteringData;
use App\Data\Product\ProductPaginationData;
use App\Data\Product\ProductSortingData;
use App\Data\Product\ProductStoreData;
use App\Data\Product\ProductUpdateData;
use App\Data\Product\ProductUploadImagesData;
use App\Http\Controllers\ApiController;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Exceptions\MediaCannotBeDeleted;
use Symfony\Component\HttpFoundation\Response as RespCode;

final class ProductController extends ApiController
{
    public function __construct(
        private readonly FetchPaginatedProducts $fetchPaginatedProductsAction,
        private readonly ShowProduct $showProductAction,
        private readonly StoreProduct $storeProductAction,
        private readonly UpdateProduct $updateProductAction,
        private readonly DeleteProduct $deleteProductAction,
        private readonly UploadProductImage $uploadProductImageAction,
        private readonly DeleteProductImage $deleteProductImageAction,
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

        $products = $this->fetchPaginatedProductsAction->handle(
            $pagination,
            $sorting,
            $filtering
        );

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
     * Upload product image.
     *
     * @param  Request  $request
     * @param  Product  $product
     *
     * @return Response
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function uploadMedia(Request $request, Product $product): Response
    {
        $uploadedData = ProductUploadImagesData::fromRequest($request);

        $media = $this->uploadProductImageAction->handle($product, $uploadedData);

        $uuid = $media?->uuid;

        return response(['uuid' => $uuid], 200);
    }

    /**
     * Delete uploaded image
     *
     * @param  Product  $product
     * @param  string  $uuid
     *
     * @return Response
     * @throws MediaCannotBeDeleted
     */
    public function deleteMedia(Product $product, string $uuid): Response
    {
        $this->deleteProductImageAction->handle($product, $uuid);

        return response('', 204);
    }
}
