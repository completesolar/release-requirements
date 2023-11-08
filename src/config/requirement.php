<?php

declare(strict_types=1);

return [
    'path' => base_path(env('REQUIREMENTS_PATH', 'requirements')),
    'enabled' => env('REQUIREMENTS_ENABLED', true),
];
