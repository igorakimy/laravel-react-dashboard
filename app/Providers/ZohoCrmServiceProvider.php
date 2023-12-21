<?php

namespace App\Providers;

use App\Enums\Integration;
use App\Services\ZohoCrm\ZohoCrmService;
use App\Settings\ZohoCrmSettings;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Throwable;

class ZohoCrmServiceProvider extends ServiceProvider
{
    private Integration $service = Integration::ZOHO_CRM;

    /**
     * Register services.
     */
    public function register(): void
    {
        $serviceName = $this->service->value;

        $this->app->bind(ZohoCrmService::class, function ($app) use ($serviceName) {
            return new ZohoCrmService(
                clientID: config("services.$serviceName.client_id"),
                clientSecret: config("services.$serviceName.client_secret"),
                redirectUri: config("services.$serviceName.redirect_uri"),
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
                /** @var ZohoCrmSettings $settings */
                $settings = app(ZohoCrmSettings::class)->toArray();

                foreach ($settings as $key => $value) {
                    Config::set("services.".$this->service->value.".$key", $value);
                }
            } catch (Throwable) {
                return;
            }
        }
    }
}
