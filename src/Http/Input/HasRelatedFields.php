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
     * @param int $depth
     * @return array|string[]
     */
    public function getRelations(int $depth = 0): array
    {
        return [];
    }

    /**
     * @param string $field
     * @param \Closure|null $then
     * @return bool
     */
    public function wants(string $field, \Closure $then = null): bool
    {
        $relations = $this->getRelations();

        $result = \in_array($field, $relations, true) ||
            \array_key_exists($field, $relations);

        if ($result && $then) {
            $then($this, $field);
        }

        return $result;
    }
}
