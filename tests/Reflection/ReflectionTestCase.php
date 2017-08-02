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
use Serafim\Railgun\Reflection\Abstraction\Common\HasFieldsInterface;
use Serafim\Railgun\Reflection\Abstraction\DocumentTypeInterface;
use Serafim\Railgun\Reflection\Abstraction\FieldInterface;
use Serafim\Railgun\Reflection\Abstraction\InputTypeInterface;
use Serafim\Railgun\Reflection\Abstraction\NamedDefinitionInterface;
use Serafim\Railgun\Reflection\Abstraction\ObjectTypeInterface;
use Serafim\Railgun\Reflection\Abstraction\SchemaTypeInterface;
use Serafim\Railgun\Reflection\Abstraction\UnionTypeInterface;
use Serafim\Railgun\Tests\AbstractTestCase;
use Serafim\Railgun\Compiler\Exceptions\UnexpectedTokenException;

/**
 * Class ReflectionTestCase
 * @package Serafim\Railgun\Tests\Reflection
 */
class ReflectionTestCase extends AbstractTestCase
{
    /**
     * @var string
     */
    protected $resourcesPath = 'type-tests/';

    /**
     * @return DocumentTypeInterface
     * @throws UnexpectedTokenException
     */
    private function getDocument(): DocumentTypeInterface
    {
        $compiler = $this->getCompiler();

        return $compiler->compileFile($this->resource('schema-1.graphqls'));
    }

    /**
     * @return Compiler
     */
    private function getCompiler(): Compiler
    {
        $compiler = new Compiler();

        $compiler->getLoader()->dir([
            $this->resource(''),
            $this->resource('enums'),
            $this->resource('models'),
        ]);

        return $compiler;
    }

    /**
     * @return null|SchemaTypeInterface
     * @throws UnexpectedTokenException
     */
    private function getSchema(): ?SchemaTypeInterface
    {
        return $this->getDocument()->getSchema();
    }

    /**
     * @return null|HasFieldsInterface
     * @throws UnexpectedTokenException
     */
    private function getQuery(): ?HasFieldsInterface
    {
        return $this->getSchema()->getQuery();
    }

    /**
     * @param string $name
     * @return null|FieldInterface
     * @throws UnexpectedTokenException
     */
    private function getField(string $name): ?FieldInterface
    {
        return $this->getQuery()->getField($name);
    }

    /**
     * @return null|UnionTypeInterface|NamedDefinitionInterface
     */
    private function getPersonUnion(): ?UnionTypeInterface
    {
        return $this->getCompiler()->getDictionary()
            ->find('Person');
    }

    /**
     * @throws UnexpectedTokenException
     */
    public function testDocument(): void
    {
        $document = $this->getDocument();

        $this->assertNotNull($document);
        $this->assertContains('schema-1', $document->getFileName());
    }

    /**
     * @return void
     * @throws UnexpectedTokenException
     */
    public function testSchema(): void
    {
        $schema = $this->getSchema();

        $this->assertNotNull($schema);
        $this->assertNotNull($schema->getQuery());
        $this->assertNull($schema->getMutation());
        $this->assertFalse($schema->hasMutation());
    }

    /**
     * @throws UnexpectedTokenException
     */
    public function testQuery(): void
    {
        $query = $this->getQuery();

        $this->assertNotNull($query);
        $this->assertEquals('Query', $query->getName());
        $this->assertInstanceOf(HasFieldsInterface::class, $query);
        $this->assertTrue($query instanceof InputTypeInterface || $query instanceof ObjectTypeInterface);
    }

    /**
     * @throws UnexpectedTokenException
     */
    public function testFields(): void
    {
        $query = $this->getQuery();

        $this->assertNotNull($query);

        // Checking non existing fields
        $this->assertFalse($query->hasField('id'));
        $this->assertNull($query->getField('id'));

        $this->assertTrue($query->hasField('users'));
        $this->assertTrue($query->hasField('nullableUsers'));
        $this->assertTrue($query->hasField('nonNullsUsers'));
        $this->assertTrue($query->hasField('nonNullUsers'));
        $this->assertTrue($query->hasField('user'));
    }

    /**
     * @throws UnexpectedTokenException
     */
    public function testFieldUsers(): void
    {
        $field = $this->getField('users');

        $this->assertTrue($field->isList());
        $this->assertTrue($field->getType()->nonNull());
        $this->assertFalse($field->getType()->getChild()->nonNull());

        $this->assertEquals('Union', $field->getRelationTypeName());
        $this->assertEquals('Person', $field->getRelationName());
    }

    /**
     * @throws UnexpectedTokenException
     */
    public function testFieldNullableUsers(): void
    {
        $field = $this->getField('nullableUsers');

        $this->assertTrue($field->isList());
        $this->assertFalse($field->getType()->nonNull());
        $this->assertFalse($field->getType()->getChild()->nonNull());

        $this->assertEquals('Union', $field->getRelationTypeName());
        $this->assertEquals('Person', $field->getRelationName());
    }

    /**
     * @throws UnexpectedTokenException
     */
    public function testFieldNonNullsUsers(): void
    {
        $field = $this->getField('nonNullsUsers');

        $this->assertTrue($field->isList());
        $this->assertFalse($field->getType()->nonNull());
        $this->assertTrue($field->getType()->getChild()->nonNull());

        $this->assertEquals('Union', $field->getRelationTypeName());
        $this->assertEquals('Person', $field->getRelationName());
    }

    /**
     * @throws UnexpectedTokenException
     */
    public function testFieldNonNullUsers(): void
    {
        $field = $this->getField('nonNullUsers');

        $this->assertTrue($field->isList());
        $this->assertTrue($field->getType()->nonNull());
        $this->assertTrue($field->getType()->getChild()->nonNull());

        $this->assertEquals('Union', $field->getRelationTypeName());
        $this->assertEquals('Person', $field->getRelationName());
    }

    /**
     * @throws UnexpectedTokenException
     */
    public function testFieldUser(): void
    {
        $field = $this->getField('user');

        $this->assertFalse($field->isList());
        $this->assertFalse($field->getType()->nonNull());
        $this->assertFalse($field->getType()->getChild()->nonNull());

        $this->assertEquals('Union', $field->getRelationTypeName());
        $this->assertEquals('Person', $field->getRelationName());
    }

    /**
     * @throws UnexpectedTokenException
     */
    public function testFieldNonNullUser(): void
    {
        $field = $this->getField('nonNullUser');

        $this->assertFalse($field->isList());
        $this->assertTrue($field->getType()->nonNull());
        $this->assertTrue($field->getType()->getChild()->nonNull());

        $this->assertEquals('Union', $field->getRelationTypeName());
        $this->assertEquals('Person', $field->getRelationName());
    }

    /**
     * @return void
     */
    public function testUnionType(): void
    {
        $person = $this->getPersonUnion();

        $this->assertNotNull($person);

    }
}
