<?php

declare(strict_types=1);

namespace CompleteSolar\ReleaseRequirement\Tests\Console\Commands;

use CompleteSolar\ReleaseRequirement\Console\Commands\SeedWithRequirementCommand;
use CompleteSolar\ReleaseRequirement\Stage;
use CompleteSolar\ReleaseRequirement\Tests\BaseTestCase;
use CompleteSolar\ReleaseRequirement\Tests\Mocks\MockRunRequirement;
use CompleteSolar\ReleaseRequirement\UseCases\RunRequirement;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Console\Command\Command;

/**
 * @coversDefaultClass \CompleteSolar\ReleaseRequirement\Console\Commands\SeedWithRequirementCommand
 */
final class SeedWithRequirementCommandTest extends BaseTestCase
{
    use MockRunRequirement;

    /**
     * @covers ::handle
     */
    public function testFailedOnBeforeSeedStage(): void
    {
        $useCase = $this->getRunRequirementMock();
        $useCase->expects($this->once())
            ->method('run')
            ->with(Stage::BEFORE_SEED)
            ->willReturn(Command::FAILURE);

        $command = $this->getCommandMock($useCase);

        $this->assertSame(Command::FAILURE, $command->handle());
    }

    /**
     * @covers ::handle
     */
    public function testFailedOnMigrateStage(): void
    {
        $useCase = $this->getRunRequirementMock();
        $useCase->expects($this->once())
            ->method('run')
            ->with(Stage::BEFORE_SEED)
            ->willReturn(Command::SUCCESS);

        $command = $this->getCommandMock($useCase);

        $command->expects($this->once())
            ->method('runSeeds')
            ->willReturn(Command::FAILURE);

        $this->assertSame(Command::FAILURE, $command->handle());
    }

    /**
     * @covers ::handle
     */
    public function testSuccessOnAfterMigrateStage(): void
    {
        $useCase = $this->getRunRequirementMock();
        $useCase->expects($this->exactly(2))
            ->method('run')
            ->withAnyParameters()
        ->willReturn(Command::SUCCESS);

        $command = $this->getCommandMock($useCase);

        $command->expects($this->once())
            ->method('runSeeds')
            ->willReturn(Command::SUCCESS);

        $this->assertSame(Command::SUCCESS, $command->handle());
    }

    private function getCommandMock(MockObject|RunRequirement $useCase): MockObject|SeedWithRequirementCommand
    {
        $command = $this->getMockBuilder(SeedWithRequirementCommand::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getRequirementRunner', 'runSeeds'])
            ->getMock();

        $command->expects($this->once())
            ->method('getRequirementRunner')
            ->willReturn($useCase);

        return $command;
    }
}
