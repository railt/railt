<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Reflection;
use Railt\Reflection\Compiler\CompilerInterface;
use Railt\Reflection\Exceptions\TypeConflictException;
use Railt\Reflection\Exceptions\TypeRedefinitionException;
use Railt\Support\Filesystem\File;

/**
 * Class InheritanceTestCase
 */
class InheritanceTestCase extends AbstractReflectionTestCase
{
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
            // Container
            'type A { id: ID } extend type A { id: ID }',
            'type A { id: ID } extend type A { id: ID! }',
            'type A { id: ID! } extend type A { id: ID! }',
            'type A { id: [ID] } extend type A { id: [ID] }',
            'type A { id: [ID] } extend type A { id: [ID!] }',
            'type A { id: [ID] } extend type A { id: [ID]! }',
            'type A { id: [ID] } extend type A { id: [ID!]! }',
            'type A { id: [ID!] } extend type A { id: [ID!] }',
            'type A { id: [ID!] } extend type A { id: [ID!]! }',
            'type A { id: [ID]! } extend type A { id: [ID]! }',
            'type A { id: [ID]! } extend type A { id: [ID!]! }',
            'type A { id: [ID!]! } extend type A { id: [ID!]! }',

            // Scalars
            'type A { id: Any } extend type A { id: Any }',
            'type A { id: Any } extend type A { id: Boolean }',
            'type A { id: Any } extend type A { id: DateTime }',
            'type A { id: Any } extend type A { id: Float }',
            'type A { id: Any } extend type A { id: ID }',
            'type A { id: Any } extend type A { id: Int }',
            'type A { id: Any } extend type A { id: String }',
            'type A { id: Boolean } extend type A { id: Boolean }',
            'type A { id: DateTime } extend type A { id: DateTime }',
            'type A { id: Float } extend type A { id: Float }',
            'type A { id: Float } extend type A { id: Int }',
            'type A { id: ID } extend type A { id: ID }',
            'type A { id: Int } extend type A { id: Int }',
            'type A { id: String } extend type A { id: DateTime }',
            'type A { id: String } extend type A { id: ID }',
            'type A { id: String } extend type A { id: String }',

            // Interfaces
            'interface I {} type A implements I { id: I } extend type A { id: I }',
            'interface I {} type A implements I { id: I } extend type A { id: A }',

            // Unions
            'union U = ID | String type A { id: U } extend type A { id: U }',
            'union U = ID | String type A { id: U } extend type A { id: ID }',
            'union U = ID | String type A { id: U } extend type A { id: String }',

