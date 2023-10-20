<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('zoho_books.domain', config('services.zoho_books.domain'));
        $this->migrator->add('zoho_books.client_id', config('services.zoho_books.client_id'));
        $this->migrator->add('zoho_books.client_secret', config('services.zoho_books.client_secret'));
        $this->migrator->add('zoho_books.redirect_uri', config('services.zoho_books.redirect_uri'));
        $this->migrator->add('zoho_books.organization_id', config('services.zoho_books.organization_id'));
        $this->migrator->add('zoho_books.scopes', config('services.zoho_books.scopes'));
    }
};
