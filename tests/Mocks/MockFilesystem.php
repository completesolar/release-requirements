<?php

declare(strict_types=1);

namespace CompleteSolar\ReleaseRequirement\Tests\Mocks;

use Illuminate\Filesystem\Filesystem;
use PHPUnit\Framework\MockObject\MockObject;

trait MockFilesystem
{
    private function getFilesystemMock(): MockObject|Filesystem
    {
        return $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
