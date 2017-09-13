<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Reflection;

use Railt\Parser\Exceptions\NotReadableException;
use Railt\Parser\Exceptions\UnexpectedTokenException;
use Railt\Parser\Exceptions\UnrecognizedTokenException;
use Railt\Reflection\Abstraction\ArgumentInterface;
use Railt\Reflection\Abstraction\Common\HasFieldsInterface;
use Railt\Reflection\Abstraction\DocumentTypeInterface;
use Railt\Reflection\Abstraction\FieldInterface;
use Railt\Reflection\Abstraction\InputTypeInterface;
use Railt\Reflection\Abstraction\InterfaceTypeInterface;
use Railt\Reflection\Abstraction\ObjectTypeInterface;
use Railt\Reflection\Abstraction\SchemaTypeInterface;
use Railt\Reflection\Abstraction\UnionTypeInterface;
use Railt\Reflection\Compiler;
use Railt\Reflection\Exceptions\TypeConflictException;
use Railt\Reflection\Exceptions\UnrecognizedNodeException;
use Railt\Reflection\Reflection\Document;
use Railt\Tests\AbstractTestCase;

/**
 * Class ReflectionTestCase
 * @package Railt\Tests\Reflection
 */
class ReflectionTestCase extends AbstractTestCase
{
    /**
     * @var string
     */
    protected $resourcesPath = 'type-tests/';

    /**
     * @throws NotReadableException
     * @throws UnexpectedTokenException
     * @throws UnrecognizedTokenException
     * @throws \PHPUnit\Framework\Exception
     */
    public function testDocument(): void
    {
        $document = $this->getDocument();

        $this->assertNotNull($document);
        $this->assertContains('schema-1', $document->getFileName());

        $this->checkNames($document);
        $this->assertEquals(8, $document->getDictionary()->count());
    }

    /**
     * @return DocumentTypeInterface|Document
     * @throws NotReadableException
     * @throws UnrecognizedTokenException
     * @throws \LogicException
     * @throws TypeConflictException
     * @throws UnrecognizedNodeException
     */
    private function getDocument(): DocumentTypeInterface
    {
        $compiler = $this->getCompiler();

        return $compiler->compile($this->file('schema-1.graphqls'));
    }

    /**
     * @return Compiler
     */
    private function getCompiler(): Compiler
    {
        $compiler = new Compiler();

        $compiler->getAutoloader()->dir([
            $this->resource(''),
            $this->resource('enums'),
            $this->resource('models'),
        ]);

        return $compiler;
    }

    /**
     * @param Document|DocumentTypeInterface $document
     * @throws UnexpectedTokenException
     */
    private function checkNames(DocumentTypeInterface $document): void
    {
        foreach ($document->getDictionary()->all() as $loadedType) {
            $this->assertNotNull($loadedType->getTypeName());
        }
    }

    /**
     * @throws UnexpectedTokenException
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws NotReadableException
     * @throws UnrecognizedTokenException
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
     * @return null|SchemaTypeInterface
     * @throws UnexpectedTokenException
     * @throws NotReadableException
     * @throws UnrecognizedTokenException
     */
    private function getSchema(): ?SchemaTypeInterface
    {
        return $this->getDocument()->getSchema();
    }

    /**
     * @throws UnexpectedTokenException
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     * @throws NotReadableException
     * @throws UnrecognizedTokenException
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
     * @return null|HasFieldsInterface
     * @throws UnexpectedTokenException
     * @throws NotReadableException
     * @throws UnrecognizedTokenException
     */
    private function getQuery(): ?HasFieldsInterface
    {
        return $this->getSchema()->getQuery();
    }

    /**
     * @throws UnexpectedTokenException
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws NotReadableException
     * @throws UnrecognizedTokenException
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
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws NotReadableException
     * @throws UnrecognizedTokenException
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
     * @param string $name
     * @return null|FieldInterface
     * @throws UnexpectedTokenException
     * @throws NotReadableException
     * @throws UnrecognizedTokenException
     */
    private function getField(string $name): ?FieldInterface
    {
        return $this->getQuery()->getField($name);
    }

