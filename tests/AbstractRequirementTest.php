<?php

declare(strict_types=1);

namespace CompleteSolar\ReleaseRequirement\Tests;

use CompleteSolar\ReleaseRequirement\AbstractRequirement;
use CompleteSolar\ReleaseRequirement\Exceptions\NoRequirementRunMethodException;
use CompleteSolar\ReleaseRequirement\Tests\Mocks\MockConsoleOutput;

/**
 * @coversDefaultClass \CompleteSolar\ReleaseRequirement\AbstractRequirement
 */
class AbstractRequirementTest extends BaseTestCase
{
    use MockConsoleOutput;

    /**
     * @covers ::__construct
     */
    public function testRunMethodDoesNotExists(): void
    {
        try {
            new class ($this->getOutputMock()) extends AbstractRequirement {
            };
        } catch (NoRequirementRunMethodException $exception) {
            $this->assertInstanceOf(NoRequirementRunMethodException::class, $exception);
        }
    }

    /**
     * @covers ::__construct
     */
    public function testRunMethodExists(): void
    {
        $requirement = new class ($this->getOutputMock()) extends AbstractRequirement {
            public function run(): void
            {
            }
        };

        $this->assertNull($requirement->run());
    }
}
