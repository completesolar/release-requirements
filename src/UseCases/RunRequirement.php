<?php

declare(strict_types=1);

namespace CompleteSolar\ReleaseRequirement\UseCases;

use App;
use Exception;
use CompleteSolar\ReleaseRequirement\AbstractRequirement;
use CompleteSolar\ReleaseRequirement\Console\Commands\RunnerTrait;
use CompleteSolar\ReleaseRequirement\Models\Requirement;
use Illuminate\Console\OutputStyle;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Command\Command;

class RunRequirement
{
    use RunnerTrait;

    private string $stage;

    public function __construct(
        private readonly Filesystem $filesystem,
        private readonly OutputStyle $output
    ) {
    }

    public function run(string $stage, ?string $name = null): int
    {
        $this->setStage($stage);
        $path = config('requirement.path') . "/$this->stage";

        if ($name) {
            $path .= "/$name.php";

            if (!$this->filesystem->exists($path)) {
                $this->output->error("No $stage/$name requirement found!");

                return Command::FAILURE;
            }
        }

        if (!$this->filesystem->exists($path) || $this->filesystem->isEmptyDirectory(dirname($path))) {
            $this->output->info("No $this->stage requirements found. Skipped.");

            return Command::SUCCESS;
        }

        $requirements = $this->pendingRequirements(
            $this->getRequirementFiles($path),
            $this->getRanRequirements()
        );

        if (empty($requirements)) {
            $this->output->info("All $this->stage requirements are up to date. Skipped.");

            return Command::SUCCESS;
        }

        $this->runPending($requirements, [
            'output' => $this->output,
        ]);

        return Command::SUCCESS;
    }

    private function setStage(string $stage): void
    {
        $this->stage = $stage;
    }

    private function runPending(array $requirements, array $data): void
    {
        foreach ($requirements as $filePath) {
            $requirement = $this->filesystem->getRequire($filePath, $data);

            if (!$requirement instanceof AbstractRequirement) {
                throw new Exception("File($filePath) must return instance of AbstractRequirement!");
            }

            $name = $this->getRequirementName($filePath);

            $this->output->info("Running $name requirement");

            App::call([$requirement, 'run']);
            Requirement::create([
                'stage' => $this->stage,
                'name' => $name,
            ]);

            $this->output->success("$name - done.");
        }
    }

    private function getRanRequirements(): array
    {
        return Requirement::where('stage', $this->stage)->pluck('name')->toArray();
    }

    private function getRequirementFiles(string $path)
    {
        return Collection::make($path)->flatMap(function ($path) {
            return str_ends_with($path, '.php') ? [$path] : $this->filesystem->glob($path . '/*_*.php');
        })->filter()->values()->keyBy(function ($file) {
            return $this->getRequirementName($file);
        })->sortBy(function ($file, $key) {
            return $key;
        })->all();
    }

    private function pendingRequirements($files, $ran)
    {
        return Collection::make($files)
            ->reject(function ($file) use ($ran) {
                return in_array($this->getRequirementName($file), $ran);
            })->values()
            ->all();
    }

    private function getRequirementName(string $path): string
    {
        return str_replace('.php', '', basename($path));
    }
}
