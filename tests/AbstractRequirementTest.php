<?php

declare(strict_types=1);

namespace CompleteSolar\ReleaseRequirement\Tests;

use CompleteSolar\ReleaseRequirement\AbstractRequirement;
use CompleteSolar\ReleaseRequirement\Exceptions\NoRequirementRunMethodException;
use CompleteSolar\ReleaseRequirement\Tests\Mocks\MockConsoleOutput;
use Symfony\Component\HttpFoundation\Response;

/**
 * @coversDefaultClass \CompleteSolar\ReleaseRequirement\AbstractRequirement
 */
class AbstractRequirementTest extends BaseTestCase
{
    use MockConsoleOutput;

    /**
     * @covers ::__construct
     * @covers \CompleteSolar\ReleaseRequirement\Exceptions\NoRequirementRunMethodException::__construct
     */
    public function testRunMethodDoesNotExists(): void
    {
        $exception = null;

        try {
            new class ($this->getOutputMock()) extends AbstractRequirement {
            };
        } catch (NoRequirementRunMethodException $e) {
            $exception = $e;
        }

        $this->assertInstanceOf(NoRequirementRunMethodException::class, $exception);
        $this->assertSame(Response::HTTP_INTERNAL_SERVER_ERROR, $exception->getCode());
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

        /** @noinspection PhpVoidFunctionResultUsedInspection */
        $this->assertNull($requirement->run());
    }
}
