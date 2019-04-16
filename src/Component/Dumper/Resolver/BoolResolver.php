<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Dumper\Resolver;

/**
 * Class BoolResolver
 */
class BoolResolver extends Resolver
{
    /**
     * @param mixed $value
     * @return bool
     */
    public function match($value): bool
    {
        return \is_bool($value);
    }

    /**
     * @param mixed $value
     * @return string
     */
    public function type($value): string
    {
        return 'bool';
    }

    /**
     * @param mixed $value
     * @return string
     */
    public function value($value): string
    {
        return $value ? 'true' : 'false';
    }
}
