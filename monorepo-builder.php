<?php

declare(strict_types=1);

use Symplify\MonorepoBuilder\Config\MBConfig;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\AddTagToChangelogReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\PushNextDevReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\PushTagReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\SetCurrentMutualDependenciesReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\SetNextMutualDependenciesReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\TagVersionReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\UpdateBranchAliasReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\UpdateReplaceReleaseWorker;

return static function (MBConfig $config): void {
    $config->packageAliasFormat('<major>.<minor>.x-dev');

    $config->packageDirectories([
        __DIR__ . '/libs',
        __DIR__ . '/libs/contracts',
        __DIR__ . '/libs/executors',
        __DIR__ . '/libs/extensions',
    ]);

    $config->dataToAppend([
        'require-dev' => [
            'friendsofphp/php-cs-fixer'=> '^3.27',
            'phpat/phpat'=> '^0.10',
            'phplrt/phplrt'=> '^3.2.7',
            'phpunit/phpunit'=> '^10.3',
            'symfony/cache'=> '^5.4|^6.0',
            'symfony/console'=> '^5.4|^6.0',
            'symfony/var-dumper'=> '^5.4|^6.0',
            'symplify/monorepo-builder'=> '^11.2',
            'vimeo/psalm'=> '^5.15'
        ],
    ]);

    $config->workers([
        UpdateReplaceReleaseWorker::class,
        SetCurrentMutualDependenciesReleaseWorker::class,
        AddTagToChangelogReleaseWorker::class,
        TagVersionReleaseWorker::class,
        PushTagReleaseWorker::class,
        SetNextMutualDependenciesReleaseWorker::class,
        UpdateBranchAliasReleaseWorker::class,
        PushNextDevReleaseWorker::class,
    ]);
};
