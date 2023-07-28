<?php

declare(strict_types=1);

namespace Railt\Http\Tests\Architecture;

use PHPat\Selector\Selector;
use PHPat\Test\Builder\Rule;
use PHPat\Test\PHPat;

final class ComposerDependenciesTest
{
    public function testDependencies(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::namespace('Railt\Http'))
            ->canOnlyDependOn()
            ->classes(
                Selector::namespace('Railt\Http'),
                Selector::classname(\Attribute::class),
                // @dependencies
                Selector::namespace('Railt\Contracts\Http'),
            )
        ;
    }
}
