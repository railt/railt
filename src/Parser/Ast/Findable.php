<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Ast;

/**
 * Interface Findable
 */
interface Findable
{
    /**
     * @param string $name
     * @param int|null $depth
     * @return iterable
     */
    public function find(string $name, int $depth = null): iterable;

    /**
     * @param string $name
     * @param int|null $depth
     * @return null|NodeInterface
     */
    public function first(string $name, int $depth = null): ?NodeInterface;
}
