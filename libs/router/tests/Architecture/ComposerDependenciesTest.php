<?php

declare(strict_types=1);

namespace Railt\Router\Tests\Architecture;

use PHPat\Selector\Selector;
use PHPat\Test\Builder\Rule;
use PHPat\Test\PHPat;

final class ComposerDependenciesTest
{
    public function testDependencies(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::namespace('Railt\Router'))
                ->excluding(Selector::classname('Railt\Router\RouterExtension'))
            ->canOnlyDependOn()
            ->classes(
                Selector::namespace('Railt\Router'),
                // @dependencies
                Selector::namespace('Psr\Container'),
                Selector::namespace('Psr\EventDispatcher'),
                Selector::namespace('Railt\Contracts\Http'),
            )
        ;
    }

    /*public function testDevDependencies(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::classname('Railt\Router\RouterExtension'))
            ->canOnlyDependOn()
            ->classes(
                Selector::namespace('Railt\Router'),
            )
        ;
    }*/
}
