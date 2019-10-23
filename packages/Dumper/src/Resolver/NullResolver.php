<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Dumper\Resolver;

/**
 * Class NullResolver
 */
class NullResolver extends Resolver
{
    /**
     * @var string
     */
    protected const PATTERN_NULL = 'null';

    /**
     * @param mixed $value
     * @return bool
     */
    public function match($value): bool
    {
        return $value === null;
    }

    /**
     * @param mixed $value
     * @return string
     */
    public function type($value): string
    {
        return 'null';
    }

    /**
     * @param mixed $value
     * @return string
     */
    public function value($value): string
    {
        return 'null';
    }
}
