<?php

declare(strict_types=1);

namespace Railt\SDL\Tests\Architecture;

use PHPat\Selector\Selector;
use PHPat\Test\Builder\Rule;
use PHPat\Test\PHPat;

final class ComposerDependenciesTest
{
    public function testDependencies(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::namespace('Railt\SDL'))
                ->excluding(Selector::namespace('Railt\SDL\Command'))
            ->canOnlyDependOn()
            ->classes(
                Selector::namespace('Railt\SDL'),
                Selector::classname(\Attribute::class),
                // @dependencies
                Selector::namespace('Psr\SimpleCache'),
                Selector::namespace('Phplrt\Contracts\Ast'),
                Selector::namespace('Phplrt\Contracts\Exception'),
                Selector::namespace('Phplrt\Contracts\Lexer'),
                Selector::namespace('Phplrt\Contracts\Parser'),
                Selector::namespace('Phplrt\Contracts\Position'),
                Selector::namespace('Phplrt\Contracts\Source'),
                Selector::namespace('Phplrt\Lexer'),
                Selector::namespace('Phplrt\Parser'),
                Selector::namespace('Phplrt\Position'),
                Selector::namespace('Phplrt\Source'),
                Selector::namespace('Railt\TypeSystem'),
                Selector::classname('voku\helper\UTF8'),
                // @dev-dependencies (optional)
                Selector::namespace('SebastianBergmann\Environment'),
            )
        ;
    }

    public function testDevDependencies(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::namespace('Railt\SDL\Command'))
            ->canOnlyDependOn()
            ->classes(
                Selector::namespace('Railt\SDL'),
                Selector::namespace('Phplrt\Source'),
                // @suggest
                Selector::namespace('Phplrt\Compiler'),
                Selector::namespace('Symfony\Component\Console'),
            )
        ;
    }
}
