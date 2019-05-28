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
 * Interface ProvideRelatedFields
 */
interface ProvideRelatedFields
{
    /**
     * @param int $depth
     * @return array|string[]
     */
    public function getRelations(int $depth = 0): array;

    /**
     * @param string $field
     * @param \Closure|null $then
     * @return bool
     */
    public function wants(string $field, \Closure $then = null): bool;
}
