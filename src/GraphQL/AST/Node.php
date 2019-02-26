<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\AST;

use Railt\GraphQL\Exception\InternalErrorException;

/**
 * Class Node
 * @internal This type is generated using a parser, please do not use it inside your application code.
 */
abstract class Node
{
    /**
     * @var int
     */
    public $offset = 0;

    /**
     * Node constructor.
     * @param string $name
     * @param array $children
     * @param int $offset
     * @throws InternalErrorException
     */
    public function __construct(string $name, array $children = [], int $offset = 0)
    {
        $this->offset = $offset;

        foreach ($this->runProxies($children) as $node => $status) {
            if ($status === false) {
                $error = \sprintf('Unhandled rule %s in %s was found', \get_class($node), \get_class($this));
                throw new InternalErrorException($error);
            }
        }
    }

    /**
     * @param array $children
     * @return iterable|bool[]
     */
    private function runProxies(array $children): iterable
    {
        $proxies = \trait_constructors(static::class, 'each');

        foreach ($children as $child) {
            foreach ($proxies as $method) {
                if ($this->$method($child)) {
                    continue 2;
                }
            }

            yield $child => $this->each($child);
        }
    }

    /**
     * @param mixed $value
     * @return bool
     */
    protected function each($value): bool
    {
        return false;
    }
}
