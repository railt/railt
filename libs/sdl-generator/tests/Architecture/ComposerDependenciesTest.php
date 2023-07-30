<?php

declare(strict_types=1);

namespace Railt\SDL\Generator\Tests\Architecture;

use PHPat\Selector\Selector;
use PHPat\Test\Builder\Rule;
use PHPat\Test\PHPat;

final class ComposerDependenciesTest
{
    public function testDependencies(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::namespace('Railt\SDL\Generator'))
            ->canOnlyDependOn()
            ->classes(
                Selector::namespace('Railt\SDL\Generator'),
                Selector::classname(\Attribute::class),
                // @dependencies
                Selector::namespace('Railt\TypeSystem'),
            )
        ;
    }
}
