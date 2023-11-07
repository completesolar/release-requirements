<?php

declare(strict_types=1);


namespace CompleteSolar\ReleaseRequirement;


trait PathTrait
{
    private function getRequirementName(string $path): string
    {
        return str_replace('.php', '', basename($path));
    }
}
