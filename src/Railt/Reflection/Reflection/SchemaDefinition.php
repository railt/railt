<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Reflection;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Support\Exceptions\NotReadableException;
use Railt\Parser\Exceptions\UnexpectedTokenException;
use Railt\Parser\Exceptions\UnrecognizedTokenException;
use Railt\Reflection\Contracts\DefinitionInterface;
use Railt\Reflection\Contracts\InputTypeInterface;
use Railt\Reflection\Contracts\NamedDefinitionInterface;
use Railt\Reflection\Contracts\ObjectTypeInterface;
use Railt\Reflection\Contracts\SchemaTypeInterface;
use Railt\Reflection\Exceptions\TypeConflictException;
use Railt\Reflection\Exceptions\TypeNotFoundException;
use Railt\Reflection\Exceptions\UnrecognizedNodeException;
use Railt\Reflection\Reflection\Common\Directives;
use Railt\Reflection\Reflection\Common\HasLinkingStageInterface;
use Railt\Reflection\Reflection\Common\LinkingStage;

/**
 * Class SchemaDefinition
 * @package Railt\Reflection\Reflection
 */
class SchemaDefinition extends Definition implements
    SchemaTypeInterface,
    HasLinkingStageInterface
{
    use Directives;
    use LinkingStage;

    /**
     * @var ObjectDefinition|InputDefinition
     */
    private $query;

    /**
     * @var ObjectDefinition|InputDefinition
     */
    private $mutation;

    /**
     * @param Document $document
     * @param TreeNode $ast
     * @return TreeNode|null
     * @throws NotReadableException
     * @throws TypeConflictException
     * @throws TypeNotFoundException
     * @throws UnrecognizedNodeException
     * @throws UnrecognizedTokenException
     * @throws \LogicException
     */
    public function compile(Document $document, TreeNode $ast): ?TreeNode
    {
        $name = $ast->getId();

        switch ($name) {
            case '#Query':
                $type        = $this->getRelatedType($ast)->getValueValue();
                $this->query = $this->check('query', $document->load($type));
                break;

            case '#Mutation':
                $type           = $this->getRelatedType($ast)->getValueValue();
                $this->mutation = $this->check('query', $document->load($type));
                break;
        }

        return $ast;
    }

    /**
     * @param TreeNode $ast
     * @return TreeNode
     * @throws TypeNotFoundException
     */
    private function getRelatedType(TreeNode $ast): TreeNode
    {
        /** @var TreeNode $node */
        foreach ($ast->getChildren() as $node) {
            if ($node->getId() === '#Type') {
                return $node->getChild(0);
            }
        }

        throw new TypeNotFoundException('Can not resolve related type of ' . $ast->getId());
    }

    /**
     * @param string $fieldName
     * @param DefinitionInterface $definition
     * @return DefinitionInterface
     * @throws TypeConflictException
     */
    private function check(string $fieldName, DefinitionInterface $definition): DefinitionInterface
    {
        if ($definition instanceof ObjectTypeInterface) {
            return $definition;
        }

        if ($definition instanceof InputTypeInterface) {
            return $definition;
        }

        $name = $definition->getTypeName();

        if ($definition instanceof NamedDefinitionInterface) {
            $name = $definition->getName() . ' ' . $name;
        }

        $error = 'Schema allows only Input type or Object type for %s field, %s given.';
        throw TypeConflictException::new($error, $fieldName, $name);
    }

    /**
     * @return ObjectTypeInterface|NamedDefinitionInterface
     * @throws TypeNotFoundException
     */
    public function getQuery(): ObjectTypeInterface
    {
        if ($this->query === null) {
            throw new TypeNotFoundException('Can not resolve query data for schema');
        }

        return $this->query;
    }

    /**
     * @return null|ObjectTypeInterface|NamedDefinitionInterface
     */
    public function getMutation(): ?ObjectTypeInterface
    {
        return $this->mutation;
    }

    /**
     * @return bool
     */
    public function hasMutation(): bool
    {
        return $this->mutation !== null;
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'Schema';
    }
}