            // Objects
            'type A { id: A } extend type A { id: A }',
        ];

        $result = [];

        foreach ($schemas as $schema) {
            $result[] = [$schema];
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
            // Container
            'type A { id: ID } extend type A { id: [ID] }',
            'type A { id: ID } extend type A { id: [ID!] }',
            'type A { id: ID } extend type A { id: [ID]! }',
            'type A { id: ID } extend type A { id: [ID!]! }',
            'type A { id: ID! } extend type A { id: ID }',
            'type A { id: ID! } extend type A { id: [ID] }',
            'type A { id: ID! } extend type A { id: [ID!] }',
            'type A { id: ID! } extend type A { id: [ID]! }',
            'type A { id: ID! } extend type A { id: [ID!]! }',
            'type A { id: [ID] } extend type A { id: ID }',
            'type A { id: [ID] } extend type A { id: ID! }',
            'type A { id: [ID!] } extend type A { id: ID }',
            'type A { id: [ID!] } extend type A { id: ID! }',
            'type A { id: [ID!] } extend type A { id: [ID] }',
            'type A { id: [ID!] } extend type A { id: [ID]! }',
            'type A { id: [ID]! } extend type A { id: ID }',
            'type A { id: [ID]! } extend type A { id: ID! }',
            'type A { id: [ID]! } extend type A { id: [ID!] }',
            'type A { id: [ID!]! } extend type A { id: ID }',
            'type A { id: [ID!]! } extend type A { id: ID! }',

            // Scalars
            'type A { id: Boolean } extend type A { id: Any }',
            'type A { id: Boolean } extend type A { id: DateTime }',
            'type A { id: Boolean } extend type A { id: Float }',
            'type A { id: Boolean } extend type A { id: ID }',
            'type A { id: Boolean } extend type A { id: Int }',
            'type A { id: Boolean } extend type A { id: String }',
            'type A { id: DateTime } extend type A { id: Any }',
            'type A { id: DateTime } extend type A { id: Boolean }',
            'type A { id: DateTime } extend type A { id: Float }',
            'type A { id: DateTime } extend type A { id: ID }',
            'type A { id: DateTime } extend type A { id: Int }',
            'type A { id: DateTime } extend type A { id: String }',
            'type A { id: Float } extend type A { id: Any }',
            'type A { id: Float } extend type A { id: Boolean }',
            'type A { id: Float } extend type A { id: DateTime }',
            'type A { id: Float } extend type A { id: ID }',
            'type A { id: Float } extend type A { id: String }',
            'type A { id: ID } extend type A { id: Any }',
            'type A { id: ID } extend type A { id: Boolean }',
            'type A { id: ID } extend type A { id: DateTime }',
            'type A { id: ID } extend type A { id: Float }',
            'type A { id: ID } extend type A { id: Int }',
            'type A { id: ID } extend type A { id: String }',
            'type A { id: Int } extend type A { id: Any }',
            'type A { id: Int } extend type A { id: Boolean }',
            'type A { id: Int } extend type A { id: DateTime }',
            'type A { id: Int } extend type A { id: Float }',
            'type A { id: Int } extend type A { id: ID }',
            'type A { id: Int } extend type A { id: String }',
            'type A { id: String } extend type A { id: Any }',
            'type A { id: String } extend type A { id: Boolean }',
            'type A { id: String } extend type A { id: Float }',
            'type A { id: String } extend type A { id: Int }',

            // Interfaces
            'interface I {} type A { id: I } extend type A { id: A }', // Not implements
            'interface I {} type A { id: I } extend type A { id: ID }', // Overwrite by other type
            'interface I {} interface J {} type A { id: I } extend type A { id: J }', // Overwrite by other interface

            // Unions
            'union U = String union U2 = ID type A { id: U } extend type A { id: U2 }',
            'union U = ID | String type A { id: U } extend type A { id: Int }',

            // Incompatible types
            'type Object {}         type A { id: Object }    extend type A { id: ID }',
            'interface Interface {} type A { id: Interface } extend type A { id: ID }',
            'union Union = String   type A { id: Union }     extend type A { id: ID }',
            'enum Enum { A }        type A { id: Enum }      extend type A { id: ID }',
            'input Input { id: ID } type A { id: Input }     extend type A { id: ID }',
            'type A {} type B {}    type C { id: A }         extend type C { id: B }',
            'interface I {} type A implements I {} type B implements I {} type C { id: A } extend type C { id: B }',
        ];

        $result = [];

        foreach ($schemas as $schema) {
            $result[] = [$schema];
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
    public function testExtendPositiveInheritance(string $schema): void
    {
        /** @var CompilerInterface $compiler */
        foreach ($this->getCompilers() as $compiler) {
            $result = $compiler->compile(File::fromSources($schema));
            static::assertNotNull($result);
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
    public function testExtendNegativeInheritance(string $schema): void
    {
        /** @var CompilerInterface $compiler */
        foreach ($this->getCompilers() as $compiler) {
            try {
                $compiler->compile(File::fromSources($schema));
                static::assertTrue(false, 'Throws an exception required');
            } catch (TypeConflictException $error) {
                echo ' +' . $error->getMessage() . "\n";
                static::assertInstanceOf(TypeRedefinitionException::class, $error);
            }
        }
    }
}
