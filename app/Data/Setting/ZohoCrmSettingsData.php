<?php

namespace App\Data\Setting;

use App\Settings\ZohoCrmSettings;
use Illuminate\Http\Request;
use Spatie\LaravelData\Data;

final class ZohoCrmSettingsData extends Data
{
    public function __construct(
        public string|null $domain,
        public string|null $client_id,
        public string|null $client_secret,
        public string|null $redirect_uri,
        public array $scopes,
    ) {
    }

    public static function fromModel(ZohoCrmSettings $settings): self
    {
        return new self(
            domain: $settings->domain,
            client_id: $settings->client_id,
            client_secret: $settings->client_secret,
            redirect_uri: $settings->redirect_uri,
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
            scopes: array_map(
                'trim',
                explode(',', $request->input('scopes'))
            ),
        );
    }

    public static function rules(): array
    {
        return [
            'domain' => ['required', 'string'],
            'client_id' => ['required', 'string'],
            'client_secret' => ['required', 'string'],
            'redirect_uri' => ['required', 'string'],
            'scopes' => ['required', 'string'],
        ];
    }
}
