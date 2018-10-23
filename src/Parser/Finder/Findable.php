<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Finder;

use Railt\Parser\Ast\NodeInterface;
use Railt\Parser\Finder;

/**
 * Interface Findable
 */
interface Findable
{
    /**
     * @param string $query
     * @param int|null $depth
     * @return Finder
     */
    public function find(string $query, int $depth = null): Finder;

    /**
     * @param string $query
     * @param int|null $depth
     * @return null|NodeInterface
     */
    public function first(string $query, int $depth = null): ?NodeInterface;
}
