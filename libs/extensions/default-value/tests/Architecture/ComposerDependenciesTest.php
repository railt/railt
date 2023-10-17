<?php

declare(strict_types=1);

namespace Railt\Extension\DefaultValue\Tests\Architecture;

use PHPat\Selector\Selector;
use PHPat\Test\Builder\Rule;
use PHPat\Test\PHPat;

final class ComposerDependenciesTest
{
    public function testDependencies(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::namespace('Railt\Extension\DefaultValue'))
                ->excluding(Selector::classname('Railt\Extension\DefaultValue\DefaultValueExtension'))
            ->canOnlyDependOn()
            ->classes(
                Selector::namespace('Railt\Extension\DefaultValue'),
                // @dependencies
                Selector::namespace('Psr\Container'),
                Selector::namespace('Psr\EventDispatcher'),
                Selector::namespace('Railt\TypeSystem'),
            )
        ;
    }
}
