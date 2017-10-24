<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Base\Dependent\Field;

use Railt\Compiler\Reflection\Base\Resolving;
use Railt\Compiler\Reflection\Contracts\Dependent\Field\HasFields;
use Railt\Compiler\Reflection\Contracts\Dependent\FieldDefinition;

/**
 * Trait BaseFieldsContainer
 * @mixin HasFields
 * @mixin Resolving
 */
trait BaseFieldsContainer
{
    /**
     * @var array
     */
    protected $fields = [];

    /**
     * @return iterable|FieldDefinition[]
     */
    public function getFields(): iterable
    {
        return \array_values($this->resolve()->fields);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasField(string $name): bool
    {
        return \array_key_exists($name, $this->resolve()->fields);
    }

    /**
     * @param string $name
     * @return null|FieldDefinition
     */
    public function getField(string $name): ?FieldDefinition
    {
        return $this->resolve()->fields[$name] ?? null;
    }

    /**
     * @return int
     */
    public function getNumberOfFields(): int
    {
        return \count($this->resolve()->fields);
    }
}
