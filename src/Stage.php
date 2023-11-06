<?php

declare(strict_types=1);

namespace CompleteSolar\ReleaseRequirement;

class Stage
{
    public const BEFORE_MIGRATE = 'before_migrate';

    public const AFTER_MIGRATE = 'after_migrate';

    public const BEFORE_SEED = 'before_seed';

    public const AFTER_SEED = 'after_seed';

    public const LIST = [
        self::BEFORE_MIGRATE,
        self::BEFORE_SEED,
        self::AFTER_SEED,
    ];
}
