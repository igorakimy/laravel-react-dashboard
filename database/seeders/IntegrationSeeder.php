<?php

namespace Database\Seeders;

use App\Models\Integration;
use Illuminate\Database\Seeder;
use App\Enums\Integration as IntegrationSystem;

class IntegrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $integrations = IntegrationSystem::cases();

        foreach ($integrations as $integration) {
            $existingIntegration = Integration::query()->where(
                'slug',
                $integration->value
            )->first();

            $payload = [
                'name' => $integration->label(),
                'slug' => $integration->value,
            ];

            if ( ! $existingIntegration) {
                Integration::query()->create($payload);
            } else {
                $existingIntegration->update($payload);
            }
        }
    }
}
