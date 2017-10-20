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
use Railt\Reflection\Compiler\CompilerInterface;
use Railt\Reflection\Contracts\Document;

/**
 * Class DocumentTestCase
 * @package Railt\Tests\Reflection
 */
class DocumentTestCase extends AbstractReflectionTestCase
{
    private const DOCUMENT_ID_PATTERN = '[0-9a-f]{32}';

    /**
     * @return array
     * @throws \Psr\Cache\InvalidArgumentException
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
            foreach ($this->getDocuments($body) as $document) {
                $result[] = [$document];
            }
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
        static::assertNotNull($document->getName());
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
        static::assertTrue($document->getNumberOfDefinitions() > 0);
        static::assertCount($document->getNumberOfDefinitions(), $document->getTypes());
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
        static::assertNotNull($document->getDefinition('Query'));
        static::assertTrue($document->hasDefinition('Query'));
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     */
    public function testDocumentTypeName(Document $document): void
    {
        static::assertSame('Document', $document->getTypeName());
    }

    /**
     * @return void
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \Railt\Parser\Exceptions\CompilerException
     * @throws \Railt\Parser\Exceptions\UnexpectedTokenException
     * @throws \Railt\Parser\Exceptions\UnrecognizedTokenException
     */
    public function testStdlibDocument(): void
    {
        /** @var CompilerInterface $compiler */
        foreach ($this->getCompilers() as $compiler) {

            $string = $compiler->get('String');
            $stdlib = $string->getDocument();

            static::assertSame('GraphQL Standard Library', $stdlib->getName());
            static::assertSame('Document', $stdlib->getTypeName());
        }
    }
}
