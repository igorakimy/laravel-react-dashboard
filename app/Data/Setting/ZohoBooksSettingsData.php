<?php

namespace App\Data\Setting;

use App\Settings\ZohoBooksSettings;
use Illuminate\Http\Request;
use Spatie\LaravelData\Data;

final class ZohoBooksSettingsData extends Data
{
    public function __construct(
        public string|null $domain,
        public string|null $client_id,
        public string|null $client_secret,
        public string|null $redirect_uri,
        public string|null $organization_id,
        public array $scopes,
    ) {
    }

    public static function fromModel(ZohoBooksSettings $settings): self
    {
        return new self(
            domain: $settings->domain,
            client_id: $settings->client_id,
            client_secret: $settings->client_secret,
            redirect_uri: $settings->redirect_uri,
            organization_id: $settings->organization_id,
            scopes: $settings->scopes,
        );
    }

    public static function fromRequest(Request $request): self
    {
        $request->validate(self::rules());

        return new self(
            domain: $request->input('domain'),
            client_id: $request->input('client_id'),
            client_secret: $request->input('client_secret'),
            redirect_uri: $request->input('redirect_uri'),
            organization_id: $request->input('organization_id'),
            scopes: array_map(
                'trim',
                explode(',', $request->input('scopes'))
            ),
        );
    }

    public static function rules(): array
    {
        return [
            'scopes' => ['required', 'string'],
        ];
    }
}
