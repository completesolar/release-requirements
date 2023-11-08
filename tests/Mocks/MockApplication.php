<?php

declare(strict_types=1);

namespace CompleteSolar\ReleaseRequirement\Tests\Mocks;

use Illuminate\Foundation\Application;
use PHPUnit\Framework\MockObject\MockObject;

trait MockApplication
{
    private function getApplicationMock(): MockObject|Application
    {
        $app = $this->getMockBuilder(Application::class)
            ->disableOriginalConstructor()
            ->getMock();


        $app->expects($this->once())
            ->method('get')
            ->willReturnMap([
                ['config', $this->getConfigMock()],
            ]);

        return $app;
    }
}
