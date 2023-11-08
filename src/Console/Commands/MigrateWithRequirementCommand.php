<?php

declare(strict_types=1);

namespace CompleteSolar\ReleaseRequirement\Console\Commands;

use CompleteSolar\ReleaseRequirement\Stage;
use Illuminate\Database\Console\Migrations\MigrateCommand;

/**
 * @see \CompleteSolar\ReleaseRequirement\Tests\Console\Commands\MigrateWithRequirementCommandTest
 */
class MigrateWithRequirementCommand extends MigrateCommand
{
    use RunnerTrait;

    public function handle(): int
    {
        $runner = $this->getRequirementRunner();

        if ($runner->run(Stage::BEFORE_MIGRATE) === self::FAILURE) {
            return self::FAILURE;
        }

        if ($this->runMigrations() === self::FAILURE) {
            return self::FAILURE;
        }

        return $runner->run(Stage::AFTER_MIGRATE);
    }

    /** @codeCoverageIgnore */
    protected function runMigrations(): int
    {
        return parent::handle();
    }
}