    /**
     * @throws UnexpectedTokenException
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws NotReadableException
     * @throws UnrecognizedTokenException
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
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws NotReadableException
     * @throws UnrecognizedTokenException
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
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws NotReadableException
     * @throws UnrecognizedTokenException
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
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws NotReadableException
     * @throws UnrecognizedTokenException
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
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws NotReadableException
     * @throws UnrecognizedTokenException
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
     * @throws UnexpectedTokenException
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     * @throws NotReadableException
     * @throws UnrecognizedTokenException
     */
    public function testUnionType(): void
    {
        $person = $this->getPersonUnion();

        $this->assertNotNull($person);

        $this->assertEquals('Union', $person->getTypeName());
        $this->assertEquals('Person', $person->getName());

        $this->assertCount(2, $person->getTypes());

        $this->assertTrue($person->hasType('User'));
        $this->assertEquals('Object', $person->getType('User')->getTypeName());

        $this->assertTrue($person->hasType('Bot'));
        $this->assertEquals('Object', $person->getType('Bot')->getTypeName());

        $this->assertFalse($person->hasType('Person'));
        $this->assertFalse($person->hasType('Document'));
    }

    /**
     * @return null|UnionTypeInterface
     * @throws UnexpectedTokenException
     * @throws NotReadableException
     * @throws UnrecognizedTokenException
     */
    private function getPersonUnion(): ?UnionTypeInterface
    {
        return $this->getField('user')->getRelationDefinition();
    }

