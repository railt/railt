<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Ast;

use Phplrt\Contracts\Ast\NodeInterface;

/**
 * Trait ResolvableTrait
 */
trait ResolvableTrait
{
    /**
     * @param array|NodeInterface[] $children
     * @return self
     * @throws \LogicException
     */
    public static function resolve(array $children): self
    {
        if ($result = self::find($children)) {
            return $result;
        }

        throw new \LogicException(static::class . ' not resolvable');
    }

    /**
     * @param array|NodeInterface[] $children
     * @return self|null
     * @throws \LogicException
     */
    public static function find(array $children): ?self
    {
        foreach ($children as $name) {
            if ($name instanceof static) {
                return $name;
            }
        }

        return null;
    }
}
