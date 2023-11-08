<?php

declare(strict_types=1);

namespace CompleteSolar\ReleaseRequirement;

use CompleteSolar\ReleaseRequirement\Console\Commands\CreateRequirementCommand;
use CompleteSolar\ReleaseRequirement\Console\Commands\MigrateWithRequirementCommand;
use CompleteSolar\ReleaseRequirement\Console\Commands\RunRequirementsCommand;
use CompleteSolar\ReleaseRequirement\Console\Commands\SeedWithRequirementCommand;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Console\Migrations\MigrateCommand;
use Illuminate\Database\Console\Seeds\SeedCommand;
use Illuminate\Support\ServiceProvider;

/**
 * @codeCoverageIgnore
 */
class ReleaseRequirementProvider extends ServiceProvider
{
    public function boot(): void
    {
        $timestamp = date('Y_m_d_His');
        $this->publishes(
            [
                __DIR__ . '/migrations/create_requirements_table.php' => $this->app->databasePath(
                    "migrations/{$timestamp}_create_requirements_table.php"
                ),
                __DIR__ . '/config/requirement.php' => config_path('requirement.php'),
            ],
            'release-requirements'
        );

        $this->commands([
            CreateRequirementCommand::class,
            RunRequirementsCommand::class,
        ]);
    }

    public function register(): void
    {
        if (!config('requirement.enabled')) {
            return;
        }

        $this->app->extend(MigrateCommand::class, function (MigrateCommand $command, $app) {
            return new MigrateWithRequirementCommand($app['migrator'], $app[Dispatcher::class]);
        });

        $this->app->extend(SeedCommand::class, function (SeedCommand $command, $app) {
            return new SeedWithRequirementCommand($app['db']);
        });
    }
}
