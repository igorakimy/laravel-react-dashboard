<?php

namespace App\Providers;

use App\Services\ZohoBooks\ZohoBooksService;
use App\Settings\ZohoBooksSettings;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Throwable;

class ZohoBooksServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ZohoBooksService::class, function ($app) {
            return new ZohoBooksService(
                clientID: config('services.zoho_books.client_id'),
                clientSecret: config('services.zoho_books.client_secret'),
                redirectUri: config('services.zoho_books.redirect_uri'),
                organizationID: config('services.zoho_books.organization_id'),
                domain: config('services.zoho_books.domain'),
                scopes: config('services.zoho_books.scopes'),
                useCache: config('services.zoho_books.use_cache'),
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (Schema::hasTable('settings')
            && DB::table('settings')->where('group', 'zoho_books')->count()) {

            try {
                /** @var ZohoBooksSettings $settings */
                $settings = app(ZohoBooksSettings::class)->toArray();

                foreach ($settings as $key => $value) {
                    Config::set("services.zoho_books.$key", $value);
                }
            } catch (Throwable) {
                return;
            }
        }
    }
}
