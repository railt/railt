<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Base\Containers;

use Railt\Reflection\Contracts\Containers\HasFields;
use Railt\Reflection\Contracts\Types\FieldType;

/**
 * Trait BaseFieldsContainer
 * @mixin HasFields
 */
trait BaseFieldsContainer
{
    /**
     * @var array
     */
    protected $fields = [];

    /**
     * @return iterable|FieldType[]
     */
    public function getFields(): iterable
    {
        return \array_values($this->resolve()->fields);
    }

    /**
     * @param FieldType $field
     * @return void
     */
    public function addField(FieldType $field): void
    {
        $this->fields[$field->getName()] = $field;
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
     * @return null|FieldType
     */
    public function getField(string $name): ?FieldType
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
