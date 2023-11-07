<?php

declare(strict_types=1);

namespace CompleteSolar\ReleaseRequirement\Tests\Mocks;

use CompleteSolar\ReleaseRequirement\Repositories\RequirementRepository;
use PHPUnit\Framework\MockObject\MockObject;

trait MockRequirementRepository
{
    private function getRepositoryMock(): MockObject|RequirementRepository
    {
        return $this->getMockBuilder(RequirementRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
