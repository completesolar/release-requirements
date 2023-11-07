<?php

declare(strict_types=1);

namespace CompleteSolar\ReleaseRequirement\UseCases;

use CompleteSolar\ReleaseRequirement\Exceptions\UndefinedRequirementTypeException;
use CompleteSolar\ReleaseRequirement\Repositories\RequirementRepository;
use CompleteSolar\ReleaseRequirement\PathTrait;
use CompleteSolar\ReleaseRequirement\AbstractRequirement;
use Illuminate\Config\Repository;
use Illuminate\Console\OutputStyle;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Symfony\Component\Console\Command\Command;

class RunRequirement
{
    use PathTrait;

    private string $stage;

    private readonly Filesystem $filesystem;

    private readonly Repository $config;

    public function __construct(
        private readonly Application $app,
        private readonly OutputStyle $output,
        private readonly RequirementRepository $requirementRepository
    ) {
        $this->filesystem = $this->app->get('filesystem');
        $this->config = $this->app->get('config');
    }

    public function run(string $stage, ?string $name = null): int
    {
        $this->setStage($stage);
        $path = $this->config->get('requirement.path') . "/$this->stage";

        if ($name) {
            $path .= "/$name.php";

            if (!$this->filesystem->exists($path)) {
                $this->output->error("No $stage/$name requirement found!");

                return Command::FAILURE;
            }
        } elseif (!$this->filesystem->exists($path) || $this->filesystem->isEmptyDirectory($path)) {
            $this->output->info("No $this->stage requirements found. Skipped.");

            return Command::SUCCESS;
        }

        $pendingRequirements = $this->requirementRepository->getPendingRequirements($this->stage, $path);

        if (empty($pendingRequirements)) {
            $this->output->info("All $this->stage requirements are up to date. Skipped.");

            return Command::SUCCESS;
        }

        $this->runPending($pendingRequirements, [
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
                throw new UndefinedRequirementTypeException($filePath);
            }

            $name = $this->getRequirementName($filePath);

            $this->output->info("Running $name requirement:");

            $this->app->call([$requirement, 'run']);
            $this->requirementRepository->addRequirementToRan($this->stage, $name);

            $this->output->success("$name - done.");
        }
    }
}
