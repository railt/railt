<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\SDL\Language\Directives;

use Railt\Component\SDL\Contracts\Definitions\ObjectDefinition;
use Railt\Component\SDL\Contracts\Document;
use Railt\Tests\SDL\Language\AbstractLanguageTestCase;

/**
 * Class DeprecationTestCase
 */
class DeprecationTestCase extends AbstractLanguageTestCase
{
    /**
     * @return array
     * @throws \Exception
     */
    public function provider(): array
    {
        $schema = <<<'GraphQL'
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
        static::assertTrue($document->getTypeDefinition('DeprecatedWithMessage')->isDeprecated());
        static::assertTrue($document->getTypeDefinition('DeprecatedWithoutMessage')->isDeprecated());
        static::assertFalse($document->getTypeDefinition('WithoutDeprecation')->isDeprecated());
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
        $type = $document->getTypeDefinition('WithoutDeprecation');

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
        $type = $document->getTypeDefinition('DeprecatedWithMessage');

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
        $type = $document->getTypeDefinition('DeprecatedWithoutMessage');

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
        foreach ($document->getTypeDefinitions() as $type) {
            if ($type->hasDirective('deprecated')) {
                static::assertTrue($type->isDeprecated());

                $invocation = $type->getDirective('deprecated');
                $directive = $invocation->getTypeDefinition();

                static::assertSame('Directive', $invocation->getTypeDefinition()->getTypeName());

                static::assertSame('deprecated', $invocation->getName());
                static::assertSame('', $invocation->getDescription());
                static::assertSame('Object', $invocation->getParent()->getTypeName());

                if ($invocation->hasPassedArgument('reason')) {
                    static::assertSame($type->getDeprecationReason(),
                        $invocation->getPassedArgument('reason'));

                    static::assertSame('String',
                        $directive->getArgument('reason')->getTypeDefinition()->getName());
                } else {
                    static::assertSame($type->getDeprecationReason(),
                        $invocation->getTypeDefinition()->getArgument('reason')->getDefaultValue());
                }
            } else {
                static::assertFalse($type->isDeprecated(), $type->getName() . ' is deprecated but no');
            }
        }
    }
}