    /**
     * @throws UnexpectedTokenException
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     * @throws NotReadableException
     * @throws UnrecognizedTokenException
     */
    public function testUserObject(): void
    {
        /** @var ObjectTypeInterface $user */
        $user = $this->getUserObject();

        $this->assertNotNull($user);

        $this->assertEquals('Object', $user->getTypeName());
        $this->assertEquals('User', $user->getName());

        $this->assertCount(7, $user->getFields());


        $this->assertNotNull($user->getField('id'));
        $this->assertEquals('id', $user->getField('id')->getName());
        $this->assertEquals('ID', $user->getField('id')->getRelationName());
        $this->assertTrue($user->getField('id')->nonNull());
        $this->assertFalse($user->getField('id')->isList());
        $this->assertCount(0, $user->getField('id')->getArguments());


        $this->assertNotNull($user->getField('name'));
        $this->assertEquals('name', $user->getField('name')->getName());
        $this->assertEquals('String', $user->getField('name')->getRelationName());
        $this->assertTrue($user->getField('name')->nonNull());
        $this->assertFalse($user->getField('name')->isList());
        $this->assertCount(0, $user->getField('name')->getArguments());


        $this->assertNotNull($user->getField('createdAt'));
        $this->assertEquals('createdAt', $user->getField('createdAt')->getName());
        $this->assertEquals('String', $user->getField('createdAt')->getRelationName());
        $this->assertTrue($user->getField('createdAt')->nonNull());
        $this->assertFalse($user->getField('createdAt')->isList());
        $this->assertCount(1, $user->getField('createdAt')->getArguments());
        // ------------------------------ Arguments ------------------------------
        $format = $user->getField('createdAt')->getArgument('format');
        $this->assertNotNull($format);
        $this->assertEquals('Argument', $format->getTypeName());
        $this->assertEquals('format', $format->getName());
        $this->assertTrue($format->hasDefaultValue());
        $this->assertEquals('RFC3339', $format->getDefaultValue());


        $this->assertNotNull($user->getField('updatedAt'));
        $this->assertEquals('updatedAt', $user->getField('updatedAt')->getName());
        $this->assertEquals('String', $user->getField('updatedAt')->getRelationName());
        $this->assertFalse($user->getField('updatedAt')->nonNull());
        $this->assertFalse($user->getField('updatedAt')->isList());
        $this->assertCount(1, $user->getField('updatedAt')->getArguments());
        // ------------------------------ Arguments ------------------------------
        $format = $user->getField('updatedAt')->getArgument('format');
        $this->assertNotNull($format);
        $this->assertEquals('Argument', $format->getTypeName());
        $this->assertEquals('format', $format->getName());
        $this->assertTrue($format->hasDefaultValue());
        $this->assertEquals('RFC3339', $format->getDefaultValue());


        $this->assertNotNull($user->getField('tags'));
        $this->assertEquals('tags', $user->getField('tags')->getName());
        $this->assertEquals('String', $user->getField('tags')->getRelationName());
        $this->assertTrue($user->getField('tags')->nonNull());
        $this->assertTrue($user->getField('tags')->isList());
        $this->assertFalse($user->getField('tags')->getType()->getChild()->nonNull());
        $this->assertCount(1, $user->getField('tags')->getArguments());
        // ------------------------------ Arguments ------------------------------
        /** @var ArgumentInterface $values */
        $values = $user->getField('tags')->getArgument('values');
        $this->assertNotNull($values);
        $this->assertEquals('Argument', $values->getTypeName());
        $this->assertEquals('values', $values->getName());
        // TODO Allow $argument->isList() like field
        $this->assertTrue($values->getType()->isList());
        $this->assertTrue($values->getType()->nonNull());
        $this->assertFalse($values->getType()->getChild()->nonNull());
        $this->assertEquals('String', $values->getType()->getRelationName());
        $this->assertTrue($values->hasDefaultValue());
        $this->assertInternalType('array', $values->getDefaultValue());
        $this->assertCount(2, $values->getDefaultValue());
        $this->assertEquals('Some', $values->getDefaultValue()[0]);
        $this->assertInternalType('object', $values->getDefaultValue()[1]);
        $this->assertNotNull($values->getDefaultValue()[1]->any);
        $this->assertEquals('Any', $values->getDefaultValue()[1]->any);


        $this->assertNotNull($user->getField('deletedAt'));
        $this->assertEquals('deletedAt', $user->getField('deletedAt')->getName());
        $this->assertEquals('String', $user->getField('deletedAt')->getRelationName());
        $this->assertFalse($user->getField('deletedAt')->nonNull());
        $this->assertFalse($user->getField('deletedAt')->isList());
        $this->assertCount(1, $user->getField('deletedAt')->getArguments());
        // ------------------------------ Arguments ------------------------------
        $format = $user->getField('deletedAt')->getArgument('format');
        $this->assertNotNull($format);
        $this->assertEquals('Argument', $format->getTypeName());
        $this->assertEquals('format', $format->getName());
        $this->assertFalse($format->hasDefaultValue());
        $this->assertEquals(null, $format->getDefaultValue());


        $this->assertNotNull($user->getField('isBanned'));
        $this->assertEquals('isBanned', $user->getField('isBanned')->getName());
        $this->assertEquals('Boolean', $user->getField('isBanned')->getRelationName());
        $this->assertTrue($user->getField('isBanned')->nonNull());
        $this->assertFalse($user->getField('isBanned')->isList());
        $this->assertCount(0, $user->getField('isBanned')->getArguments());
    }

    /**
     * @return null|ObjectTypeInterface
     * @throws UnexpectedTokenException
     * @throws NotReadableException
     * @throws UnrecognizedTokenException
     */
    private function getUserObject(): ?ObjectTypeInterface
    {
        return $this->getPersonUnion()->getType('User');
    }

