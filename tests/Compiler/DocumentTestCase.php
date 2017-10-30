<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Compiler;

use Railt\Compiler\Reflection\CompilerInterface;
use Railt\Compiler\Reflection\Contracts\Document;

/**
 * Class DocumentTestCase
 * @package Railt\Tests\Compiler
 */
class DocumentTestCase extends AbstractCompilerTestCase
{
    private const DOCUMENT_ID_PATTERN = '[0-9a-f]{32}';

    /**
     * @return array
     * @throws \League\Flysystem\FileNotFoundException
     * @throws \LogicException
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
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testDocumentName(Document $document)
    {
        static::assertNotNull($document->getName());
        static::assertTrue((bool)\preg_match('/^Source\(.*?:\d+\)$/isu', $document->getName()));
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
        static::assertTrue($document->getNumberOfTypeDefinitions() > 0);
        static::assertCount($document->getNumberOfTypeDefinitions(), $document->getTypeDefinitions());
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
        static::assertNotNull($document->getTypeDefinition('Query'));
        static::assertTrue($document->hasTypeDefinition('Query'));
    }

    /**
     * @return void
     * @throws \League\Flysystem\FileNotFoundException
     * @throws \LogicException
     */
    public function testStdlibDocument(): void
    {
        /** @var CompilerInterface $compiler */
        foreach ($this->getCompilers() as $compiler) {

            $string = $compiler->get('String');
            $stdlib = $string->getDocument();

            static::assertSame('GraphQL Standard Library', $stdlib->getName());
        }
    }
}
