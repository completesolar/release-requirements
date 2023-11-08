<?php

declare(strict_types=1);

namespace CompleteSolar\ReleaseRequirement\Tests\Console\Commands;

use CompleteSolar\ReleaseRequirement\Console\Commands\CreateRequirementCommand;
use CompleteSolar\ReleaseRequirement\Stage;
use CompleteSolar\ReleaseRequirement\Tests\BaseTestCase;
use CompleteSolar\ReleaseRequirement\Tests\Mocks\MockCreateRequirement;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\Console\Command\Command;

/**
 * @coversDefaultClass \CompleteSolar\ReleaseRequirement\Console\Commands\CreateRequirementCommand
 */
class CreateRequirementCommandTest extends BaseTestCase
{
    use MockCreateRequirement;
    use WithFaker;

    /**
     * @covers ::handle
     * @covers \CompleteSolar\ReleaseRequirement\Console\Commands\AbstractRequirementCommand::isInvalidStage
     * @covers \CompleteSolar\ReleaseRequirement\Console\Commands\AbstractRequirementCommand::showInvalidStageError
     */
    public function testWithWrongStages(): void
    {
        $command = $this->getMockBuilder(CreateRequirementCommand::class)
            ->onlyMethods(['argument', 'error'])
            ->getMock();

        $command->expects($this->once())
            ->method('argument')
            ->with('stage')
            ->willReturn($this->faker->word);

        $command->expects($this->once())
            ->method('error')
            ->with("Undefined stage. Available stages: " . implode(', ', Stage::LIST));

        $this->assertSame(
            Command::FAILURE,
            $command->handle($this->getCreateRequirementMock())
        );
    }

    /**
     * @covers ::handle
     * @covers \CompleteSolar\ReleaseRequirement\Console\Commands\AbstractRequirementCommand::isInvalidStage
     */
    public function testSuccess(): void
    {
        $command = $this->getMockBuilder(CreateRequirementCommand::class)
            ->onlyMethods(['argument', 'info'])
            ->getMock();

        $command->expects($this->exactly(2))
            ->method('argument')
            ->willReturnMap([
                ['stage', $stage = $this->faker->randomElement(Stage::LIST)],
                ['name', $name = $this->faker->word],
            ]);

        $filePath = "requirements/$stage/$name";

        $command->expects($this->once())
            ->method('info')
            ->with("Requirement($stage) $filePath is created successfully.");

        $useCase = $this->getCreateRequirementMock();
        $useCase->expects($this->once())
            ->method('run')
            ->with($stage, $name)
            ->willReturn($filePath);

        $this->assertSame(Command::SUCCESS, $command->handle($useCase));
    }
}
