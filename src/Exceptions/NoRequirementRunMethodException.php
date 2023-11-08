<?php

declare(strict_types=1);

namespace CompleteSolar\ReleaseRequirement\Exceptions;

use Exception;
use Illuminate\Http\Response;

class NoRequirementRunMethodException extends Exception
{
    public function __construct()
    {
        parent::__construct(
            "You must declare 'run' method to use this class.",
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
