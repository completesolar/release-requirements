<?php

declare(strict_types=1);

namespace CompleteSolar\ReleaseRequirement\Tests\Mocks;

use Illuminate\Config\Repository;
use PHPUnit\Framework\MockObject\MockObject;

trait MockConfig
{
    private function getConfigMock(): MockObject|Repository
    {
        $config = $this->getMockBuilder(Repository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $config->expects($this->once())
            ->method('get')
            ->with('requirement.path')
            ->willReturn('');

        return $config;
    }
}
