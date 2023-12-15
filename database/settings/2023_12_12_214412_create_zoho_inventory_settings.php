<?php

use App\Enums\Integration;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $serviceName = Integration::ZOHO_INVENTORY->value;

        $this->migrator->add("$serviceName.domain", config("services.$serviceName.domain"));
        $this->migrator->add("$serviceName.client_id", config("services.$serviceName.client_id"));
        $this->migrator->add("$serviceName.client_secret", config("services.$serviceName.client_secret"));
        $this->migrator->add("$serviceName.redirect_uri", config("services.$serviceName.redirect_uri"));
        $this->migrator->add("$serviceName.organization_id", config("services.$serviceName.organization_id"));
        $this->migrator->add("$serviceName.scopes", config("services.$serviceName.scopes"));
    }
};
