<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Reflection;

use Hoa\Compiler\Llk\TreeNode;
use Railgun\Exceptions\IndeterminateBehaviorException;
use Railgun\Exceptions\SemanticException;
use Railgun\Reflection\Abstraction\DefinitionInterface;
use Railgun\Reflection\Abstraction\InputTypeInterface;
use Railgun\Reflection\Abstraction\NamedDefinitionInterface;
use Railgun\Reflection\Abstraction\ObjectTypeInterface;
use Railgun\Reflection\Abstraction\SchemaTypeInterface;
use Railgun\Reflection\Common\Directives;
use Railgun\Reflection\Common\HasLinkingStageInterface;
use Railgun\Reflection\Common\LinkingStage;

/**
 * Class SchemaDefinition
 * @package Railgun\Reflection
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
     * @throws \Railgun\Exceptions\UnexpectedTokenException
     * @throws \Railgun\Exceptions\UnrecognizedTokenException
     */
    public function compile(Document $document, TreeNode $ast): ?TreeNode
    {
        $name = $ast->getId();

        switch ($name) {
            case '#Query':
                $type = $this->getRelatedType($ast)->getValueValue();
                $this->query = $this->check('query', $document->load($type));
                break;

            case '#Mutation':
                $type = $this->getRelatedType($ast)->getValueValue();
                $this->mutation = $this->check('query', $document->load($type));
                break;
        }

        return $ast;
    }

    /**
     * @param TreeNode $ast
     * @return TreeNode
     * @throws \RuntimeException
     */
    private function getRelatedType(TreeNode $ast): TreeNode
    {
        /** @var TreeNode $node */
        foreach ($ast->getChildren() as $node) {
            if ($node->getId() === '#Type') {
                return $node->getChild(0);
            }
        }

        throw new \RuntimeException('Can not resolve related type of ' . $ast->getId());
    }

    /**
     * @param string $fieldName
     * @param DefinitionInterface $definition
     * @return DefinitionInterface
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
        throw SemanticException::new($error, $fieldName, $name);
    }

    /**
     * @return ObjectTypeInterface|NamedDefinitionInterface
     */
    public function getQuery(): ObjectTypeInterface
    {
        if ($this->query === null) {
            IndeterminateBehaviorException::new('Can not resolve query data for schema');
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
