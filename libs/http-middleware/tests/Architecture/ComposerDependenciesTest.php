<?php

declare(strict_types=1);

namespace Railt\Http\Middleware\Tests\Architecture;

use PHPat\Selector\Selector;
use PHPat\Test\Builder\Rule;
use PHPat\Test\PHPat;

final class ComposerDependenciesTest
{
    public function testDependencies(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::namespace('Railt\Http\Middleware'))
            ->canOnlyDependOn()
            ->classes(
                Selector::namespace('Railt\Http\Middleware'),
                // @dependencies
                Selector::namespace('Railt\Contracts\Http'),
                Selector::namespace('Railt\Contracts\Http\Middleware'),
            )
        ;
    }
}
