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
use Railt\Reflection\Contracts\EnumTypeInterface;
use Railt\Reflection\Contracts\EnumValueInterface;
use Railt\Reflection\Exceptions\NotImplementedException;
use Railt\Reflection\Reflection\Common\Directives;
use Railt\Reflection\Reflection\Common\HasDescription;
use Railt\Reflection\Reflection\Common\HasLinkingStageInterface;
use Railt\Reflection\Reflection\Common\HasName;
use Railt\Reflection\Reflection\Common\LinkingStage;

/**
 * Class EnumDefinition
 * @package Railt\Reflection\Reflection
 */
class EnumDefinition extends Definition implements
    EnumTypeInterface,
    HasLinkingStageInterface
{
    use HasName;
    use Directives;
    use LinkingStage;
    use HasDescription;

    /**
     * @var array
     */
    private $values = [];

    /**
     * @param Document $document
     * @param TreeNode $ast
     * @return TreeNode|null
     * @throws \LogicException
     */
    public function compile(Document $document, TreeNode $ast): ?TreeNode
    {
        switch ($ast->getId()) {
            case '#Value':
                $value = new EnumValue($document, $ast, $this);
                $this->values[$value->getValue()] = $value;
        }

        return $ast;
    }

    /**
     * @return iterable|EnumValue[]
     */
    public function getValues(): iterable
    {
        return array_values($this->values);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasValue(string $name): bool
    {
        return array_key_exists($name, $this->values);
    }

    /**
     * @param string $name
     * @return null|EnumValueInterface
     */
    public function getValue(string $name): ?EnumValueInterface
    {
        return $this->values[$name] ?? null;
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'Enum';
    }
}
