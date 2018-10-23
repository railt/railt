<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\SDL\Language;

use Railt\Io\File;
use Railt\SDL\Compiler;
use Railt\SDL\Exceptions\TypeConflictException;
use Railt\SDL\Schema\CompilerInterface;

/**
 * Class InheritanceTestCase
 */
class InheritanceTestCase extends AbstractLanguageTestCase
{
    /**
     * @var array
     */
    private $messages = [];

    /**
     * @return array
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \Railt\SDL\Exceptions\CompilerException
     * @throws \Railt\SDL\Exceptions\UnexpectedTokenException
     * @throws \Railt\SDL\Exceptions\UnrecognizedTokenException
     */
    public function provider(): array
    {
        return \array_merge($this->positiveProvider(), $this->negativeProvider());
    }

    /**
     * @return array
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \Railt\SDL\Exceptions\CompilerException
     * @throws \Railt\SDL\Exceptions\UnexpectedTokenException
     * @throws \Railt\SDL\Exceptions\UnrecognizedTokenException
     */
    public function positiveProvider(): array
    {
        $schemas = [
            // Container
            'type A { id: ID }              extend type A { id: ID }',
            'type A { id: ID }              extend type A { id: ID! }',
            'type A { id: ID! }             extend type A { id: ID! }',
            'type A { id: [ID] }            extend type A { id: [ID] }',
            'type A { id: [ID] }            extend type A { id: [ID!] }',
            'type A { id: [ID] }            extend type A { id: [ID]! }',
            'type A { id: [ID] }            extend type A { id: [ID!]! }',
            'type A { id: [ID!] }           extend type A { id: [ID!] }',
            'type A { id: [ID!] }           extend type A { id: [ID!]! }',
            'type A { id: [ID]! }           extend type A { id: [ID]! }',
            'type A { id: [ID]! }           extend type A { id: [ID!]! }',
            'type A { id: [ID!]! }          extend type A { id: [ID!]! }',

            'interface A { id: ID }         type B implements A { id: ID }',
            'interface A { id: ID }         type B implements A { id: ID! }',
            'interface A { id: ID! }        type B implements A { id: ID! }',
            'interface A { id: [ID] }       type B implements A { id: [ID] }',
            'interface A { id: [ID] }       type B implements A { id: [ID!] }',
            'interface A { id: [ID] }       type B implements A { id: [ID]! }',
            'interface A { id: [ID] }       type B implements A { id: [ID!]! }',
            'interface A { id: [ID!] }      type B implements A { id: [ID!] }',
            'interface A { id: [ID!] }      type B implements A { id: [ID!]! }',
            'interface A { id: [ID]! }      type B implements A { id: [ID]! }',
            'interface A { id: [ID]! }      type B implements A { id: [ID!]! }',
            'interface A { id: [ID!]! }     type B implements A { id: [ID!]! }',

            // Scalars
            'type A { id: Any }             extend type A { id: Any }',
            'type A { id: Any }             extend type A { id: Boolean }',
            'type A { id: Any }             extend type A { id: DateTime }',
            'type A { id: Any }             extend type A { id: Float }',
            'type A { id: Any }             extend type A { id: ID }',
            'type A { id: Any }             extend type A { id: Int }',
            'type A { id: Any }             extend type A { id: String }',
            'type A { id: Boolean }         extend type A { id: Boolean }',
            'type A { id: DateTime }        extend type A { id: DateTime }',
            'type A { id: Float }           extend type A { id: Float }',
            'type A { id: Float }           extend type A { id: Int }',
            'type A { id: ID }              extend type A { id: ID }',
            'type A { id: Int }             extend type A { id: Int }',
            'type A { id: String }          extend type A { id: DateTime }',
            'type A { id: String }          extend type A { id: ID }',
            'type A { id: String }          extend type A { id: String }',
            'type A { id: String }          extend type A { id: Boolean }',
            'type A { id: String }          extend type A { id: Float }',
            'type A { id: String }          extend type A { id: Int }',

            'interface A { id: Any }        type B implements A { id: Any }',
            'interface A { id: Any }        type B implements A { id: Boolean }',
            'interface A { id: Any }        type B implements A { id: DateTime }',
            'interface A { id: Any }        type B implements A { id: Float }',
            'interface A { id: Any }        type B implements A { id: ID }',
            'interface A { id: Any }        type B implements A { id: Int }',
            'interface A { id: Any }        type B implements A { id: String }',
            'interface A { id: Boolean }    type B implements A { id: Boolean }',
            'interface A { id: DateTime }   type B implements A { id: DateTime }',
            'interface A { id: Float }      type B implements A { id: Float }',
            'interface A { id: Float }      type B implements A { id: Int }',
            'interface A { id: ID }         type B implements A { id: ID }',
            'interface A { id: Int }        type B implements A { id: Int }',
            'interface A { id: String }     type B implements A { id: DateTime }',
            'interface A { id: String }     type B implements A { id: ID }',
            'interface A { id: String }     type B implements A { id: String }',


            // Container + Arguments
            'type A { id(f: ID): ID }                    extend type A { id(f: ID): ID }',
            'type A { id(f: ID!): ID }                   extend type A { id(f: ID): ID! }',
            'type A { id(f: ID!): ID! }                  extend type A { id(f: ID!): ID! }',
            'type A { id(f: [ID]): [ID] }                extend type A { id(f: [ID]): [ID] }',
            'type A { id(f: [ID!]): [ID] }               extend type A { id(f: [ID]): [ID!] }',
            'type A { id(f: [ID]!): [ID] }               extend type A { id(f: [ID]): [ID]! }',
            'type A { id(f: [ID!]!): [ID] }              extend type A { id(f: [ID]): [ID!]! }',
            'type A { id(f: [ID!]): [ID!] }              extend type A { id(f: [ID!]): [ID!] }',
            'type A { id(f: [ID!]!): [ID!] }             extend type A { id(f: [ID!]): [ID!]! }',
            'type A { id(f: [ID]!): [ID]! }              extend type A { id(f: [ID]!): [ID]! }',
            'type A { id(f: [ID!]!): [ID]! }             extend type A { id(f: [ID]!): [ID!]! }',
            'type A { id(f: [ID!]!): [ID!]! }            extend type A { id(f: [ID!]!): [ID!]! }',

            'interface A { id(f: ID): ID }               type B implements A { id(f: ID): ID }',
            'interface A { id(f: ID!): ID }              type B implements A { id(f: ID): ID! }',
            'interface A { id(f: ID!): ID! }             type B implements A { id(f: ID!): ID! }',
            'interface A { id(f: [ID]): [ID] }           type B implements A { id(f: [ID]): [ID] }',
            'interface A { id(f: [ID!]): [ID] }          type B implements A { id(f: [ID]): [ID!] }',
            'interface A { id(f: [ID]!): [ID] }          type B implements A { id(f: [ID]): [ID]! }',
            'interface A { id(f: [ID!]!): [ID] }         type B implements A { id(f: [ID]): [ID!]! }',
            'interface A { id(f: [ID!]): [ID!] }         type B implements A { id(f: [ID!]): [ID!] }',
            'interface A { id(f: [ID!]!): [ID!] }        type B implements A { id(f: [ID!]): [ID!]! }',
            'interface A { id(f: [ID]!): [ID]! }         type B implements A { id(f: [ID]!): [ID]! }',
            'interface A { id(f: [ID!]!): [ID]! }        type B implements A { id(f: [ID]!): [ID!]! }',
            'interface A { id(f: [ID!]!): [ID!]! }       type B implements A { id(f: [ID!]!): [ID!]! }',

            // Scalars + Arguments
            'type A { id(f: Any): Any }                  extend type A { id(f: Any): Any }',
            'type A { id(f: Boolean): Any }              extend type A { id(f: Any): Boolean }',
            'type A { id(f: DateTime): Any }             extend type A { id(f: Any): DateTime }',
            'type A { id(f: Float): Any }                extend type A { id(f: Any): Float }',
            'type A { id(f: ID): Any }                   extend type A { id(f: Any): ID }',
            'type A { id(f: Int): Any }                  extend type A { id(f: Any): Int }',
            'type A { id(f: String): Any }               extend type A { id(f: Any): String }',
            'type A { id(f: Boolean): Boolean }          extend type A { id(f: Boolean): Boolean }',
            'type A { id(f: DateTime): DateTime }        extend type A { id(f: DateTime): DateTime }',
            'type A { id(f: Float): Float }              extend type A { id(f: Float): Float }',
            'type A { id(f: Int): Float }                extend type A { id(f: Float): Int }',
            'type A { id(f: ID): ID }                    extend type A { id(f: ID): ID }',
            'type A { id(f: Int): Int }                  extend type A { id(f: Int): Int }',
            'type A { id(f: DateTime): String }          extend type A { id(f: String): DateTime }',
            'type A { id(f: ID): String }                extend type A { id(f: String): ID }',
            'type A { id(f: String): String }            extend type A { id(f: String): String }',

            'interface A { id(f: Any): Any }             type B implements A { id(f: Any): Any }',
            'interface A { id(f: Boolean): Any }         type B implements A { id(f: Any): Boolean }',
            'interface A { id(f: DateTime): Any }        type B implements A { id(f: Any): DateTime }',
            'interface A { id(f: Float): Any }           type B implements A { id(f: Any): Float }',
            'interface A { id(f: ID): Any }              type B implements A { id(f: Any): ID }',
            'interface A { id(f: Int): Any }             type B implements A { id(f: Any): Int }',
            'interface A { id(f: String): Any }          type B implements A { id(f: Any): String }',
            'interface A { id(f: Boolean): Boolean }     type B implements A { id(f: Boolean): Boolean }',
            'interface A { id(f: DateTime): DateTime }   type B implements A { id(f: DateTime): DateTime }',
            'interface A { id(f: Float): Float }         type B implements A { id(f: Float): Float }',
            'interface A { id(f: Int): Float }           type B implements A { id(f: Float): Int }',
            'interface A { id(f: ID): ID }               type B implements A { id(f: ID): ID }',
            'interface A { id(f: Int): Int }             type B implements A { id(f: Int): Int }',
            'interface A { id(f: DateTime): String }     type B implements A { id(f: String): DateTime }',
            'interface A { id(f: ID): String }           type B implements A { id(f: String): ID }',
            'interface A { id(f: String): String }       type B implements A { id(f: String): String }',


            // Interfaces
            'interface I {} 
                type A implements I { id: I } 
                extend type A { id: I }',
            'interface I {} 
                type A implements I { id: I } 
                extend type A { id: A }',

            // Unions
            'union U = ID | String 
                type A { id: U } 
                extend type A { id: U }',

            'union U = ID | String 
                type A { id: U } 
                extend type A { id: ID }',

            'union U = ID | String 
                type A { id: U } 
                extend type A { id: String }',

            'union X = ID | String | A 
                interface A { id: X }   
                type B implements A { id: A }',

            // Objects
            'type A { id: A }   extend type A { id: A }',

            // Arguments overriding
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
     * @throws \Railt\SDL\Exceptions\CompilerException
     * @throws \Railt\SDL\Exceptions\UnexpectedTokenException
     * @throws \Railt\SDL\Exceptions\UnrecognizedTokenException
     */
    public function negativeProvider(): array
    {
        $schemas = [
            // Type + Extend
            'type A { id: ID }      extend type A { id: [ID] }',
            'type A { id: ID }      extend type A { id: [ID!] }',
            'type A { id: ID }      extend type A { id: [ID]! }',
            'type A { id: ID }      extend type A { id: [ID!]! }',
            'type A { id: ID! }     extend type A { id: ID }',
            'type A { id: ID! }     extend type A { id: [ID] }',
            'type A { id: ID! }     extend type A { id: [ID!] }',
            'type A { id: ID! }     extend type A { id: [ID]! }',
            'type A { id: ID! }     extend type A { id: [ID!]! }',
            'type A { id: [ID] }    extend type A { id: ID }',
            'type A { id: [ID] }    extend type A { id: ID! }',
            'type A { id: [ID!] }   extend type A { id: ID }',
            'type A { id: [ID!] }   extend type A { id: ID! }',
            'type A { id: [ID!] }   extend type A { id: [ID] }',
            'type A { id: [ID!] }   extend type A { id: [ID]! }',
            'type A { id: [ID]! }   extend type A { id: ID }',
            'type A { id: [ID]! }   extend type A { id: ID! }',
            'type A { id: [ID]! }   extend type A { id: [ID!] }',
            'type A { id: [ID!]! }  extend type A { id: ID }',
            'type A { id: [ID!]! }  extend type A { id: ID! }',

            // Extend + Type (Inverse definition)
            'extend type A { id: [ID] }     type A { id: ID }',
            'extend type A { id: [ID!] }    type A { id: ID }',
            'extend type A { id: [ID]! }    type A { id: ID }',
            'extend type A { id: [ID!]! }   type A { id: ID }',
            'extend type A { id: ID }       type A { id: ID! }',
            'extend type A { id: [ID] }     type A { id: ID! }',
            'extend type A { id: [ID!] }    type A { id: ID! }',
            'extend type A { id: [ID]! }    type A { id: ID! }',
            'extend type A { id: [ID!]! }   type A { id: ID! }',
            'extend type A { id: ID }       type A { id: [ID] }',
            'extend type A { id: ID! }      type A { id: [ID] }',
            'extend type A { id: ID }       type A { id: [ID!] }',
            'extend type A { id: ID! }      type A { id: [ID!] }',
            'extend type A { id: [ID] }     type A { id: [ID!] }',
            'extend type A { id: [ID]! }    type A { id: [ID!] }',
            'extend type A { id: ID }       type A { id: [ID]! }',
            'extend type A { id: ID! }      type A { id: [ID]! }',
            'extend type A { id: [ID!] }    type A { id: [ID]! }',
            'extend type A { id: ID }       type A { id: [ID!]! }',
            'extend type A { id: ID! }      type A { id: [ID!]! }',

            // Interface + Type
            'interface B { id: Boolean }     type A implements B { id: Any }',
            'interface B { id: Boolean }     type A implements B { id: DateTime }',
            'interface B { id: Boolean }     type A implements B { id: Float }',
            'interface B { id: Boolean }     type A implements B { id: ID }',
            'interface B { id: Boolean }     type A implements B { id: Int }',
            'interface B { id: Boolean }     type A implements B { id: String }',
            'interface B { id: DateTime }    type A implements B { id: Any }',
            'interface B { id: DateTime }    type A implements B { id: Boolean }',
            'interface B { id: DateTime }    type A implements B { id: Float }',
            'interface B { id: DateTime }    type A implements B { id: ID }',
            'interface B { id: DateTime }    type A implements B { id: Int }',
            'interface B { id: DateTime }    type A implements B { id: String }',
            'interface B { id: Float }       type A implements B { id: Any }',
            'interface B { id: Float }       type A implements B { id: Boolean }',
            'interface B { id: Float }       type A implements B { id: DateTime }',
            'interface B { id: Float }       type A implements B { id: ID }',
            'interface B { id: Float }       type A implements B { id: String }',
            'interface B { id: ID }          type A implements B { id: Any }',
            'interface B { id: ID }          type A implements B { id: Boolean }',
            'interface B { id: ID }          type A implements B { id: DateTime }',
            'interface B { id: ID }          type A implements B { id: Float }',
            'interface B { id: ID }          type A implements B { id: Int }',
            'interface B { id: ID }          type A implements B { id: String }',
            'interface B { id: Int }         type A implements B { id: Any }',
            'interface B { id: Int }         type A implements B { id: Boolean }',
            'interface B { id: Int }         type A implements B { id: DateTime }',
            'interface B { id: Int }         type A implements B { id: Float }',
            'interface B { id: Int }         type A implements B { id: ID }',
            'interface B { id: Int }         type A implements B { id: String }',
            'interface B { id: String }      type A implements B { id: Any }',

            // Type + Interface (Inverse definition)
            'type A implements B { id: Any }        interface B { id: Boolean }',
            'type A implements B { id: DateTime }   interface B { id: Boolean }',
            'type A implements B { id: Float }      interface B { id: Boolean }',
            'type A implements B { id: ID }         interface B { id: Boolean }',
            'type A implements B { id: Int }        interface B { id: Boolean }',
            'type A implements B { id: String }     interface B { id: Boolean }',
            'type A implements B { id: Any }        interface B { id: DateTime }',
            'type A implements B { id: Boolean }    interface B { id: DateTime }',
            'type A implements B { id: Float }      interface B { id: DateTime }',
            'type A implements B { id: ID }         interface B { id: DateTime }',
            'type A implements B { id: Int }        interface B { id: DateTime }',
            'type A implements B { id: String }     interface B { id: DateTime }',
            'type A implements B { id: Any }        interface B { id: Float }',
            'type A implements B { id: Boolean }    interface B { id: Float }',
            'type A implements B { id: DateTime }   interface B { id: Float }',
            'type A implements B { id: ID }         interface B { id: Float }',
            'type A implements B { id: String }     interface B { id: Float }',
            'type A implements B { id: Any }        interface B { id: ID }',
            'type A implements B { id: Boolean }    interface B { id: ID }',
            'type A implements B { id: DateTime }   interface B { id: ID }',
            'type A implements B { id: Float }      interface B { id: ID }',
            'type A implements B { id: Int }        interface B { id: ID }',
            'type A implements B { id: String }     interface B { id: ID }',
            'type A implements B { id: Any }        interface B { id: Int }',
            'type A implements B { id: Boolean }    interface B { id: Int }',
            'type A implements B { id: DateTime }   interface B { id: Int }',
            'type A implements B { id: Float }      interface B { id: Int }',
            'type A implements B { id: ID }         interface B { id: Int }',
            'type A implements B { id: String }     interface B { id: Int }',
            'type A implements B { id: Any }        interface B { id: String }',

            // Scalars + Extend
            'type A { id: Boolean }     extend type A { id: Any }',
            'type A { id: Boolean }     extend type A { id: DateTime }',
            'type A { id: Boolean }     extend type A { id: Float }',
            'type A { id: Boolean }     extend type A { id: ID }',
            'type A { id: Boolean }     extend type A { id: Int }',
            'type A { id: Boolean }     extend type A { id: String }',
            'type A { id: DateTime }    extend type A { id: Any }',
            'type A { id: DateTime }    extend type A { id: Boolean }',
            'type A { id: DateTime }    extend type A { id: Float }',
            'type A { id: DateTime }    extend type A { id: ID }',
            'type A { id: DateTime }    extend type A { id: Int }',
            'type A { id: DateTime }    extend type A { id: String }',
            'type A { id: Float }       extend type A { id: Any }',
            'type A { id: Float }       extend type A { id: Boolean }',
            'type A { id: Float }       extend type A { id: DateTime }',
            'type A { id: Float }       extend type A { id: ID }',
            'type A { id: Float }       extend type A { id: String }',
            'type A { id: ID }          extend type A { id: Any }',
            'type A { id: ID }          extend type A { id: Boolean }',
            'type A { id: ID }          extend type A { id: DateTime }',
            'type A { id: ID }          extend type A { id: Float }',
            'type A { id: ID }          extend type A { id: Int }',
            'type A { id: ID }          extend type A { id: String }',
            'type A { id: Int }         extend type A { id: Any }',
            'type A { id: Int }         extend type A { id: Boolean }',
            'type A { id: Int }         extend type A { id: DateTime }',
            'type A { id: Int }         extend type A { id: Float }',
            'type A { id: Int }         extend type A { id: ID }',
            'type A { id: Int }         extend type A { id: String }',
            'type A { id: String }      extend type A { id: Any }',

            // Scalar to Object
            'interface A { id: ID }               type B implements A { id: B }',
            'interface A { id: B }                type B implements A { id: A }',
            'union X = ID | String interface A { id: X }    type B implements A { id: A }',
            'scalar Test interface A { id: Test } type B implements A { id: A }',
            'enum X {} interface A { id: X }      type B implements A { id: A }',
            'input X {} interface A { id: X }     type B implements A { id: A }',

            // Interfaces
            'interface A { field: A }
                interface B {}
                type X implements A { field: B }',
            'interface I {} 
                type A { id: I } 
                extend type A { id: A }', // Not implements
            'interface I {} 
                type A { id: I } 
                extend type A { id: ID }', // Overwrite by other type
            'interface I {} 
                interface J {} 
                type A { id: I } 
                extend type A { id: J }', // Overwrite by other interface
            'interface I { id: ID }
                type A implements I {}', // Missing implementation

            // Unions
            'union U = String 
                union U2 = ID 
                type A { id: U } 
                extend type A { id: U2 }',
            'union U = ID | String 
                type A { id: U } 
                extend type A { id: Int }',

            // Incompatible types
            'type Object {}         
                type A { id: Object }    
                extend type A { id: ID }',
            'interface Interface {} 
                type A { id: Interface } 
                extend type A { id: ID }',
            'union Union = String   
                type A { id: Union }     
                extend type A { id: ID }',
            'enum Enum { A }        
                type A { id: Enum }      
                extend type A { id: ID }',
            'input Input { id: ID } 
                type A { id: Input }     
                extend type A { id: ID }',
            'type A {} type B {}    
                type C { id: A }         
                extend type C { id: B }',
            'interface I {}         
                type A implements I {}  
                type B implements I {} 
                type C { id: A } 
                extend type C { id: B }',
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
     * @throws \PHPUnit\Framework\AssertionFailedError
     */
    public function testExtendPositiveInheritance(string $schema): void
    {
        try {
            /** @var CompilerInterface $compiler */
            foreach ($this->getCompilers() as $compiler) {
                $result = $compiler->compile(File::fromSources($schema));
                static::assertNotNull($result);
            }
        } catch (\Throwable $e) {
            static::assertFalse(true, $e->getMessage() . "\n    " . $schema);
        }
    }

    /**
     * @dataProvider negativeProvider
     *
     * @param string $schema
     * @return void
     * @throws \LogicException
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     */
    public function testExtendNegativeInheritance(string $schema): void
    {
        /** @var CompilerInterface|Compiler $compiler */
        foreach ($this->getCompilers() as $compiler) {
            try {
                $compiler->compile(File::fromSources($schema));
                static::assertFalse(true, 'Exception required in:' . "\n    " . $schema);
            } catch (TypeConflictException $error) {
                // No assertions hotfix
                static::assertInstanceOf(TypeConflictException::class, $error);

                $this->messages[] = 'OK (Throws an error): ' . $schema;
            }
        }
    }
}
