<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Reflection;

use Hoa\Compiler\Llk\TreeNode;
use Serafim\Railgun\Compiler\Exceptions\CompilerException;
use Serafim\Railgun\Compiler\Exceptions\SemanticException;
use Serafim\Railgun\Reflection\Abstraction\DefinitionInterface;
use Serafim\Railgun\Reflection\Abstraction\InputTypeInterface;
use Serafim\Railgun\Reflection\Abstraction\NamedDefinitionInterface;
use Serafim\Railgun\Reflection\Abstraction\ObjectTypeInterface;
use Serafim\Railgun\Reflection\Abstraction\SchemaTypeInterface;
use Serafim\Railgun\Reflection\Common\Directives;
use Serafim\Railgun\Reflection\Common\HasLinkingStageInterface;
use Serafim\Railgun\Reflection\Common\LinkingStage;

/**
 * Class SchemaDefinition
 * @package Serafim\Railgun\Reflection
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
     * @throws SemanticException
     */
    public function compile(Document $document, TreeNode $ast): ?TreeNode
    {
        $name = $ast->getId();

        switch ($name) {
            case '#Query':
                $type = $ast->getChild(0)->getChild(0)->getValueValue();
                $this->query = $this->check('query', $document->load($type));
                break;

            case '#Mutation':
                $type = $ast->getChild(0)->getChild(0)->getValueValue();
                $this->mutation = $this->check('query', $document->load($type));
                break;
        }

        return $ast;
    }

    /**
     * @param string $fieldName
     * @param DefinitionInterface $definition
     * @return DefinitionInterface
     * @throws SemanticException
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
        throw new SemanticException(sprintf($error, $fieldName, $name));
    }

    /**
     * @return ObjectTypeInterface|NamedDefinitionInterface
     * @throws CompilerException
     */
    public function getQuery(): ObjectTypeInterface
    {
        if ($this->query === null) {
            throw new CompilerException('Internal error: Can not resolve query data for schema');
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
