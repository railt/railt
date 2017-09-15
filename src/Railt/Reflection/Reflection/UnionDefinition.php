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
use Railt\Parser\Exceptions\UnrecognizedTokenException;
use Railt\Reflection\Abstraction\NamedDefinitionInterface;
use Railt\Reflection\Abstraction\UnionTypeInterface;
use Railt\Reflection\Exceptions\TypeConflictException;
use Railt\Reflection\Exceptions\TypeNotFoundException;
use Railt\Reflection\Exceptions\UnrecognizedNodeException;
use Railt\Reflection\Reflection\Common\Directives;
use Railt\Reflection\Reflection\Common\HasDescription;
use Railt\Reflection\Reflection\Common\HasLinkingStageInterface;
use Railt\Reflection\Reflection\Common\HasName;
use Railt\Reflection\Reflection\Common\LinkingStage;

/**
 * Class UnionDefinition
 * @package Railt\Reflection\Reflection
 */
class UnionDefinition extends Definition implements
    UnionTypeInterface,
    HasLinkingStageInterface
{
    use HasName;
    use Directives;
    use LinkingStage;
    use HasDescription;

    /**
     * @var array
     */
    private $relations = [];

    /**
     * @param Document $document
     * @param TreeNode $ast
     * @return TreeNode|null
     * @throws NotReadableException
     * @throws TypeNotFoundException
     * @throws \LogicException
     * @throws UnrecognizedTokenException
     * @throws TypeConflictException
     * @throws UnrecognizedNodeException
     */
    public function compile(Document $document, TreeNode $ast): ?TreeNode
    {
        if ($ast->getId() === '#Relations') {
            /** @var TreeNode $child */
            foreach ($ast->getChildren() as $child) {
                $name = $child->getChild(0)->getValueValue();
                $this->relations[$name] = $document->load($name);
            }
        }

        return $ast;
    }

    /**
     * @return iterable
     */
    public function getTypes(): iterable
    {
        return array_values($this->relations);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasType(string $name): bool
    {
        return array_key_exists($name, $this->relations);
    }

    /**
     * @param string $name
     * @return null|NamedDefinitionInterface
     */
    public function getType(string $name): ?NamedDefinitionInterface
    {
        return $this->relations[$name] ?? null;
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'Union';
    }
}
