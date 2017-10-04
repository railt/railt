<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Reflection;

use Railt\Reflection\Contracts\Types\ArgumentType;
use Railt\Reflection\Contracts\Types\FieldType;
use Railt\Reflection\Contracts\Types\ObjectType;
use Railt\Reflection\Exceptions\TypeConflictException;

/**
 * Class ArgumentDefaultsTestCase
 */
class ArgumentDefaultsTestCase extends AbstractReflectionTestCase
{
    private const ARGUMENT_BODY = 'type A { field(argument: %s): String }';

    /**
     * @return array
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \Railt\Parser\Exceptions\CompilerException
     * @throws \Railt\Parser\Exceptions\UnexpectedTokenException
     * @throws \Railt\Parser\Exceptions\UnrecognizedTokenException
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
     * @throws \Railt\Parser\Exceptions\CompilerException
     * @throws \Railt\Parser\Exceptions\UnexpectedTokenException
     * @throws \Railt\Parser\Exceptions\UnrecognizedTokenException
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
     * @throws \Railt\Parser\Exceptions\CompilerException
     * @throws \Railt\Parser\Exceptions\UnexpectedTokenException
     * @throws \Railt\Parser\Exceptions\UnrecognizedTokenException
     */
    public function testAllowedArgumentDefaultValue(string $schema): void
    {
        foreach ($this->getDocuments($schema) as $document) {
            /** @var ObjectType $type */
            $type = $document->getType('A');
            static::assertNotNull($type);

            /** @var FieldType $field */
            $field = $type->getField('field');
            static::assertNotNull($field);

            /** @var ArgumentType $argument */
            $argument = $field->getArgument('argument');
            static::assertNotNull($argument);
        }
    }

    /**
     * @dataProvider negativeProvider
     *
     * @param string $schema
     * @return void
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Parser\Exceptions\CompilerException
     * @throws \Railt\Parser\Exceptions\UnexpectedTokenException
     * @throws \Railt\Parser\Exceptions\UnrecognizedTokenException
     */
    public function testInvalidArgumentDefaultValue(string $schema): void
    {
        /** @var \Generator $documents */
        $documents = $this->getDocuments($schema);

        while ($documents->valid()) {
            $throws = false;

            $document = $documents->current();

            try {
                /** @var ArgumentType $arg */
                $arg = $document->getType('A')
                    ->getField('field')
                    ->getArgument('argument')
                    ->getDefaultValue();
            } catch (TypeConflictException $error) {
                $throws = true;
            }

            try {
                $documents->next();
            } catch (TypeConflictException $error) {
                echo '+Error: ' . $error->getMessage() . "\n";
                $throws = true;
            }

            static::assertTrue($throws,'The schema is valid but Exception required:' . "\n    " . $schema);
        }
    }
}
