<?php

declare(strict_types=1);

namespace CompleteSolar\ReleaseRequirement\Tests;

use CompleteSolar\ReleaseRequirement\PathTrait;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;

/**
 * @coversDefaultClass \CompleteSolar\ReleaseRequirement\PathTrait
 */
class PathTraitTest extends BaseTestCase
{
    use WithFaker;

    /**
     * @covers \CompleteSolar\ReleaseRequirement\PathTrait::getRequirementName
     */
    public function testGetRequirementName(): void
    {
        $object = new class () {
            use PathTrait;

            public function test(string $path): string
            {
                return $this->getRequirementName($path);
            }
        };

        $name = $this->faker->word;

        $this->assertTrue(Str::contains($name, $object->test("$name.php")));
    }
}
