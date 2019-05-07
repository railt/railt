<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\SDL\Language;

use Railt\SDL\Contracts\Document;
use Railt\SDL\Schema\CompilerInterface;

/**
 * Class DocumentTestCase
 */
class DocumentTestCase extends AbstractLanguageTestCase
{
    private const DOCUMENT_ID_PATTERN = '[0-9a-f]{32}';

    /**
     * @return array
     * @throws \Exception
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
    public function testIsSuccessfullyCompiled(Document $document): void
    {
        static::assertInstanceOf(Document::class, $document);
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @throws \PHPUnit\Framework\Exception
     */
    public function testUniqueIdentifier(Document $document): void
    {
        $pattern = '/^' . self::DOCUMENT_ID_PATTERN . '$/u';

        static::assertTrue((bool)\preg_match($pattern, $document->getUniqueId()));
        static::assertSame($document->getUniqueId(), $document->getUniqueId());
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testDocumentName(Document $document): void
    {
        static::assertNotNull($document->getName());
        static::assertSame('php://input', $document->getName());
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     */
    public function testDocumentHasTypes(Document $document): void
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
     * @throws \Exception
     */
    public function testStdlibDocument(): void
    {
        /** @var CompilerInterface $compiler */
        foreach ($this->getCompilers() as $compiler) {
            $string = $compiler->getDictionary()->get('String');
            $stdlib = $string->getDocument();

            static::assertSame('GraphQL Standard Library', $stdlib->getName());
        }
    }
}
