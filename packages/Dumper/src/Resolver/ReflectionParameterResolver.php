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
 * Class ReflectionParameterResolver
 */
class ReflectionParameterResolver extends Resolver implements SelfDisplayed
{
    /**
     * @param mixed $value
     * @return bool
     */
    public function match($value): bool
    {
        return $value instanceof \ReflectionParameter;
    }

    /**
     * @param \ReflectionParameter $parameter
     * @return string
     */
    public function type($parameter): string
    {
        $prefix = $parameter->allowsNull() ? '?' : '';
        $type = $parameter->getType();

        return $prefix . ($type ? $type->getName() : 'mixed');
    }

    /**
     * @param \ReflectionParameter $parameter
     * @return string
     * @throws \ReflectionException
     */
    public function value($parameter): string
    {
        $result = $parameter->isVariadic() ? '...' : '';
        $result .= '$' . $parameter->getName();

        if ($parameter->isDefaultValueAvailable()) {
            $result .= ' = ' . $this->dumper->value($parameter->getDefaultValue());
        }

        return $result;
    }

    /**
     * @param string $type
     * @param string $value
     * @return string
     */
    public function render(string $type, string $value): string
    {
        return $type . ' ' . $value;
    }
}
