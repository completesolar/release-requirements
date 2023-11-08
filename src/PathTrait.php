<?php

declare(strict_types=1);

namespace CompleteSolar\ReleaseRequirement;

/**
 * @see \CompleteSolar\ReleaseRequirement\Tests\PathTraitTest
 */
trait PathTrait
{
    private function getRequirementName(string $path): string
    {
        return str_replace('.php', '', basename($path));
    }
}
