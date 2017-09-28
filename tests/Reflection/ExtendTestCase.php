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
use Railt\Reflection\Contracts\Types\ObjectType;

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
type Some {
    id: ID
}

extend type Some 
    @deprecated(reason: "Because")
{
    id(value: Any): ID!
    createdAt: DateTime!
    updatedAt: DateTime
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
    public function testSomeType(Document $document): void
    {
        /** @var ObjectType $type */
        $type = $document->getType('Some');
        static::assertNotNull($type);
    }
}
