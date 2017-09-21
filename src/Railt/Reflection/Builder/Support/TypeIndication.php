<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Builder\Support;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Reflection\Contracts\Behavior\AllowsTypeIndication;
use Railt\Reflection\Contracts\Behavior\Inputable;
use Railt\Reflection\Contracts\Types\NamedTypeInterface;
use Railt\Reflection\Contracts\Types\TypeInterface;
use Railt\Reflection\Exceptions\TypeConflictException;

/**
 * Trait TypeIndication
 * @mixin AllowsTypeIndication
 */
trait TypeIndication
{
    /**
     * @var string
     */
    private $typeName;

    /**
     * @var bool
     */
    private $isNonNull = false;

    /**
     * @var bool
     */
    private $isList = false;

    /**
     * @var bool
     */
    private $isNonNullList = false;

    /**
     * @return Inputable
     * @throws TypeConflictException
     */
    public function getType(): Inputable
    {
        \assert($this->typeName !== null, 'Broken AST, #Type node required');

        /** @var NamedTypeInterface $type */
        $type = $this->getCompiler()->get($this->typeName);

        if ($type instanceof Inputable) {
            return $type;
        }

        $error = 'Type must be an any inputable type (Enum, Input or Scalar), but %s<%s> defined.';
        throw new TypeConflictException(\sprintf($error, $type->getTypeName(), $type->getName()));
    }

    /**
     * @return bool
     */
    public function isList(): bool
    {
        return $this->compiled()->isList;
    }

    /**
     * @return bool
     */
    public function isNonNull(): bool
    {
        return $this->compiled()->isNonNull;
    }

    /**
     * @return bool
     */
    public function isNonNullList(): bool
    {
        return $this->compiled()->isList && $this->compiled()->isNonNullList;
    }

    /**
     * @param TreeNode $ast
     * @return bool
     */
    protected function compileTypeIndication(TreeNode $ast): bool
    {
        switch ($ast->getId()) {
            case '#Type':
                return $this->buildType($ast);

            case '#List':
                return $this->buildList($ast);
        }

        return false;
    }

    /**
     * @param TreeNode $ast
     * @return bool
     */
    private function buildType(TreeNode $ast): bool
    {
        /** @var TreeNode $child */
        foreach ($ast->getChildren() as $child) {
            if ($child->getValueToken() === 'T_NON_NULL') {
                $this->isNonNull = true;
            } else {
                $this->typeName = $child->getValueValue();
            }
        }

        return true;
    }

    /**
     * @param TreeNode $ast
     * @return bool
     */
    private function buildList(TreeNode $ast): bool
    {
        $this->isList = true;

        /** @var TreeNode $child */
        foreach ($ast->getChildren() as $child) {
            if ($child->getId() === '#Type') {
                $this->buildType($child);
                continue;
            }

            if ($child->getValueToken() === 'T_NON_NULL') {
                $this->isNonNullList = true;
            }
        }

        return true;
    }
}
