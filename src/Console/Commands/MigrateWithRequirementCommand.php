<?php

declare(strict_types=1);

namespace CompleteSolar\ReleaseRequirement\Console\Commands;

use CompleteSolar\ReleaseRequirement\Stage;
use Illuminate\Database\Console\Migrations\MigrateCommand;

class MigrateWithRequirementCommand extends MigrateCommand
{
    use RunnerTrait;

    public function handle(): int
    {
        if ($this->getRequirementRunner()->run(Stage::BEFORE_MIGRATE) === self::FAILURE) {
            return self::FAILURE;
        }

        return parent::handle();
    }
}
