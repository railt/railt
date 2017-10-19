<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Reflection;

use Railt\Reflection\Builder\DocumentBuilder;
use Railt\Reflection\Contracts\Definitions\ObjectDefinition;
use Railt\Reflection\Contracts\Document;

/**
 * Class ExtendTestCase
 */
class ExtendTestCase extends AbstractReflectionTestCase
{
    /**
     * @return array
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \Railt\Parser\Exceptions\CompilerException
     * @throws \Railt\Parser\Exceptions\UnexpectedTokenException
     * @throws \Railt\Parser\Exceptions\UnrecognizedTokenException
     */
    public function provider(): array
    {
        $schema = <<<GraphQL
type Test {
    id: String
    createdAt: DateTime!
}

extend type Test @deprecated(reason: "Test") {
    id(arg: String): ID
    updatedAt: DateTime
}
GraphQL;

        return $this->dataProviderDocuments($schema);
    }

    /**
     * @dataProvider provider
     *
     * @param Document|DocumentBuilder $document
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testType(Document $document): void
    {
        /** @var ObjectDefinition $type */
        $type = $document->getDefinition('Test');

        static::assertNotNull($type);
        static::assertNotNull($type->getField('id'));

        static::assertEquals(3, $type->getNumberOfFields());
        static::assertCount(3, $type->getFields());
    }

    /**
     * @dataProvider provider
     *
     * @param Document|DocumentBuilder $document
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testTypeFields(Document $document): void
    {
        /** @var ObjectDefinition $type */
        $type = $document->getDefinition('Test');

        static::assertNotNull($type);

        static::assertTrue($type->hasField('id'));
        static::assertTrue($type->hasField('createdAt'));
        static::assertTrue($type->hasField('updatedAt'));
        static::assertFalse($type->hasField('deprecated'));
    }

    /**
     * @dataProvider provider
     *
     * @param Document|DocumentBuilder $document
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testTypeArguments(Document $document): void
    {
        /** @var ObjectDefinition $type */
        $type = $document->getDefinition('Test');
        static::assertNotNull($type);

        $field = $type->getField('id');
        static::assertNotNull($field);

        static::assertTrue($field->hasArgument('arg'));
        static::assertNotNull($field->getArgument('arg'));
        static::assertCount(1, $field->getArguments());
    }
}
