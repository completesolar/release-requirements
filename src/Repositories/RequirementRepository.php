<?php

declare(strict_types=1);

namespace CompleteSolar\ReleaseRequirement\Repositories;

use CompleteSolar\ReleaseRequirement\Models\Requirement;
use CompleteSolar\ReleaseRequirement\PathTrait;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;

class RequirementRepository
{
    use PathTrait;

    public function __construct(private readonly Filesystem $filesystem)
    {
    }

    public function getPendingRequirements(string $stage, string $path): array
    {
        $ran = $this->getRanRequirements($stage);

        return $this->getRequirementFiles($path)
            ->reject(function ($file) use ($ran) {
                return in_array($this->getRequirementName($file), $ran);
            })->values()
            ->all();
    }

    /** @codeCoverageIgnore */
    public function addRequirementToRan(string $stage, string $name): void
    {
        Requirement::create([
            'stage' => $stage,
            'name' => $name,
        ]);
    }

    protected function getRanRequirements(string $stage): array
    {
        return Requirement::where('stage', $stage)->pluck('name')->toArray();
    }

    protected function getRequirementFiles(string $path): Collection
    {
        return Collection::make($path)->flatMap(function ($path) {
            return str_ends_with($path, '.php') ? [$path] : $this->filesystem->glob($path . '/*_*.php');
        })->filter()->values()->keyBy(function ($file) {
            return $this->getRequirementName($file);
        })->sortBy(function ($file, $key) {
            return $key;
        });
    }
}
