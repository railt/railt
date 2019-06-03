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
 * Trait MutableQueryTrait
 */
trait MutableQueryTrait
{
    use QueryTrait;

    /**
     * @param string $query
     * @return MutableQueryInterface|$this
     */
    public function withQuery(string $query): MutableQueryInterface
    {
        $this->query = \trim($query);

        return $this;
    }
}
