<?php

declare(strict_types=1);

namespace CompleteSolar\ReleaseRequirement;

use CompleteSolar\ReleaseRequirement\Exceptions\NoRequirementRunMethodException;
use Illuminate\Console\OutputStyle;

/**
 * @method void run() Run current requirement. All method arguments will be resolved by an app service container.
 *
 * @see \CompleteSolar\ReleaseRequirement\Tests\AbstractRequirementTest
 */
abstract class AbstractRequirement
{
    /**
     * @throws NoRequirementRunMethodException
     */
    public function __construct(protected readonly OutputStyle $output)
    {
        if (!method_exists($this, 'run')) {
            throw new NoRequirementRunMethodException();
        }
    }
}
