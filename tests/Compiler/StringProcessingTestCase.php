<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Compiler;

use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Contracts\Dependent\FieldDefinition;
use Railt\Reflection\Contracts\Definitions\ObjectDefinition;
use Railt\Reflection\Contracts\Definitions\DirectiveDefinition;
use Railt\Reflection\Contracts\Invocations\DirectiveInvocation;

/**
 * Class StringProcessingTestCase.
 */
class StringProcessingTestCase extends AbstractCompilerTestCase
{
    /**
     * @return array
     * @throws \Railt\Compiler\Exceptions\CompilerException
     * @throws \Railt\Compiler\Exceptions\UnexpectedTokenException
     * @throws \Railt\Compiler\Exceptions\UnrecognizedTokenException
     */
    public function provider(): array
    {
        $schema = <<<'GraphQL'
directive @test(text: String = "Lol\\Troll\nexample\u00B6") on OBJECT
        
type A @test(text: "Lol\\Troll\nexample\u00B6"){
    field(arg: String = "Lol\\Troll\nexample\u00B6"): String
}
GraphQL;

        return $this->dataProviderDocuments($schema);
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testDirectiveArgumentDefault(Document $document): void
    {
        /** @var DirectiveDefinition $directive */
        $directive = $document->getTypeDefinition('test');
        /** @var string $text */
        $text = $directive->getArgument('text')->getDefaultValue();

        static::assertInternalType('string', $text);
        static::assertSame('Lol\\Troll' . "\n" . 'example¶', $text);
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testDirectiveArgumentValue(Document $document): void
    {
        /** @var ObjectDefinition $type */
        $type = $document->getTypeDefinition('A');
        /** @var DirectiveInvocation $directive */
        $directive = $type->getDirective('test');
        /** @var string $text */
        $text = $directive->getPassedArgument('text')->getPassedValue();

        static::assertInternalType('string', $text);
        static::assertSame('Lol\\Troll' . "\n" . 'example¶', $text);
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     * @throws \PHPUnit\Framework\Exception
     */
    public function testFieldArgumentValue(Document $document): void
    {
        /** @var ObjectDefinition $type */
        $type = $document->getTypeDefinition('A');
        /** @var FieldDefinition $field */
        $field = $type->getField('field');
        /** @var string $text */
        $text = $field->getArgument('arg')->getDefaultValue();

        static::assertInternalType('string', $text);
        static::assertSame('Lol\\Troll' . "\n" . 'example¶', $text);
    }
}
