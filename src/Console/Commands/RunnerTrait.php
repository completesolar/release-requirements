<?php

declare(strict_types=1);

namespace CompleteSolar\ReleaseRequirement\Console\Commands;

use CompleteSolar\ReleaseRequirement\UseCases\RunRequirement;

/**
 * @see \CompleteSolar\ReleaseRequirement\Tests\Console\Commands\RunnerTraitTest
 */
trait RunnerTrait
{
    protected function getRequirementRunner(): RunRequirement
    {
        return $this->laravel->make(RunRequirement::class, ['output' => $this->output]);
    }
}
