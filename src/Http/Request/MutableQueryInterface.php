<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Request;

/**
 * Interface MutableQueryInterface
 */
interface MutableQueryInterface extends QueryInterface
{
    /**
     * @param string $query
     * @return MutableQueryInterface|$this
     */
    public function withQuery(string $query): self;
}
