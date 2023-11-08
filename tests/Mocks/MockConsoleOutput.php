<?php

declare(strict_types=1);

namespace CompleteSolar\ReleaseRequirement\Tests\Mocks;

use Illuminate\Console\OutputStyle;
use PHPUnit\Framework\MockObject\MockObject;

trait MockConsoleOutput
{
    private function getOutputMock(): MockObject|OutputStyle
    {
        return $this->getMockBuilder(OutputStyle::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
