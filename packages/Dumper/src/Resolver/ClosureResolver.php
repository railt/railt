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
 * Class ClosureResolver
 */
class ClosureResolver extends Resolver implements SelfDisplayed
{
    /**
     * @param mixed $value
     * @return bool
     */
    public function match($value): bool
    {
        return $value instanceof \Closure;
    }

    /**
     * @param mixed $value
     * @return string
     */
    public function type($value): string
    {
        return 'fn';
    }

    /**
     * @param string $type
     * @param string $value
     * @return string
     */
    public function render(string $type, string $value): string
    {
        return $type . $value;
    }

    /**
     * @param mixed $value
     * @return string
     * @throws \ReflectionException
     */
    public function value($value): string
    {
        $reflection = new \ReflectionFunction($value);

        $type = $reflection->getReturnType();
        $suffix = $type ? $this->dumper->value($type) : 'mixed';

        return \sprintf('(%s): %s', $this->arguments($reflection), $suffix);
    }

    /**
     * @param \ReflectionFunction $fn
     * @return string
     */
    private function arguments(\ReflectionFunction $fn): string
    {
        $result = [];

        foreach ($fn->getParameters() as $parameter) {
            $result[] = $this->dumper->type($parameter) . ' ' . $this->dumper->value($parameter);
        }

        return \count($result) ? \implode(', ', $result) : '';
    }
}
