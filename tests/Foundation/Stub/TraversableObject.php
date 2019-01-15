<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Foundation\Stub;

/**
 * Class TraversableObject
 */
class TraversableObject implements \IteratorAggregate
{
    /**
     * @var array
     */
    private $data;

    /**
     * ArrayAccessObject constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return \Traversable
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->data);
    }
}
