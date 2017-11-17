<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Compiler;

use Railt\Compiler\Compiler;
use Railt\Reflection\Filesystem\File;
use Railt\Compiler\Exceptions\TypeConflictException;
use Railt\Reflection\Contracts\Dependent\FieldDefinition;
use Railt\Reflection\Contracts\Definitions\ObjectDefinition;
use Railt\Reflection\Contracts\Dependent\ArgumentDefinition;

/**
 * Class ArgumentDefaultsTestCase.
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
    public function provider(): array
    {
        return \array_merge($this->positiveProvider(), $this->negativeProvider());
    }

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
            '[Int!]! = [1,2,3]',
            '[String!] = ["1","2","3"]',
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
            // NonNull init by NULL
            'String! = null',
            // NonList init by List
            'String = []',
            // NonNullList init by NULL
            '[String]! = null',
            // ListOfNonNull init by List with NULL
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
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \Throwable
     */
    public function testAllowedArgumentDefaultValue(string $schema): void
    {
        try {
            foreach ($this->getDocuments($schema) as $document) {
                /** @var ObjectDefinition $type */
                $type = $document->getTypeDefinition('A');
                static::assertNotNull($type, 'Type "A" not found');

                /** @var FieldDefinition $field */
                $field = $type->getField('field');
                static::assertNotNull($field, 'Field "field" not found');

                /** @var ArgumentDefinition $argument */
                $argument = $field->getArgument('argument');
                static::assertNotNull($argument, 'Argument "argument" not found');
            }
        } catch (\Throwable $e) {
            static::assertFalse(true,
                'This code is valid: ' . "\n> " . $schema . "\n" .
                'But exception thrown:' . "\n> " . $e->getMessage()
            );
            throw $e;
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
     * @throws \Railt\Compiler\Exceptions\CompilerException
     * @throws \Railt\Compiler\Exceptions\TypeNotFoundException
     * @throws \Railt\Compiler\Exceptions\UnexpectedTokenException
     * @throws \Railt\Compiler\Exceptions\UnrecognizedTokenException
     */
    public function testInvalidArgumentDefaultValue(string $schema): void
    {
        $compilers = $this->getCompilers();

        /** @var Compiler $compiler */
        foreach ($compilers as $compiler) {
            try {
                $compiler->compile(File::fromSources($schema));
                static::assertFalse(true,
                    'Default value must throw an exception: ' . "\n" . $schema);
            } catch (TypeConflictException $error) {
                static::assertTrue(true);
            }
        }
    }
}
