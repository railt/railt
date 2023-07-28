<?php

declare(strict_types=1);

namespace Railt\Contracts\Http\Factory\Tests\Architecture;

use PHPat\Selector\Selector;
use PHPat\Test\Builder\Rule;
use PHPat\Test\PHPat;

final class ComposerDependenciesTest
{
    public function testDependencies(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::namespace('Railt\Contracts\Http\Factory'))
            ->canOnlyDependOn()
            ->classes(
                Selector::namespace('Railt\Contracts\Http\Factory'),
                // @dependencies
                Selector::namespace('Railt\Contracts\Http'),
            )
        ;
    }
}
