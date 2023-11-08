<?php

declare(strict_types=1);

namespace CompleteSolar\ReleaseRequirement\Tests\Console\Commands;

use CompleteSolar\ReleaseRequirement\Console\Commands\RunnerTrait;
use CompleteSolar\ReleaseRequirement\Tests\BaseTestCase;
use CompleteSolar\ReleaseRequirement\Tests\Mocks\MockApplication;
use CompleteSolar\ReleaseRequirement\Tests\Mocks\MockConfig;
use CompleteSolar\ReleaseRequirement\Tests\Mocks\MockConsoleOutput;
use CompleteSolar\ReleaseRequirement\Tests\Mocks\MockRunRequirement;
use CompleteSolar\ReleaseRequirement\UseCases\RunRequirement;
use Illuminate\Console\OutputStyle;
use Illuminate\Foundation\Application;

/**
 * @coversDefaultClass \CompleteSolar\ReleaseRequirement\Console\Commands\RunnerTrait
 */
class RunnerTraitTest extends BaseTestCase
{
    use MockConsoleOutput;
    use MockApplication;
    use MockRunRequirement;
    use MockConfig;

    /**
     * @covers \CompleteSolar\ReleaseRequirement\Console\Commands\RunnerTrait::getRequirementRunner
     */
    public function testGetRequirementRunner(): void
    {
        $output = $this->getOutputMock();

        $app = $this->getApplicationMock();
        $app->expects($this->once())
            ->method('make')
            ->with(RunRequirement::class, ['output' => $output])
            ->willReturn($this->getRunRequirementMock());

        $command = new class ($app, $output) {
            use RunnerTrait;

            public function __construct(
                private readonly Application $laravel,
                private readonly OutputStyle $output,
            ) {
            }

            public function getRunner(): RunRequirement
            {
                return $this->getRequirementRunner();
            }
        };

        $this->assertInstanceOf(RunRequirement::class, $command->getRunner());
    }
}
