<?php

declare(strict_types=1);

namespace CompleteSolar\ReleaseRequirement\Console\Commands;

class RunRequirementsCommand extends AbstractRequirementCommand
{
    use RunnerTrait;

    protected $signature = 'requirement:run
                                {stage : For which stage(before-migrate, before-seed, after_seed)}
                                {--name= : Name of requirement}';

    protected $description = "Run requirements for selected stage: (before-migrate, before-seed, after_seed).";

    public function handle(): int
    {
        $stage = $this->argument('stage');

        if ($this->isInvalidStage($stage)) {
            $this->showInvalidStageError();

            return self::FAILURE;
        }

        return $this->getRequirementRunner()->run($stage, $this->option('name'));
    }
}
