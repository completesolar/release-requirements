<?php

declare(strict_types=1);

namespace CompleteSolar\ReleaseRequirement\Console\Commands;

use CompleteSolar\ReleaseRequirement\UseCases\CreateRequirement;
use Illuminate\Support\Str;

/**
 * @see \CompleteSolar\ReleaseRequirement\Tests\Console\Commands\CreateRequirementCommandTest
 */
class CreateRequirementCommand extends AbstractRequirementCommand
{
    protected $signature = 'make:requirement
                                {stage : For which stage(before-migrate, before-seed, after_seed)}
                                {name : short name of requirement}';

    protected $description = "Create release requirement for one of the stages: before_migrate, before_seed, after_seed";

    public function handle(CreateRequirement $useCase): int
    {
        $stage = $this->argument('stage');

        if ($this->isInvalidStage($stage)) {
            $this->showInvalidStageError();

            return self::FAILURE;
        }

        $filePath = $useCase->run(
            $stage,
            Str::snake($this->argument('name'))
        );

        $this->info("Requirement($stage) $filePath is created successfully.");

        return self::SUCCESS;
    }
}
