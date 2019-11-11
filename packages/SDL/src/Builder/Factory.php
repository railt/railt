<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Builder;

use Railt\SDL\Document;
use Railt\SDL\Executor\Registry;
use Railt\SDL\Ast\DefinitionNode;
use Railt\SDL\Ast\Definition\FieldDefinitionNode;
use Railt\SDL\Ast\Definition\SchemaDefinitionNode;
use GraphQL\Contracts\TypeSystem\Type\TypeInterface;
use GraphQL\Contracts\TypeSystem\DirectiveInterface;
use Railt\SDL\Ast\Definition\EnumTypeDefinitionNode;
use GraphQL\Contracts\TypeSystem\DefinitionInterface;
use Railt\SDL\Ast\Definition\DirectiveDefinitionNode;
use Railt\SDL\Ast\Definition\EnumValueDefinitionNode;
use Railt\SDL\Ast\Definition\ObjectTypeDefinitionNode;
use Railt\SDL\Ast\Definition\ScalarTypeDefinitionNode;
use Railt\SDL\Ast\Definition\InputValueDefinitionNode;
use Railt\SDL\Ast\Definition\InterfaceTypeDefinitionNode;
use Railt\SDL\Ast\Definition\InputObjectTypeDefinitionNode;

/**
 * Class Factory
 */
class Factory
{
    /**
     * @var string|TypeBuilder
     */
    private const TYPE_MAPPINGS = [
        SchemaDefinitionNode::class          => SchemaBuilder::class,
        DirectiveDefinitionNode::class       => DirectiveBuilder::class,

        // TypeDefinitions
        ObjectTypeDefinitionNode::class      => ObjectTypeBuilder::class,
        InterfaceTypeDefinitionNode::class   => InterfaceTypeBuilder::class,
        ScalarTypeDefinitionNode::class      => ScalarTypeBuilder::class,
        EnumTypeDefinitionNode::class        => EnumTypeBuilder::class,
        InputObjectTypeDefinitionNode::class => InputObjectTypeBuilder::class,

        // Definitions
        FieldDefinitionNode::class           => FieldBuilder::class,
        InputValueDefinitionNode::class      => ArgumentBuilder::class,
        EnumValueDefinitionNode::class       => EnumValueBuilder::class,
    ];

    /**
     * @var Document
     */
    private Document $dictionary;

    /**
     * Builder constructor.
     *
     * @param Document $dictionary
     */
    public function __construct(Document $dictionary)
    {
        $this->dictionary = $dictionary;
    }

    /**
     * @param Registry $registry
     * @return Document
     */
    public function loadFrom(Registry $registry): Document
    {
        foreach ($registry->typeMap as $name => $typeNode) {
            $this->getType($name, $registry);
        }

        foreach ($registry->directives as $name => $directiveNode) {
            $this->getDirective($name, $registry);
        }

        if ($registry->schema) {
            $this->dictionary->schema = $this->build($registry->schema, $registry);
        }

        return $this->dictionary;
    }

    /**
     * @param string $type
     * @param Registry $registry
     * @return TypeInterface
     */
    public function getType(string $type, Registry $registry): TypeInterface
    {
        if ($this->dictionary->typeMap->containsKey($type)) {
            return $this->dictionary->typeMap->get($type);
        }

        if ($registry->typeMap->containsKey($type)) {
            /** @var TypeInterface $result */
            $result = $this->build($registry->typeMap->get($type), $registry);

            $this->dictionary->typeMap->put($type, $result);

            return $result;
        }

        throw new \LogicException('Can not build type ' . $type);
    }

    /**
     * @param DefinitionNode $node
     * @param Registry $registry
     * @return DefinitionInterface
     */
    public function build(DefinitionNode $node, Registry $registry): DefinitionInterface
    {
        return $this->builder($node, $registry)->build();
    }

    /**
     * @param DefinitionNode $node
     * @param Registry $registry
     * @return TypeBuilder
     */
    private function builder(DefinitionNode $node, Registry $registry): TypeBuilder
    {
        $builder = self::TYPE_MAPPINGS[\get_class($node)] ?? null;

        if ($builder === null) {
            throw new \LogicException('Unrecognized builder for node ' . \get_class($node));
        }

        return new $builder($this, $registry, $this->dictionary, $node);
    }

    /**
     * @param string $type
     * @param Registry $registry
     * @return DirectiveInterface
     */
    public function getDirective(string $type, Registry $registry): DirectiveInterface
    {
        if ($this->dictionary->directives->containsKey($type)) {
            return $this->dictionary->directives->get($type);
        }

        if ($registry->directives->containsKey($type)) {
            /** @var DirectiveInterface $result */
            $result = $this->build($registry->directives->get($type), $registry);

            $this->dictionary->directives->put($type, $result);

            return $result;
        }

        throw new \LogicException('Can not build directive ' . $type);
    }
}
