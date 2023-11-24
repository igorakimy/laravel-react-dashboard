<?php

namespace App\Services\ZohoBooks\Resources;

use App\Data\Integration\ZohoBooks\Document\DocumentResponseData;
use App\Data\Integration\ZohoBooks\Document\DocumentsResponseData;
use App\Services\ZohoBooks\ZohoBooksService;
use Illuminate\Http\Client\Response;

final class DocumentResource
{
    public function __construct(
        private readonly ZohoBooksService $service
    ) {
    }

    /**
     * Get list of documents(attachments).
     *
     * @return DocumentsResponseData
     */
    public function getList(): DocumentsResponseData
    {
        $url = '/documents';

        $request = $this->service->buildRequestWithToken();

        $response = $this->service->get($request, $url, [
            'organization_id' => $this->service->organizationID
        ]);

        return DocumentsResponseData::fromResponse($response);
    }

    /**
     * Get document by ID.
     *
     * @param  string  $documentID
     * @param  string  $format
     *
     * @return DocumentResponseData
     */
    public function getByID(string $documentID, string $format = 'json'): DocumentResponseData
    {
        $url = '/documents/'.$documentID;

        $request = $this->service->buildRequestWithToken();

        $response = $this->service->get($request,$url, [
            'format' => $format,
            'organization_id' => $this->service->organizationID,
        ]);

        return DocumentResponseData::fromResponse($response);
    }

    /**
     * Get document binary by ID.
     *
     * @param  string  $documentID
     * @param  string  $inline
     * @param  string  $image_size
     *
     * @return string
     */
    public function getBinaryByID(string $documentID, string $inline = 'true', string $image_size = 'large'): string
    {
        $url = '/documents/'.$documentID;

        $request = $this->service->buildRequestWithToken();

        $response = $this->service->get($request, $url, [
            'organization_id' => $this->service->organizationID,
            'inline' => $inline
        ]);

        return $response->body();
    }

    /**
     * Delete the document.
     *
     * @param  string  $documentID
     *
     * @return DocumentResponseData
     */
    public function delete(string $documentID): DocumentResponseData
    {
        $url = '/documents/'.$documentID;

        $request = $this->service->buildRequestWithToken();

        $response = $this->service->delete($request, $url, [
            'organization_id' => $this->service->organizationID,
        ]);

        return DocumentResponseData::fromResponse($response);
    }

    /**
     * Get image from binary response.
     *
     * @param  Response  $response
     *
     * @return string
     */
    private function getImageFromBinaryResponse(Response $response): string
    {
        $contentType = $response->handlerStats()['content_type'];

        $base64 = base64_encode($response->body());

        return 'data:'.explode(';',$contentType)[0].';base64,'.$base64;
    }

}
