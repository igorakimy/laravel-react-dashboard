<?php

namespace App\Data\Integration\ZohoCrm\Record\Product;

use App\Models\Integration;
use App\Enums\Integration as IntegrationSystem;
use Illuminate\Http\Client\Response;
use Spatie\LaravelData\Data;

final class ProductResponseData extends Data
{
    public function __construct(
        public bool $success,

        public ProductData|null $product,

        public string|null $message,
    ) {
    }

    public static function fromResponse(Response $response): self
    {
        $product = $response->json('data');

        return new self(
            success: $response->successful(),
            product: $product
                ? ProductData::from($product[0])->additional(self::getCustomAttributes($product[0]))
                : null,
            message: $response->json('message'),
        );
    }

    private static function getCustomAttributes(array $data): array
    {
        $attributes = [
            'Custom_img' => $data['Custom_img'],
        ];

        $integration = Integration::query()->where(
            'slug',
            IntegrationSystem::ZOHO_CRM
        )->first();

        $integrationFields = $integration->fields;

        foreach ($integrationFields as $integrationField) {
            if ($integrationField->is_active && in_array($integrationField->api_name, array_keys($data))) {
                $attributes[$integrationField->api_name] = $data[$integrationField->api_name] ?? null;
            }
        }

        return $attributes;
    }
}
