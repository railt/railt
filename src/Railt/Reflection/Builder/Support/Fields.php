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
use Railt\Reflection\Builder\FieldBuilder;
use Railt\Reflection\Contracts\Behavior\Nameable;
use Railt\Reflection\Contracts\Types\FieldType;
use Railt\Reflection\Contracts\Containers\HasFields;

/**
 * Trait Fields
 * @mixin HasFields
 */
trait Fields
{
    /**
     * @var array
     */
    private $fields = [];

    /**
     * @param TreeNode $ast
     * @return bool
     * @throws \Railt\Reflection\Exceptions\BuildingException
     */
    protected function compileFields(TreeNode $ast): bool
    {
        /** @var Nameable $this */
        if ($ast->getId() === '#Field') {
            $field = new FieldBuilder($ast, $this->getDocument(), $this);
            $this->fields[$field->getName()] = $field;
            return true;
        }

        return false;
    }

    /**
     * @return iterable|FieldType[]
     */
    public function getFields(): iterable
    {
        return \array_values($this->compiled()->fields);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasField(string $name): bool
    {
        return \array_key_exists($name, $this->compiled()->fields);
    }

    /**
     * @param string $name
     * @return null|FieldType
     */
    public function getField(string $name): ?FieldType
    {
        return $this->compiled()->fields[$name] ?? null;
    }

    /**
     * @return int
     */
    public function getNumberOfFields(): int
    {
        return \count($this->compiled()->fields);
    }
}
