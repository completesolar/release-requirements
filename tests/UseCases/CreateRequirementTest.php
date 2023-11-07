<?php

declare(strict_types=1);

namespace CompleteSolar\ReleaseRequirement\Tests\UseCases;

use CompleteSolar\ReleaseRequirement\Stage;
use CompleteSolar\ReleaseRequirement\Tests\BaseTestCase;
use CompleteSolar\ReleaseRequirement\UseCases\CreateRequirement;
use Illuminate\Config\Repository;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Filesystem\Filesystem;

/**
 * @coversDefaultClass \CompleteSolar\ReleaseRequirement\UseCases\CreateRequirement
 */
final class CreateRequirementTest extends BaseTestCase
{
    use WithFaker;

    /**
     * @covers ::run
     */
    public function testRunSuccess(): void
    {
        $fileSystem = $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()
            ->onlyMethods([
                'ensureDirectoryExists',
                'put'
            ])
            ->getMock();

        $fileSystem->expects($this->once())
            ->method('ensureDirectoryExists')
            ->willReturn(true);

        $fileSystem->expects($this->once())
            ->method('put')
            ->willReturn(true);

        $config = $this->getMockBuilder(Repository::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['get'])
            ->getMock();

        $config->expects($this->once())
            ->method('get')
            ->willReturn($dir = 'requirements');

        $useCase = new CreateRequirement($fileSystem, $config);

        $path = $useCase->run(
            $stage = $this->faker->randomElement(Stage::LIST),
            $name = $this->faker->word
        );
        $date = today()->format('Y_m_d');

        $this->assertTrue((bool) preg_match("/$dir\/$stage\/$date" . "_\d+_$name\.php/", $path));
    }
}
