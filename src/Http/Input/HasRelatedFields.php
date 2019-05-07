<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Input;

/**
 * Trait HasRelatedFields
 *
 * @mixin ProvideRelatedFields
 */
trait HasRelatedFields
{
    /**
     * @var array|string[]
     */
    protected $relations = [];

    /**
     * @return array|string[]
     */
    public function getRelatedFields(): array
    {
        return $this->relations;
    }

    /**
     * @param array $fields
     * @return ProvideRelatedFields|$this
     */
    public function withRelatedFields(array $fields): ProvideRelatedFields
    {
        $this->relations = \array_unique(\array_merge($this->relations, $fields));

        return $this;
    }

    /**
     * @param array $fields
     * @return ProvideRelatedFields|$this
     */
    public function setRelatedFields(array $fields): ProvideRelatedFields
    {
        $this->relations = $fields;

        return $this;
    }

    /**
     * @param string $field
     * @return bool
     */
    public function wants(string $field): bool
    {
        return \in_array($field, $this->relations, true);
    }
}
