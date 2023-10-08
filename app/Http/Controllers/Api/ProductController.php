<?php

namespace App\Http\Controllers\Api;

use App\Actions\Api\Product\DeleteProduct;
use App\Actions\Api\Product\FetchPaginatedProducts;
use App\Actions\Api\Product\ShowProduct;
use App\Actions\Api\Product\StoreProduct;
use App\Actions\Api\Product\UpdateProduct;
use App\Data\Product\ProductFilteringData;
use App\Data\Product\ProductPaginationData;
use App\Data\Product\ProductSortingData;
use App\Data\Product\ProductStoreData;
use App\Data\Product\ProductUpdateData;
use App\Http\Controllers\ApiController;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as RespCode;

final class ProductController extends ApiController
{
    public function __construct(
        private readonly FetchPaginatedProducts $fetchPaginatedProductsAction,
        private readonly ShowProduct $showProductAction,
        private readonly StoreProduct $storeProductAction,
        private readonly UpdateProduct $updateProductAction,
        private readonly DeleteProduct $deleteProductAction,
    ) {
    }

    /**
     * Show products list.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function index(Request $request)
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
    public function store(Request $request)
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
    public function show(Product $product)
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
    public function update(Request $request, Product $product)
    {
        $updatingData = ProductUpdateData::fromRequest($request);

        $product = $this->updateProductAction->handle($product, $updatingData);

        return response($product);
    }

    /**
     * Delete the product.
     *
     * @param  Product  $product
     *
     * @return Response
     */
    public function destroy(Product $product)
    {
        $product = $this->deleteProductAction->handle($product);

        return response($product, RespCode::HTTP_NO_CONTENT);
    }
}
