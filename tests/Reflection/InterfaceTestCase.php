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
use Railt\Reflection\Contracts\Types\InterfaceType;
use Railt\Reflection\Contracts\Types\ObjectType;

/**
 * Class InterfaceTestCase
 */
class InterfaceTestCase extends AbstractReflectionTestCase
{
    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     */
    public function testObjectHasInterface(Document $document): void
    {
        /** @var ObjectType $object */
        $object = $document->getType('Object');

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
        /** @var ObjectType $object */
        $object = $document->getType('Object');

        static::assertNotNull($object);

        $interface = $object->getInterface('Test');
        $this->processTestInterface($interface);
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
        /** @var InterfaceType $interface */
        $interface = $document->getType('Test');
        $this->processTestInterface($interface);
    }

    /**
     * @param InterfaceType $type
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     */
    private function processTestInterface(?InterfaceType $type): void
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
     * @return array
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \Railt\Parser\Exceptions\CompilerException
     * @throws \Railt\Parser\Exceptions\UnexpectedTokenException
     * @throws \Railt\Parser\Exceptions\UnrecognizedTokenException
     */
    public function provider(): array
    {
        $schema = 'type Object implements Test { id: ID! }' .
            '"""' . "\n" .
            '# This is a test interface' . "\n" .
            '"""' . "\n" .
            'interface Test @deprecated(reason: "Because") { id: ID! }';

        return [
            [$this->getDocument($schema)],
            [$this->getCachedDocument($schema)],
        ];
    }
}
