<?php

declare(strict_types=1);

namespace Railt\Extension\Router\Tests\Architecture;

use PHPat\Selector\Selector;
use PHPat\Test\Builder\Rule;
use PHPat\Test\PHPat;

final class ComposerDependenciesTest
{
    public function testDependencies(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::namespace('Railt\Extension\Router'))
                ->excluding(Selector::classname('Railt\Extension\Router\RouterExtension'))
            ->canOnlyDependOn()
            ->classes(
                Selector::namespace('Railt\Extension\Router'),
                // @dependencies
                Selector::namespace('Psr\Container'),
                Selector::namespace('Psr\EventDispatcher'),
                Selector::namespace('Railt\Contracts\Http'),
                Selector::namespace('Railt\TypeSystem'),
            )
        ;
    }

    public function testDevDependencies(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::classname('Railt\Extension\Router\RouterExtension'))
            ->canOnlyDependOn()
            ->classes(
                Selector::namespace('Railt\Extension\Router'),
                // @dependencies
                Selector::namespace('Psr\Container'),
                Selector::namespace('Psr\EventDispatcher'),
                Selector::namespace('Railt\TypeSystem'),
                // @dev-dependencies
                Selector::namespace('Railt\Contracts\Http'),
                Selector::namespace('Railt\Foundation'),
                Selector::namespace('Symfony\Component\EventDispatcher'),
            )
        ;
    }
}
