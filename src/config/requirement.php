<?php

declare(strict_types=1);

return [
    'path' => base_path(env('REQUIREMENT_PATH', 'requirements')),
    'enable' => env('REQUIREMENT_ENABLE', true),
];
