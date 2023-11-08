<?php

declare(strict_types=1);

namespace CompleteSolar\ReleaseRequirement\Tests\Mocks;

use CompleteSolar\ReleaseRequirement\UseCases\CreateRequirement;
use PHPUnit\Framework\MockObject\MockObject;

trait MockCreateRequirement
{
    private function getCreateRequirementMock(): MockObject|CreateRequirement
    {
        return $this->getMockBuilder(CreateRequirement::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
