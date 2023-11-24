<?php

namespace App\Services\ZohoBooks\Resources;

use App\Data\Integration\ZohoBooks\Item\ItemEditPageResponseData;
use App\Data\Integration\ZohoBooks\Item\ItemResponseData;
use App\Data\Integration\ZohoBooks\Item\ItemsResponseData;
use App\Helpers\AttachmentHelper;
use App\Services\ZohoBooks\ZohoBooksService;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class ItemResource
{
    public function __construct(
        private readonly ZohoBooksService $service,
    ) {
    }

    /**
     * Get the list of all active items.
     *
     * @return ItemsResponseData
     */
    public function getList(): ItemsResponseData
    {
        $url = '/items';

        $request = $this->service->buildRequestWithToken();

        $response = $this->service->get($request, $url, [
            'organization_id' => $this->service->organizationID,
        ]);

        return ItemsResponseData::fromResponse($response);
    }

    /**
     * Search the items by criteria.
     *
     * @param  array  $criteria
     *
     * @return ItemsResponseData
     */
    public function searchByCriteria(array $criteria): ItemsResponseData
    {
        $url = '/items';

        $request = $this->service->buildRequestWithToken();

        $response = $this->service->get($request, $url, array_merge([
            'organization_id' => $this->service->organizationID,
        ], $criteria));

        return ItemsResponseData::fromResponse($response);
    }

    /**
     * Get the item by unique ID.
     *
     * @param  string  $itemID
     *
     * @return ItemResponseData
     */
    public function getByID(string $itemID): ItemResponseData
    {
        $url = '/items/'.$itemID;

        $request = $this->service->buildRequestWithToken();

        $response = $this->service->get($request, $url, [
            'organization_id' => $this->service->organizationID,
        ]);

        return ItemResponseData::fromResponse($response);
    }

    /**
     * Get the item data for edit page.
     *
     * @param  string  $itemID
     *
     * @return ItemEditPageResponseData
     */
    public function getForEditPage(string $itemID): ItemEditPageResponseData
    {
        $url = '/items/editpage';

        $request = $this->service->buildRequestWithToken();

        $response = $this->service->get($request, $url, [
            'organization_id' => $this->service->organizationID,
            'item_id' => $itemID,
        ]);

        return ItemEditPageResponseData::fromResponse($response);
    }

    /**
     * Create a new item.
     *
     * @param  array  $payload
     *
     * @return ItemResponseData
     */
    public function create(array $payload): ItemResponseData
    {
        $url = '/items';

        $request = $this->service->buildRequestWithToken();

        $response = $this->service->post($request, $url, $payload);

        return ItemResponseData::fromResponse($response);
    }

    /**
     * Update the item.
     *
     * @param  string  $itemID
     * @param  array  $payload
     * @param  bool  $payloadInQueryParams
     *
     * @return ItemResponseData
     */
    public function update(string $itemID, array $payload, bool $payloadInQueryParams = false): ItemResponseData
    {
        $url = '/items/'.$itemID;

        $request = $this->service->buildRequestWithToken();

        $queryParams = [];

        if ($payloadInQueryParams) {
            $queryParams = $payload;
            $payload = [];
        }

        $queryParams = array_merge($queryParams, [
            'organization_id' => $this->service->organizationID,
        ]);

        $response = $this->service->put($request, $url, $payload, $queryParams);

        return ItemResponseData::fromResponse($response);
    }

    /**
     * Update item main image.
     *
     * @param  string  $itemID
     * @param  UploadedFile  $image
     *
     * @return ItemResponseData
     */
    public function updateImage(string $itemID, UploadedFile $image): ItemResponseData
    {
        $url = '/items/'.$itemID.'/image';

        $request = $this->service->buildRequestWithToken();

        $response = $this->service->post(
            $request,
            $url,
            null,
            false,
            AttachmentHelper::create(
                'image',
                file_get_contents($image),
                $image->getClientOriginalName().'.'
                .$image->getClientOriginalExtension()
            )
        );

        return ItemResponseData::fromResponse($response);
    }
}
