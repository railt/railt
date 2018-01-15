<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\SDL\Language\Inputs;

use Railt\Reflection\Contracts\Definitions\InputDefinition;
use Railt\Reflection\Contracts\Dependent\ArgumentDefinition;
use Railt\Reflection\Contracts\Document;
use Railt\Tests\SDL\Language\AbstractLanguageTestCase;

/**
 * Class InputTestCase
 */
class InputTestCase extends AbstractLanguageTestCase
{
    /**
     * @return array
     * @throws \Exception
     */
    public function provider(): array
    {
        $schema = <<<'GraphQL'
"""
 # This an Input type example
"""
input Test { 
    id: ID! = "Hell OR World"
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
    public function testInputName(Document $document): void
    {
        /** @var InputDefinition $input */
        $input = $document->getTypeDefinition('Test');

        static::assertNotNull($input);
        static::assertSame('Test', $input->getName());
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     */
    public function testInputType(Document $document): void
    {
        /** @var InputDefinition $input */
        $input = $document->getTypeDefinition('Test');
        static::assertNotNull($input);

        static::assertSame('Input', $input->getTypeName());
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     */
    public function testInputDescription(Document $document): void
    {
        /** @var InputDefinition $input */
        $input = $document->getTypeDefinition('Test');
        static::assertNotNull($input);

        $description = 'This an Input type example';

        static::assertSame($description, $input->getDescription());
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testInputDeprecation(Document $document): void
    {
        /** @var InputDefinition $input */
        $input = $document->getTypeDefinition('Test');
        static::assertNotNull($input);

        static::assertFalse($input->isDeprecated());
        static::assertSame('', $input->getDeprecationReason());
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testInputIdField(Document $document): void
    {
        /** @var InputDefinition $input */
        $input = $document->getTypeDefinition('Test');
        static::assertNotNull($input);

        /** @var ArgumentDefinition $id */
        $id = $input->getArgument('id');
        static::assertNotNull($id);

        static::assertSame('id', $id->getName());
        static::assertSame('ID', $id->getTypeDefinition()->getName());
        static::assertTrue($id->isNonNull());
        static::assertSame('Hell OR World', $id->getDefaultValue());
    }
}
