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
use Railt\Reflection\Contracts\Document;

/**
 * Class DocumentTestCase
 * @package Railt\Tests\Reflection
 */
class DocumentTestCase extends AbstractReflectionTestCase
{
    private const DOCUMENT_ID_PATTERN = '[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}';

    /**
     * @return array
     * @throws \Railt\Parser\Exceptions\CompilerException
     * @throws \Railt\Parser\Exceptions\UnexpectedTokenException
     * @throws \Railt\Parser\Exceptions\UnrecognizedTokenException
     */
    public function provider(): array
    {
        $data = [
            'schema { query: Query }type Query {}',
            'schema{query:Query,mutation:Mutation,subscription:Subscription}' .
            'type Query{}type Mutation{}type Subscription{}',
            'schema{query:Query}type Query {id:ID!,some:[String]}',
        ];

        $result = [];

        foreach ($data as $body) {
            $result[] = [$this->getDocument($body)];
        }

        return $result;
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @throws \PHPUnit\Framework\Exception
     */
    public function testIsSuccessfullyCompiled(Document $document)
    {
        static::assertInstanceOf(Document::class, $document);
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @throws \PHPUnit\Framework\Exception
     */
    public function testUniqueIdentifier(Document $document)
    {
        $pattern = '/^' . self::DOCUMENT_ID_PATTERN . '$/u';

        static::assertTrue((bool)\preg_match($pattern, $document->getUniqueId()));
        static::assertEquals($document->getUniqueId(), $document->getUniqueId());
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     */
    public function testDocumentName(Document $document)
    {
        static::assertEquals(DocumentBuilder::VIRTUAL_FILE_NAME, $document->getName());
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     */
    public function testDocumentHasTypes(Document $document)
    {
        static::assertTrue($document->getNumberOfTypes() > 0);
        static::assertCount($document->getNumberOfTypes(), $document->getTypes());
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testDocumentHasQueryType(Document $document): void
    {
        static::assertNotNull($document->getType('Query'));
        static::assertTrue($document->hasType('Query'));
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     */
    public function testDocumentTypeName(Document $document): void
    {
        $name = DocumentBuilder::VIRTUAL_FILE_NAME;
        $pattern = \sprintf('/^%s<%s>$/', $name, self::DOCUMENT_ID_PATTERN);

        static::assertSame(1, \preg_match($pattern, $document->getTypeName()));
    }
}
