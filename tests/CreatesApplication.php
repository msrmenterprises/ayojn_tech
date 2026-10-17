<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        if ($app->environment('testing')) {
            $schema = $app['db']->connection()->getSchemaBuilder();
            $settingsExist = $schema->hasTable('mfa_demo_settings');
            $usersExist = $schema->hasTable('mfa_demo_users');

            if ($settingsExist !== $usersExist) {
                throw new \RuntimeException('The isolated test database has an incomplete MFA demo schema.');
            }

            if (! $settingsExist) {
                require_once base_path('database/migrations/2026_10_17_130000_create_mfa_demo_delivery_tables.php');
                (new \CreateMfaDemoDeliveryTables())->up();
            }
        }

        return $app;
    }
}
