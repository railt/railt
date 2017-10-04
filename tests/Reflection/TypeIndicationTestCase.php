<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Reflection;

use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Contracts\Types\FieldType;
use Railt\Reflection\Contracts\Types\ObjectType;

/**
 * Class TypeIndicationTestCase
 */
class TypeIndicationTestCase extends AbstractReflectionTestCase
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
    a: ID
    b: ID!
    c: [ID]
    d: [ID!]
    e: [ID]!
    f: [ID!]!
}
GraphQL;

        return $this->dataProviderDocuments($schema);
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testNullableType(Document $document): void
    {
        /** @var ObjectType $type */
        $type = $document->getType('Test');
        static::assertNotNull($type);

        /** @var FieldType $a "ID" */
        $a = $type->getField('a');
        static::assertNotNull($a);
        static::assertFalse($a->isNonNull());
        static::assertFalse($a->isList());
        static::assertFalse($a->isListOfNonNulls());
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testNonNull(Document $document): void
    {
        /** @var ObjectType $type */
        $type = $document->getType('Test');
        static::assertNotNull($type);

        /** @var FieldType $b "ID!" */
        $b = $type->getField('b');
        static::assertNotNull($b);
        static::assertTrue($b->isNonNull());
        static::assertFalse($b->isList());
        static::assertFalse($b->isListOfNonNulls());
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testList(Document $document): void
    {
        /** @var ObjectType $type */
        $type = $document->getType('Test');
        static::assertNotNull($type);

        /** @var FieldType $c "[ID]" */
        $c = $type->getField('c');
        static::assertNotNull($c);
        static::assertFalse($c->isNonNull());
        static::assertTrue($c->isList());
        static::assertFalse($c->isListOfNonNulls());
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testListOfNonNulls(Document $document): void
    {
        /** @var ObjectType $type */
        $type = $document->getType('Test');
        static::assertNotNull($type);

        /** @var FieldType $d "[ID!]" */
        $d = $type->getField('d');
        static::assertNotNull($d);
        static::assertFalse($d->isNonNull());
        static::assertTrue($d->isList());
        static::assertTrue($d->isListOfNonNulls());
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testNonNullList(Document $document): void
    {
        /** @var ObjectType $type */
        $type = $document->getType('Test');
        static::assertNotNull($type);

        /** @var FieldType $e "[ID]!" */
        $e = $type->getField('e');
        static::assertNotNull($e);
        static::assertTrue($e->isNonNull());
        static::assertTrue($e->isList());
        static::assertFalse($e->isListOfNonNulls());
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testNonNullListOfNonNulls(Document $document): void
    {
        /** @var ObjectType $type */
        $type = $document->getType('Test');
        static::assertNotNull($type);

        /** @var FieldType $f "[ID!]!" */
        $f = $type->getField('f');
        static::assertNotNull($f);
        static::assertTrue($f->isNonNull());
        static::assertTrue($f->isList());
        static::assertTrue($f->isListOfNonNulls());
    }
}
