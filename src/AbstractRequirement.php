<?php

declare(strict_types=1);

namespace CompleteSolar\ReleaseRequirement;

use Illuminate\Console\OutputStyle;
use Illuminate\Http\Response;
use LogicException;

/**
 * @method void run() Run current requirement. All method arguments will be resolved by an app service container.
 */
abstract class AbstractRequirement
{
    public function __construct(protected readonly OutputStyle $output)
    {
        if (!method_exists($this, 'run')) {
            throw new LogicException("You must declare 'run' method to use this class.", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
