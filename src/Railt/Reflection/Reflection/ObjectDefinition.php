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
use Railt\Reflection\Contracts\InterfaceTypeInterface;
use Railt\Reflection\Contracts\ObjectTypeInterface;
use Railt\Reflection\Reflection\Common\Directives;
use Railt\Reflection\Reflection\Common\Fields;
use Railt\Reflection\Reflection\Common\HasDescription;
use Railt\Reflection\Reflection\Common\HasLinkingStageInterface;
use Railt\Reflection\Reflection\Common\HasName;
use Railt\Reflection\Reflection\Common\LinkingStage;

/**
 * Class ObjectDefinition
 * @package Railt\Reflection\Reflection
 */
class ObjectDefinition extends Definition implements
    ObjectTypeInterface,
    HasLinkingStageInterface
{
    use HasName;
    use Fields;
    use Directives;
    use LinkingStage;
    use HasDescription;

    /**
     * @var array|InterfaceTypeInterface[]
     */
    private $interfaces = [];

    /**
     * @param Document $document
     * @param TreeNode $ast
     * @return TreeNode|null
     * @throws \LogicException
     * @throws \Railt\Support\Exceptions\NotReadableException
     * @throws \Railt\Parser\Exceptions\UnrecognizedTokenException
     * @throws \Railt\Reflection\Exceptions\TypeConflictException
     * @throws \Railt\Reflection\Exceptions\TypeNotFoundException
     * @throws \Railt\Reflection\Exceptions\UnrecognizedNodeException
     */
    public function compile(Document $document, TreeNode $ast): ?TreeNode
    {
        switch ($ast->getId()) {
            case '#Implements':
                /** @var TreeNode $child */
                foreach ($ast->getChildren() as $child) {
                    $name                    = $child->getChild(0)->getValueValue();
                    $this->interfaces[$name] = $document->load($name);
                }
        }

        return $ast;
    }

    /**
     * @return iterable
     */
    public function getInterfaces(): iterable
    {
        return array_values($this->interfaces);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasInterface(string $name): bool
    {
        return array_key_exists($name, $this->interfaces);
    }

    /**
     * @param string $name
     * @return null|InterfaceTypeInterface
     */
    public function getInterface(string $name): ?InterfaceTypeInterface
    {
        return $this->interfaces[$name] ?? null;
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'Object';
    }
}
