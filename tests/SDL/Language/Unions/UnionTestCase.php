<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\SDL\Language\Unions;

use Railt\Component\SDL\Contracts\Definitions\UnionDefinition;
use Railt\Component\SDL\Contracts\Document;
use Railt\Tests\SDL\Language\AbstractLanguageTestCase;

/**
 * Class UnionTestCase
 */
class UnionTestCase extends AbstractLanguageTestCase
{
    /**
     * @return array
     * @throws \Exception
     */
    public function provider(): array
    {
        $schema = '"""This is an example union"""' .
            'union Person = | User | Bot ' .
            'type User {}' .
            'type Bot {}';

        return $this->dataProviderDocuments($schema);
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     */
    public function testUnionDefinition(Document $document): void
    {
        /** @var UnionDefinition $union */
        $union = $document->getTypeDefinition('Person');
        static::assertNotNull($union);

        static::assertSame('Person', $union->getName());
        static::assertSame('This is an example union', $union->getDescription());

        static::assertSame('Union', $union->getTypeName());

        static::assertFalse($union->isDeprecated());
        static::assertSame('', $union->getDeprecationReason());
        static::assertCount(0, $union->getDirectives());
        static::assertNull($union->getDirective('deprecated'));
        static::assertFalse($union->hasDirective('deprecated'));
        static::assertSame(0, $union->getNumberOfDirectives());
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testUnionRelations(Document $document): void
    {
        /** @var UnionDefinition $union */
        $union = $document->getTypeDefinition('Person');
        static::assertNotNull($union);

        static::assertSame(2, $union->getNumberOfTypes());
        static::assertNotNull($union->getType('User'));
        static::assertNotNull($union->getType('Bot'));
        static::assertNull($union->getType('user'));
        static::assertNull($union->getType('bot'));

        static::assertSame('User', $union->getType('User')->getName());
        static::assertSame('Object', $union->getType('User')->getTypeName());
        static::assertSame('Bot', $union->getType('Bot')->getName());
        static::assertSame('Object', $union->getType('Bot')->getTypeName());
    }
}
