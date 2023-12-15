<?php

namespace App\Providers;

use App\Enums\Integration;
use App\Services\ZohoInventory\ZohoInventoryService;
use App\Settings\ZohoInventorySettings;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Throwable;

class ZohoInventoryServiceProvider extends ServiceProvider
{
    public Integration $service = Integration::ZOHO_INVENTORY;

    /**
     * Register services.
     */
    public function register(): void
    {
        $serviceName = $this->service->value;

        $this->app->bind(ZohoInventoryService::class, function ($app) use ($serviceName) {
            return new ZohoInventoryService(
                clientID: config("services.$serviceName.client_id"),
                clientSecret: config("services.$serviceName.client_secret"),
                redirectUri: config("services.$serviceName.redirect_uri"),
                organizationID: config("services.$serviceName.organization_id"),
                domain: config("services.$serviceName.domain"),
                scopes: config("services.$serviceName.scopes"),
                useCache: config("services.$serviceName.use_cache"),
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (Schema::hasTable('settings')
            && DB::table('settings')->where('group', $this->service->value)->count()) {

            try {
                /** @var ZohoInventorySettings $settings */
                $settings = app(ZohoInventorySettings::class)->toArray();

                foreach ($settings as $key => $value) {
                    Config::set("services.".$this->service->value.".$key", $value);
                }
            } catch (Throwable) {
                return;
            }
        }
    }
}
