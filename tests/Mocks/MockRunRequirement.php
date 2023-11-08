<?php

declare(strict_types=1);

namespace CompleteSolar\ReleaseRequirement\Tests\Mocks;

use CompleteSolar\ReleaseRequirement\UseCases\RunRequirement;
use PHPUnit\Framework\MockObject\MockObject;

trait MockRunRequirement
{
    private function getRunRequirementMock(): MockObject|RunRequirement
    {
        return $this->getMockBuilder(RunRequirement::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
