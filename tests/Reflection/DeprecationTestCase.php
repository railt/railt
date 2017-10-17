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
use Railt\Reflection\Contracts\Types\Directive\DirectiveInvocation;
use Railt\Reflection\Contracts\Types\DirectiveType;
use Railt\Reflection\Contracts\Types\ObjectType;

/**
 * Class DeprecationTestCase
 */
class DeprecationTestCase extends AbstractReflectionTestCase
{
    /**
     * @return array
     * @throws \Railt\Parser\Exceptions\CompilerException
     * @throws \Railt\Parser\Exceptions\UnexpectedTokenException
     * @throws \Railt\Parser\Exceptions\UnrecognizedTokenException
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
        static::assertTrue($document->getType('DeprecatedWithMessage')->isDeprecated());
        static::assertTrue($document->getType('DeprecatedWithoutMessage')->isDeprecated());
        static::assertFalse($document->getType('WithoutDeprecation')->isDeprecated());
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
        /** @var ObjectType $type */
        $type = $document->getType('WithoutDeprecation');

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
        /** @var ObjectType $type */
        $type = $document->getType('DeprecatedWithMessage');

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
        /** @var ObjectType $type */
        $type = $document->getType('DeprecatedWithoutMessage');

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
        /** @var ObjectType $type */
        foreach ($document->getTypes() as $type) {
            if ($type->hasDirective('deprecated')) {
                static::assertTrue($type->isDeprecated());

                $invocation = $type->getDirective('deprecated');
                $directive = $invocation->getDirective();

                static::assertSame('Directive', $invocation->getDirective()->getTypeName());

                static::assertSame('deprecated', $invocation->getName());
                static::assertSame('', $invocation->getDescription());
                static::assertSame('Object', $invocation->getParent()->getTypeName());

                if ($invocation->hasArgument('reason')) {
                    static::assertSame($type->getDeprecationReason(),
                        $invocation->getArgument('reason')->getValue());
                    static::assertSame('String',
                        $directive->getArgument('reason')->getType()->getName());
                } else {
                    static::assertSame($type->getDeprecationReason(),
                        $invocation->getDirective()->getArgument('reason')->getDefaultValue());
                }
            } else {
                static::assertFalse($type->isDeprecated());
            }
        }
    }
}
