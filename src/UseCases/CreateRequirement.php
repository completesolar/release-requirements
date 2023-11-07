<?php

declare(strict_types=1);

namespace CompleteSolar\ReleaseRequirement\UseCases;

use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;

/**
 * @see \CompleteSolar\ReleaseRequirement\Tests\UseCases\CreateRequirementTest
 */
class CreateRequirement
{
    public function __construct(private readonly Filesystem $files, private readonly Repository $config)
    {
    }

    public function run(string $stage, string $name): string
    {
        $path = $this->getFullFilePath($stage, $name);
        $this->files->ensureDirectoryExists(dirname($path));
        $this->files->put($path, $this->getStub());

        return $path;
    }

    private function getStub(): string
    {
        return $this->files->get($this->stubPath() . '/requirement.create.stub');
    }

    private function stubPath(): string
    {
        return __DIR__ . '/stubs';
    }

    private function getFullFilePath(string $stage, string $name): string
    {
        return implode('/', [
            $this->config->get('requirement.path'),
            $stage,
            date('Y_m_d_His') . "_$name.php",
        ]);
    }
}
