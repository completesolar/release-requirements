<?php

declare(strict_types=1);

namespace CompleteSolar\ReleaseRequirement\Tests\Mocks;

use Illuminate\Foundation\Application;
use PHPUnit\Framework\MockObject\MockObject;

trait MockApplication
{
    private function getApplicationMock(): MockObject|Application
    {
        return $this->getMockBuilder(Application::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
