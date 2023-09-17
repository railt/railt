<?php

declare(strict_types=1);

namespace Railt\EventDispatcher\Tests\Architecture;

use PHPat\Selector\Selector;
use PHPat\Test\Builder\Rule;
use PHPat\Test\PHPat;

final class ComposerDependenciesTest
{
    public function testDependencies(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::namespace('Railt\EventDispatcher'))
            ->canOnlyDependOn()
            ->classes(
                Selector::namespace('Railt\EventDispatcher'),
                // @dependencies
                Selector::namespace('Psr\EventDispatcher')
            )
        ;
    }
}