    /**
     * @throws UnexpectedTokenException
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     * @throws NotReadableException
     * @throws UnrecognizedTokenException
     */
    public function testBotObject(): void
    {
        /** @var ObjectTypeInterface $bot */
        $bot = $this->getBotObject();

        $this->assertNotNull($bot);

        $this->assertEquals('Object', $bot->getTypeName());
        $this->assertEquals('Bot', $bot->getName());

        $this->assertCount(5, $bot->getFields());


        $this->assertNotNull($bot->getField('id'));
        $this->assertEquals('id', $bot->getField('id')->getName());
        $this->assertEquals('ID', $bot->getField('id')->getRelationName());
        $this->assertTrue($bot->getField('id')->nonNull());
        $this->assertFalse($bot->getField('id')->isList());
        $this->assertCount(0, $bot->getField('id')->getArguments());


        $this->assertNotNull($bot->getField('name'));
        $this->assertEquals('name', $bot->getField('name')->getName());
        $this->assertEquals('String', $bot->getField('name')->getRelationName());
        $this->assertTrue($bot->getField('name')->nonNull());
        $this->assertFalse($bot->getField('name')->isList());
        $this->assertCount(0, $bot->getField('name')->getArguments());


        $this->assertNotNull($bot->getField('createdAt'));
        $this->assertEquals('createdAt', $bot->getField('createdAt')->getName());
        $this->assertEquals('String', $bot->getField('createdAt')->getRelationName());
        $this->assertTrue($bot->getField('createdAt')->nonNull());
        $this->assertFalse($bot->getField('createdAt')->isList());
        $this->assertCount(1, $bot->getField('createdAt')->getArguments());
        // ------------------------------ Arguments ------------------------------
        $format = $bot->getField('createdAt')->getArgument('format');
        $this->assertNotNull($format);
        $this->assertEquals('Argument', $format->getTypeName());
        $this->assertEquals('format', $format->getName());
        $this->assertTrue($format->hasDefaultValue());
        $this->assertEquals('ISO8601', $format->getDefaultValue());


        $this->assertNotNull($bot->getField('updatedAt'));
        $this->assertEquals('updatedAt', $bot->getField('updatedAt')->getName());
        $this->assertEquals('String', $bot->getField('updatedAt')->getRelationName());
        $this->assertFalse($bot->getField('updatedAt')->nonNull());
        $this->assertFalse($bot->getField('updatedAt')->isList());
        $this->assertCount(1, $bot->getField('updatedAt')->getArguments());
        // ------------------------------ Arguments ------------------------------
        $format = $bot->getField('updatedAt')->getArgument('format');
        $this->assertNotNull($format);
        $this->assertEquals('Argument', $format->getTypeName());
        $this->assertEquals('format', $format->getName());
        $this->assertTrue($format->hasDefaultValue());
        $this->assertEquals('RFC2822', $format->getDefaultValue());


        $this->assertNotNull($bot->getField('tags'));
        $this->assertEquals('tags', $bot->getField('tags')->getName());
        $this->assertEquals('String', $bot->getField('tags')->getRelationName());
        $this->assertTrue($bot->getField('tags')->nonNull());
        $this->assertTrue($bot->getField('tags')->isList());
        $this->assertFalse($bot->getField('tags')->getType()->getChild()->nonNull());
        $this->assertCount(1, $bot->getField('tags')->getArguments());
        // ------------------------------ Arguments ------------------------------
        /** @var ArgumentInterface $values */
        $values = $bot->getField('tags')->getArgument('values');
        $this->assertNotNull($values);
        $this->assertEquals('Argument', $values->getTypeName());
        $this->assertEquals('values', $values->getName());
        // TODO Allow $argument->isList() like field
        $this->assertTrue($values->getType()->isList());
        $this->assertTrue($values->getType()->nonNull());
        $this->assertFalse($values->getType()->getChild()->nonNull());
        $this->assertEquals('String', $values->getType()->getRelationName());
        $this->assertFalse($values->hasDefaultValue());
        $this->assertNull($values->getDefaultValue());
    }

    /**
     * @return null|ObjectTypeInterface
     * @throws UnexpectedTokenException
     * @throws NotReadableException
     * @throws UnrecognizedTokenException
     */
    private function getBotObject(): ?ObjectTypeInterface
    {
        return $this->getPersonUnion()->getType('Bot');
    }

