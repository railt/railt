<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Reflection\Abstraction\NamedDefinitionInterface;
use Railt\Reflection\Abstraction\UnionTypeInterface;
use Railt\Reflection\Common\Directives;
use Railt\Reflection\Common\HasLinkingStageInterface;
use Railt\Reflection\Common\HasName;
use Railt\Reflection\Common\LinkingStage;

/**
 * Class UnionDefinition
 * @package Railt\Reflection
 */
class UnionDefinition extends Definition implements
    UnionTypeInterface,
    HasLinkingStageInterface
{
    use HasName;
    use Directives;
    use LinkingStage;

    /**
     * @var array
     */
    private $relations = [];

    /**
     * @param Document $document
     * @param TreeNode $ast
     * @return TreeNode|null
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
