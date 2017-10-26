<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Compiler;

use Railt\Compiler\Reflection\Contracts\Definitions\ObjectDefinition;
use Railt\Compiler\Reflection\Contracts\Document;

/**
 * Class DeprecationTestCase
 */
class DeprecationTestCase extends AbstractCompilerTestCase
{
    /**
     * @return array
     * @throws \Railt\Compiler\Exceptions\CompilerException
     * @throws \Railt\Compiler\Exceptions\UnexpectedTokenException
     * @throws \Railt\Compiler\Exceptions\UnrecognizedTokenException
     */
    public function provider(): array
    {
        $schema = <<<GraphQL
type DeprecatedWithMessage @deprecated(reason: "Message") {}

type DeprecatedWithoutMessage @deprecated() {}

type WithoutDeprecation {}
GraphQL;

        return $this->dataProviderDocuments($schema);
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testDeprecationExists(Document $document): void
    {
        static::assertTrue($document->getDefinition('DeprecatedWithMessage')->isDeprecated());
        static::assertTrue($document->getDefinition('DeprecatedWithoutMessage')->isDeprecated());
        static::assertFalse($document->getDefinition('WithoutDeprecation')->isDeprecated());
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testEmptyMessageExists(Document $document): void
    {
        /** @var ObjectDefinition $type */
        $type = $document->getDefinition('WithoutDeprecation');

        static::assertSame('', $type->getDeprecationReason());
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testDefinedMessageExists(Document $document): void
    {
        /** @var ObjectDefinition $type */
        $type = $document->getDefinition('DeprecatedWithMessage');

        static::assertSame('Message', $type->getDeprecationReason());
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testWithoutReasonDefinition(Document $document): void
    {
        /** @var ObjectDefinition $type */
        $type = $document->getDefinition('DeprecatedWithoutMessage');

        static::assertSame('No longer supported', $type->getDeprecationReason());
    }

    /**
     * @dataProvider provider
     *
     * @param Document $document
     * @return void
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testReasonSameWithDirective(Document $document): void
    {
        /** @var ObjectDefinition $type */
        foreach ($document->getTypes() as $type) {
            if ($type->hasDirective('deprecated')) {
                static::assertTrue($type->isDeprecated());

                $invocation = $type->getDirective('deprecated');
                $directive = $invocation->getDefinition();

                static::assertSame('Directive', $invocation->getDefinition()->getTypeName());

                static::assertSame('deprecated', $invocation->getName());
                static::assertSame('', $invocation->getDescription());
                static::assertSame('Object', $invocation->getParent()->getTypeName());

                if ($invocation->hasPassedArgument('reason')) {
                    static::assertSame($type->getDeprecationReason(),
                        $invocation->getPassedArgument('reason')->getPassedValue());
                    static::assertSame('String',
                        $directive->getArgument('reason')->getType()->getName());
                } else {
                    static::assertSame($type->getDeprecationReason(),
                        $invocation->getDefinition()->getArgument('reason')->getDefaultValue());
                }
            } else {
                static::assertFalse($type->isDeprecated(), $type->getName() . ' is deprecated but no');
            }
        }
    }
}
