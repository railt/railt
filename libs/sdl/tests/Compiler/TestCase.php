<?php

declare(strict_types=1);

namespace Railt\SDL\Tests\Compiler;

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Railt\SDL\Compiler;
use Railt\SDL\DictionaryInterface;
use Railt\TypeSystem\DirectiveDefinition;
use Railt\TypeSystem\NamedTypeDefinition;

#[Group('unit'), Group('sdl')]
abstract class TestCase extends BaseTestCase
{
    protected function compile(string $schema): DictionaryInterface
    {
        return (new Compiler())
            ->compile($schema)
        ;
    }

    protected function type(string $type, string $schema): ?NamedTypeDefinition
    {
        return $this->compile($schema)
            ->findTypeDefinition($type)
        ;
    }

    protected function directive(string $type, string $schema): ?DirectiveDefinition
    {
        return $this->compile($schema)
            ->findDirectiveDefinition($type)
        ;
    }
}