    /**
     * @throws UnexpectedTokenException
     */
    public function testPersonInterface()
    {
        $person = $this->getPersonUnion();
        $this->assertNotNull($person);

        /** @var InterfaceTypeInterface $personInterface */
        $personInterface = $person->getDocument()->load('PersonInterface');
        $this->assertNotNull($personInterface);

        /** @var ObjectTypeInterface $user */
        $user = $person->getType('User');
        $this->assertNotNull($user);

        /** @var ObjectTypeInterface $bot */
        $bot = $person->getType('Bot');
        $this->assertNotNull($bot);

        $this->assertTrue($user->hasInterface('PersonInterface'));
        $userInterface = $user->getInterface('PersonInterface');
        $this->assertNotNull($userInterface);

        $this->assertTrue($bot->hasInterface('PersonInterface'));
        $botInterface = $bot->getInterface('PersonInterface');
        $this->assertNotNull($botInterface);

        $this->assertEquals($userInterface, $botInterface);
        $this->assertEquals($userInterface, $personInterface);
        $this->assertEquals($botInterface, $personInterface);


        $this->assertEquals('Interface', $personInterface->getTypeName());
        $this->assertEquals('PersonInterface', $personInterface->getName());

        $this->assertCount(5, $personInterface->getFields());


        $this->assertNotNull($personInterface->getField('id'));
        $this->assertEquals('id', $personInterface->getField('id')->getName());
        $this->assertEquals('ID', $personInterface->getField('id')->getRelationName());
        $this->assertTrue($personInterface->getField('id')->nonNull());
        $this->assertFalse($personInterface->getField('id')->isList());
        $this->assertCount(0, $personInterface->getField('id')->getArguments());


        $this->assertNotNull($personInterface->getField('name'));
        $this->assertEquals('name', $personInterface->getField('name')->getName());
        $this->assertEquals('String', $personInterface->getField('name')->getRelationName());
        $this->assertTrue($personInterface->getField('name')->nonNull());
        $this->assertFalse($personInterface->getField('name')->isList());
        $this->assertCount(0, $personInterface->getField('name')->getArguments());


        $this->assertNotNull($personInterface->getField('createdAt'));
        $this->assertEquals('createdAt', $personInterface->getField('createdAt')->getName());
        $this->assertEquals('String', $personInterface->getField('createdAt')->getRelationName());
        $this->assertTrue($personInterface->getField('createdAt')->nonNull());
        $this->assertFalse($personInterface->getField('createdAt')->isList());
        $this->assertCount(1, $personInterface->getField('createdAt')->getArguments());
        // ------------------------------ Arguments ------------------------------
        $format = $personInterface->getField('createdAt')->getArgument('format');
        $this->assertNotNull($format);
        $this->assertEquals('Argument', $format->getTypeName());
        $this->assertEquals('format', $format->getName());
        $this->assertTrue($format->hasDefaultValue());
        $this->assertEquals('ISO8601', $format->getDefaultValue());


        $this->assertNotNull($personInterface->getField('updatedAt'));
        $this->assertEquals('updatedAt', $personInterface->getField('updatedAt')->getName());
        $this->assertEquals('String', $personInterface->getField('updatedAt')->getRelationName());
        $this->assertFalse($personInterface->getField('updatedAt')->nonNull());
        $this->assertFalse($personInterface->getField('updatedAt')->isList());
        $this->assertCount(1, $personInterface->getField('updatedAt')->getArguments());
        // ------------------------------ Arguments ------------------------------
        $format = $personInterface->getField('updatedAt')->getArgument('format');
        $this->assertNotNull($format);
        $this->assertEquals('Argument', $format->getTypeName());
        $this->assertEquals('format', $format->getName());
        $this->assertTrue($format->hasDefaultValue());
        $this->assertEquals('RFC2822', $format->getDefaultValue());
    }

    /**
     * @return null|InterfaceTypeInterface
     * @throws UnexpectedTokenException
     * @throws NotReadableException
     * @throws UnrecognizedTokenException
     */
    private function getPersonInterface(): ?InterfaceTypeInterface
    {
        return $this->getUserObject()->getInterface('PersonInterface');
    }
}
