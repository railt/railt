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
use Railt\Compiler\Reflection\Contracts\Dependent\ArgumentDefinition;
use Railt\Compiler\Reflection\Contracts\Dependent\FieldDefinition;
use Railt\Compiler\Reflection\Contracts\Document;
use Railt\Compiler\Exceptions\TypeConflictException;

/**
 * Class ArgumentDefaultsTestCase
 */
class ArgumentDefaultsTestCase extends AbstractCompilerTestCase
{
    private const ARGUMENT_BODY = 'type A { field(argument: %s): String }';

    /**
     * @return array
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \Railt\Compiler\Exceptions\CompilerException
     * @throws \Railt\Compiler\Exceptions\UnexpectedTokenException
     * @throws \Railt\Compiler\Exceptions\UnrecognizedTokenException
     */
    public function positiveProvider(): array
    {
        $schemas = [
            'String = null',
            '[String] = null',
            '[String!] = null',
            '[String]! = [null]',
            '[String!]! = [1,2,3]',
            '[String!] = [1,2,3]',
        ];

        $result = [];

        foreach ($schemas as $schema) {
            $result[] = [\sprintf(self::ARGUMENT_BODY, $schema)];
        }

        return $result;
    }

    /**
     * @return array
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \Railt\Compiler\Exceptions\CompilerException
     * @throws \Railt\Compiler\Exceptions\UnexpectedTokenException
     * @throws \Railt\Compiler\Exceptions\UnrecognizedTokenException
     */
    public function negativeProvider(): array
    {
        $schemas = [
            'String! = null',
            'String = []',
            '[String]! = null',
            '[String!] = [1,null,3]',
        ];

        $result = [];

        foreach ($schemas as $schema) {
            $result[] = [\sprintf(self::ARGUMENT_BODY, $schema)];
        }

        return $result;
    }

    /**
     * @dataProvider positiveProvider
     *
     * @param string $schema
     * @return void
     * @throws \League\Flysystem\FileNotFoundException
     * @throws \LogicException
     */
    public function testAllowedArgumentDefaultValue(string $schema): void
    {
        foreach ($this->getDocuments($schema) as $document) {
            /** @var ObjectDefinition $type */
            $type = $document->getDefinition('A');
            static::assertNotNull($type, 'Type "A" not found');

            /** @var FieldDefinition $field */
            $field = $type->getField('field');
            static::assertNotNull($field, 'Field "field" not found');

            /** @var ArgumentDefinition $argument */
            $argument = $field->getArgument('argument');
            static::assertNotNull($argument, 'Argument "argument" not found');
        }
    }

    /**
     * @dataProvider negativeProvider
     *
     * @param string $schema
     * @return void
     * @throws \League\Flysystem\FileNotFoundException
     * @throws \LogicException
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testInvalidArgumentDefaultValue(string $schema): void
    {
        /** @var \Generator $documents */
        $documents = $this->getDocuments($schema);

        while ($documents->valid()) {
            $throws = false;

            /** @var Document $document */
            $document = $documents->current();

            try {
                /** @var ArgumentDefinition $arg */
                $arg = $document->getDefinition('A')
                    ->getField('field')
                    ->getArgument('argument')
                    ->getDefaultValue();
            } catch (TypeConflictException $error) {
                $throws = true;
            }

            try {
                $documents->next();
            } catch (TypeConflictException $error) {
                $throws = true;
            }

            static::assertTrue($throws,'The schema is valid but Exception required:' . "\n    " . $schema);
        }
    }
}
