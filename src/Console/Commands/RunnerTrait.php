<?php

declare(strict_types=1);

namespace CompleteSolar\ReleaseRequirement\Console\Commands;

use CompleteSolar\ReleaseRequirement\UseCases\RunRequirement;

trait RunnerTrait
{
    private function getRequirementRunner(): RunRequirement
    {
        return app()->make(RunRequirement::class, ['output' => $this->output]);
    }
}
