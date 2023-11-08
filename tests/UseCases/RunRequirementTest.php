<?php

declare(strict_types=1);

namespace CompleteSolar\ReleaseRequirement\Tests\UseCases;

use CompleteSolar\ReleaseRequirement\AbstractRequirement;
use CompleteSolar\ReleaseRequirement\Exceptions\UndefinedRequirementTypeException;
use CompleteSolar\ReleaseRequirement\Stage;
use CompleteSolar\ReleaseRequirement\Tests\BaseTestCase;
use CompleteSolar\ReleaseRequirement\Tests\Mocks\MockApplication;
use CompleteSolar\ReleaseRequirement\Tests\Mocks\MockConfig;
use CompleteSolar\ReleaseRequirement\Tests\Mocks\MockConsoleOutput;
use CompleteSolar\ReleaseRequirement\Tests\Mocks\MockFilesystem;
use CompleteSolar\ReleaseRequirement\Tests\Mocks\MockRequirementRepository;
use CompleteSolar\ReleaseRequirement\UseCases\RunRequirement;
use Illuminate\Console\Command;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * @coversDefaultClass \CompleteSolar\ReleaseRequirement\UseCases\RunRequirement
 */
class RunRequirementTest extends BaseTestCase
{
    use WithFaker;
    use MockApplication;
    use MockConfig;
    use MockConsoleOutput;
    use MockFilesystem;
    use MockRequirementRepository;

    public static function successDataForRun(): array
    {
        return [
            [
                'name' => 'requirement_name',
            ],

            [
                'name' => null,
            ],
        ];
    }

    /**
     * @dataProvider successDataForRun
     * @covers ::run
     */
    public function testRunByName(?string $name): void
    {
        $withName = $name !== null;
        $stage = $this->getRandomStage();
        $name = $name ?? $this->faker->word;

        $output = $this->getOutputMock();

        $output->expects($this->once())
            ->method('info')
            ->with("Running $name requirement:");

        $output->expects($this->once())
            ->method('success')
            ->with("$name - done.");

        $filesystem = $this->getFilesystemMock();

        $filesystem->expects($this->once())
            ->method('exists')
            ->withAnyParameters()
            ->willReturn(true);

        $filesystem->expects($this->once())
            ->method('getRequire')
            ->withAnyParameters()
            ->willReturn($requirementObject = new class ($output) extends AbstractRequirement {
                public function run(): void
                {
                }
            });

        $repository = $this->getRepositoryMock();

        $repository->expects($this->once())
            ->method('getPendingRequirements')
            ->willReturn(["$name.php"]);

        $repository->expects($this->once())
            ->method('addRequirementToRan')
            ->with($stage, $name);

        $app = $this->getApplicationMock();

        $app->expects($this->once())
            ->method('call')
            ->with([$requirementObject, 'run']);

        $useCase = new RunRequirement($app, $filesystem, $output, $repository);

        $this->assertSame(Command::SUCCESS, $useCase->run($stage, $withName ? $name : null));
    }

    /**
     * @covers ::run
     */
    public function testFileWithNameDoesntExist(): void
    {
        $stage = $this->getRandomStage();
        $name = $this->faker->word;
        $output = $this->getOutputMock();

        $output->expects($this->once())
            ->method('error')
            ->with("No $stage/$name requirement found!");

        $useCase = new RunRequirement($this->getApplicationMock(), $this->getFilesystemMock(), $output, $this->getRepositoryMock());

        $this->assertSame(Command::FAILURE, $useCase->run($stage, $name));
    }

    /**
     * @covers ::run
     */
    public function testNoRequirementDirectoryFound(): void
    {
        $stage = $this->getRandomStage();

        $output = $this->getOutputMock();
        $output->expects($this->once())
            ->method('info')
            ->with("No $stage requirements found. Skipped.");

        $filesystem = $this->getFilesystemMock();
        $filesystem->expects($this->once())
            ->method('exists')
            ->with("/$stage")
            ->willReturn(false);

        $useCase = new RunRequirement($this->getApplicationMock(), $filesystem, $output, $this->getRepositoryMock());

        $this->assertSame(Command::SUCCESS, $useCase->run($stage));
    }

    /**
     * @covers ::run
     */
    public function testNoRequirementsInDirectory(): void
    {
        $stage = $this->getRandomStage();
        $path = "/$stage";
        $output = $this->getOutputMock();

        $output->expects($this->once())
            ->method('info')
            ->with("No $stage requirements found. Skipped.");

        $filesystem = $this->getFilesystemMock();
        $filesystem->expects($this->once())
            ->method('exists')
            ->with($path)
            ->willReturn(true);
        $filesystem->expects($this->once())
            ->method('isEmptyDirectory')
            ->with($path)
            ->willReturn(true);

        $useCase = new RunRequirement($this->getApplicationMock(), $filesystem, $output, $this->getRepositoryMock());

        $this->assertSame(Command::SUCCESS, $useCase->run($stage));
    }

    /**
     * @covers ::run
     */
    public function testAllRequirementsAreUpToDate(): void
    {
        $stage = $this->getRandomStage();
        $path = "/$stage";
        $output = $this->getOutputMock();

        $output->expects($this->once())
            ->method('info')
            ->with("All $stage requirements are up to date. Skipped.");

        $filesystem = $this->getFilesystemMock();
        $filesystem->expects($this->once())
            ->method('exists')
            ->with($path)
            ->willReturn(true);
        $filesystem->expects($this->once())
            ->method('isEmptyDirectory')
            ->with($path)
            ->willReturn(false);

        $repository = $this->getRepositoryMock();
        $repository->expects($this->once())
            ->method('getPendingRequirements')
            ->with($stage, $path)
            ->willReturn([]);

        $useCase = new RunRequirement($this->getApplicationMock(), $filesystem, $output, $repository);

        $this->assertSame(Command::SUCCESS, $useCase->run($stage));
    }

    /**
     * @covers ::run
     * @covers \CompleteSolar\ReleaseRequirement\Exceptions\UndefinedRequirementTypeException::__construct
     */
    public function testWrongRequirementFileReturnType(): void
    {
        $name = $this->faker->word;
        $stage = $this->getRandomStage();
        $path = "/$stage";
        $output = $this->getOutputMock();

        $filesystem = $this->getFilesystemMock();
        $filesystem->expects($this->once())
            ->method('exists')
            ->with($path)
            ->willReturn(true);
        $filesystem->expects($this->once())
            ->method('isEmptyDirectory')
            ->with($path)
            ->willReturn(false);

        $repository = $this->getRepositoryMock();
        $repository->expects($this->once())
            ->method('getPendingRequirements')
            ->with($stage, $path)
            ->willReturn([$name]);

        $this->expectException(UndefinedRequirementTypeException::class);

        $useCase = new RunRequirement($this->getApplicationMock(), $filesystem, $output, $repository);

        $useCase->run($stage);
    }

    private function getRandomStage(): string
    {
        return $this->faker->randomElement(Stage::LIST);
    }
}
