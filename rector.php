<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

// Kami's rules, shared with the package. Tests are left out: Rector never rewrites an assertion.
return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/app',
        __DIR__ . '/bootstrap',
        __DIR__ . '/config',
        __DIR__ . '/database',
        __DIR__ . '/routes',
    ])
    ->withSkip([__DIR__ . '/bootstrap/cache'])
    ->withSets([__DIR__ . '/vendor/psyao/kami/rector-set.php']);
