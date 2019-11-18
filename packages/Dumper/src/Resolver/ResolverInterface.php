<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Dumper\Resolver;

use Railt\Dumper\TypeDumperInterface;

/**
 * Interface ResolverInterface
 */
interface ResolverInterface
{
    /**
     * @param mixed $value
     * @return bool
     */
    public function match($value): bool;

    /**
     * @param TypeDumperInterface|string $dumper
     * @param mixed $value
     * @return string
     */
    public function type($value): string;

    /**
     * @param TypeDumperInterface|string $dumper
     * @param mixed $value
     * @return string
     */
    public function value($value): string;
}
