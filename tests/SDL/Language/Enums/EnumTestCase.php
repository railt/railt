<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\SDL\Language\Enums;

use Railt\Reflection\Contracts\Definitions\EnumDefinition;
use Railt\Reflection\Contracts\Document;
use Railt\Tests\SDL\Language\AbstractLanguageTestCase;

/**
 * Class EnumTestCase
 */
class EnumTestCase extends AbstractLanguageTestCase
{
    /**
     * @return array
     * @throws \Exception
     */
    public function provider(): array
    {
        $schema = <<<'GraphQL'
"""
# This is an example ENUM
"""
enum Colour {
    Red
    Green
    Blue
}

type A {
    some(any: Colour = Red): String
}
GraphQL;

        return $this->dataProviderDocuments($schema);
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     */
    public function testEnumName(Document $document): void
    {
        /** @var EnumDefinition $enum */
        $enum = $document->getTypeDefinition('Colour');
        static::assertNotNull($enum);

        static::assertSame('Colour', $enum->getName());
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     */
    public function testEnumDescription(Document $document): void
    {
        /** @var EnumDefinition $enum */
        $enum = $document->getTypeDefinition('Colour');
        static::assertNotNull($enum);

        static::assertSame('This is an example ENUM', $enum->getDescription());
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     */
    public function testEnumValues(Document $document): void
    {
        /** @var EnumDefinition $enum */
        $enum = $document->getTypeDefinition('Colour');
        static::assertNotNull($enum);

        static::assertNotNull($enum->getValue('Red'));
        static::assertNotNull($enum->getValue('Green'));
        static::assertNotNull($enum->getValue('Blue'));
        static::assertNull($enum->getValue('Alpha'));
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testEnumValuesCount(Document $document): void
    {
        /** @var EnumDefinition $enum */
        $enum = $document->getTypeDefinition('Colour');
        static::assertNotNull($enum);

        static::assertCount(3, $enum->getValues());
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testEnumValuesExists(Document $document): void
    {
        /** @var EnumDefinition $enum */
        $enum = $document->getTypeDefinition('Colour');
        static::assertNotNull($enum);

        static::assertTrue($enum->hasValue('Red'));
        static::assertTrue($enum->hasValue('Green'));
        static::assertTrue($enum->hasValue('Blue'));
        static::assertFalse($enum->hasValue('Alpha'));
    }
}
