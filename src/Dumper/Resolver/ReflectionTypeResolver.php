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
 * Class ReflectionTypeResolver
 */
class ReflectionTypeResolver extends Resolver
{
    /**
     * @param mixed $value
     * @return bool
     */
    public function match($value): bool
    {
        return $value instanceof \ReflectionType;
    }

    /**
     * @param \ReflectionType $type
     * @return string
     */
    public function type($type): string
    {
        return 'object';
    }

    /**
     * @param \ReflectionType $type
     * @return string
     */
    public function value($type): string
    {
        $prefix = $type->allowsNull() ? '?' : '';

        return $prefix . $type->getName();
    }
}
