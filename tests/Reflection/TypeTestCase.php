<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Tests\Reflection;

use Serafim\Railgun\Compiler\Compiler;
use Serafim\Railgun\Reflection\Abstraction\SchemaTypeInterface;
use Serafim\Railgun\Tests\AbstractTestCase;
use Serafim\Railgun\Compiler\Exceptions\UnexpectedTokenException;

/**
 *
 * Class CompilerTestCase
 * @package Serafim\Railgun\Tests\Reflection
 */
class TypeTestCase extends AbstractTestCase
{
    /**
     * @var string
     */
    protected $resourcesPath = 'type-tests/';

    /**
     * @return array
     * @throws UnexpectedTokenException
     */
    public function testComposite(): array
    {
        $compiler = new Compiler();

        $compiler->getLoader()
            ->psr0($this->resource('/'))
            ->psr0($this->resource('enums/'))
            ->psr0($this->resource('models/'))
        ;

        /**
         * Document
         */
        $document = $compiler->compileFile($this->resource('schema-1.graphqls'));
        $this->assertNotNull($document);

        /**
         * Schema
         */
        $schema = $document->getSchema();
        $this->assertNotNull($schema);

        return [[$schema]];
    }

    /**
     * @dataProvider testComposite
     * @param SchemaTypeInterface $schema
     */
    public function testSchema(SchemaTypeInterface $schema): void
    {
        $query = $schema->getQuery();
        $this->assertNotNull($query);
        $this->assertEquals('Query', $query->getName());

        /**
         * Fields
         */
        $this->assertFalse($query->hasField('id'));
        $this->assertTrue($query->hasField('users'));

        $this->assertNull($query->getField('id'));

        /**
         * Field "users":
         *
         * <code>
         *  users: [Person]!
         *  nullableUsers: [Person]
         *  nonNullsUsers: [Person!]
         *  nonNullUsers: [Person!]!
         *  user: Person
         * </code>
         */
        $users = $query->getField('users');

        $this->assertNotNull($users);

        $this->assertTrue($users->isList());
        $this->assertTrue($users->getType()->nonNull());

        $this->assertEquals('Person', $users->getType()->getTypeName());
    }
}
