<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\ValueObject\PhpVersion;
use RectorLaravel\Set\LaravelSetList;

return static function (RectorConfig $config): void {
    $config->paths([
        __DIR__ . '/app',
        __DIR__ . '/routes',
        __DIR__ . '/database',
        __DIR__ . '/tests',
    ]);

    $config->phpVersion(PhpVersion::PHP_83);

    $config->import(LaravelSetList::LARAVEL_120);

    $config->import(LevelSetList::UP_TO_PHP_83);
};
