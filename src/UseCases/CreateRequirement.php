<?php

declare(strict_types=1);

namespace CompleteSolar\ReleaseRequirement\UseCases;

use CompleteSolar\ReleaseRequirement\Console\Commands\RunnerTrait;
use Illuminate\Filesystem\Filesystem;

class CreateRequirement
{
    use RunnerTrait;

    public function __construct(private readonly Filesystem $files)
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
            config('requirement.path'),
            $stage,
            date('Y_m_d_His') . "_$name.php",
        ]);
    }
}
