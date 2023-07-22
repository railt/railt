<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Tests;

use PHPUnit\Framework\Attributes\Group;
use Railt\TypeSystem\Definition\SchemaDefinition;

#[Group('railt/type-system')]
class DefinitionStringRepresentationTest extends TestCase
{
    public function testSchema(): void
    {
        $schema = (string)(new SchemaDefinition());

        self::assertSame('schema', $schema);
    }
}
