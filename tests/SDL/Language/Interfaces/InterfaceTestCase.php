<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\SDL\Language\Interfaces;

use Railt\Component\SDL\Contracts\Definitions\InterfaceDefinition;
use Railt\Component\SDL\Contracts\Definitions\ObjectDefinition;
use Railt\Component\SDL\Contracts\Document;
use Railt\Tests\SDL\Language\AbstractLanguageTestCase;

/**
 * Class InterfaceTestCase
 */
class InterfaceTestCase extends AbstractLanguageTestCase
{
    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testObjectHasInterface(Document $document): void
    {
        /** @var ObjectDefinition $object */
        $object = $document->getTypeDefinition('Object');

        static::assertNotNull($object);
        static::assertNotCount(0, $object->getInterfaces());
        static::assertCount(1, $object->getInterfaces());

        static::assertNotNull($object->getInterface('Test'));
        static::assertNull($object->getInterface('Test2'));

        static::assertTrue($object->hasInterface('Test'));
        static::assertFalse($object->hasInterface('Test2'));

        static::assertNotSame(0, $object->getNumberOfInterfaces());
        static::assertSame(1, $object->getNumberOfInterfaces());
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     */
    public function testGetInterfaceThroughObject(Document $document): void
    {
        /** @var ObjectDefinition $object */
        $object = $document->getTypeDefinition('Object');

        static::assertNotNull($object);

        $interface = $object->getInterface('Test');
        $this->processTestInterface($interface);
    }

    /**
     * @param InterfaceDefinition $type
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     */
    private function processTestInterface(?InterfaceDefinition $type): void
    {
        static::assertNotNull($type);

        static::assertSame('Test', $type->getName());
        static::assertSame('This is a test interface', $type->getDescription());
        static::assertSame('Interface', $type->getTypeName());

        static::assertTrue($type->isDeprecated());
        static::assertSame('Because', $type->getDeprecationReason());

        static::assertNotCount(0, $type->getDirectives());
        static::assertCount(1, $type->getDirectives());

        static::assertNotNull($type->getDirective('deprecated'));
        static::assertNull($type->getDirective('Deprecated'));

        static::assertNotNull($type->getField('id'));
        static::assertNull($type->getField('Id'));
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     */
    public function testGetInterfaceThroughDocument(Document $document): void
    {
        /** @var InterfaceDefinition $interface */
        $interface = $document->getTypeDefinition('Test');
        $this->processTestInterface($interface);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function provider(): array
    {
        $schema = <<<'GraphQL'
type Object implements Test {
    id: ID!
}

"""
# This is a test interface
"""
interface Test @deprecated(reason: "Because") { 
    id: ID!
}
GraphQL;

        return $this->dataProviderDocuments($schema);
    }
}
