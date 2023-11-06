<?php

declare(strict_types=1);

namespace CompleteSolar\ReleaseRequirement\Console\Commands;

use CompleteSolar\ReleaseRequirement\Stage;
use Illuminate\Database\Console\Seeds\SeedCommand;

class SeedWithRequirementCommand extends SeedCommand
{
    use RunnerTrait;

    public function handle(): int
    {
        $runner = $this->getRequirementRunner();

        if (
            $runner->run(Stage::BEFORE_SEED) === self::FAILURE
            || parent::handle() === self::FAILURE
        ) {
            return self::FAILURE;
        }

        return $runner->run(Stage::AFTER_SEED);
    }
}
