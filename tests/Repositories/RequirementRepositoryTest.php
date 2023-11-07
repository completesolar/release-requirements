<?php

declare(strict_types=1);

namespace CompleteSolar\ReleaseRequirement\Tests\Repositories;

use CompleteSolar\ReleaseRequirement\Repositories\RequirementRepository;
use CompleteSolar\ReleaseRequirement\Stage;
use CompleteSolar\ReleaseRequirement\Tests\BaseTestCase;
use CompleteSolar\ReleaseRequirement\Tests\Mocks\MockFilesystem;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * @coversDefaultClass \CompleteSolar\ReleaseRequirement\Repositories\RequirementRepository
 */
class RequirementRepositoryTest extends BaseTestCase
{
    use WithFaker;
    use MockFilesystem;

    /**
     * @covers ::getPendingRequirements
     */
    public function testGetPendingRequirementsForFile(): void
    {
        $name = $this->faker->word;
        $stage = $this->faker->randomElement(Stage::LIST);
        $path = "$stage/$name.php";

        $repository = $this->getMockBuilder(RequirementRepository::class)
            ->setConstructorArgs(['filesystem' => $this->getFilesystemMock()])
            ->onlyMethods(['getRanRequirements'])
            ->getMock();

        $repository->expects($this->once())
            ->method('getRanRequirements')
            ->willReturn([$this->faker->word]);

        $requirements = $repository->getPendingRequirements($stage, $path);

        $this->assertContains($path, $requirements);
    }

    /**
     * @covers ::getPendingRequirements
     */
    public function testGetPendingRequirementsStage(): void
    {
        $stage = $this->faker->randomElement(Stage::LIST);

        $filesystem = $this->getFilesystemMock();
        $filesystem->expects($this->once())
            ->method('glob')
            ->willReturn([$path = $this->faker->word . '.php']);

        $repository = $this->getMockBuilder(RequirementRepository::class)
            ->setConstructorArgs(['filesystem' => $filesystem])
            ->onlyMethods(['getRanRequirements'])
            ->getMock();

        $repository->expects($this->once())
            ->method('getRanRequirements')
            ->willReturn([]);

        $requirements = $repository->getPendingRequirements($stage, $stage);
        $this->assertContains($path, $requirements);
    }
}
