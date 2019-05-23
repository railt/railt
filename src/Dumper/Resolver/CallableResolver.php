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
 * Class CallableResolver
 */
class CallableResolver extends ClosureResolver
{
    /**
     * @param mixed $value
     * @return bool
     */
    public function match($value): bool
    {
        return \is_callable($value);
    }

    /**
     * @param mixed $value
     * @return string
     */
    public function type($value): string
    {
        return 'callable';
    }

    /**
     * @param mixed $value
     * @return string
     * @throws \ReflectionException
     */
    public function value($value): string
    {
        $prefix = $this->getCallableName($value);

        return $prefix . parent::value(\Closure::fromCallable($value));
    }

    /**
     * @param mixed $value
     * @return string
     */
    private function getCallableName($value): string
    {
        switch (true) {
            case \is_string($value):
                return $value;

            case \is_array($value) && \is_object($value[0]) && \count($value) === 1:
                return \get_class($value[0]);

            case \is_array($value) && \is_object($value[0]) && \count($value) === 2:
                return \get_class($value[0]) . '@' . $value[1];

            case \is_array($value) && \is_string($value[0]):
                return $value[0] . '::' . $value[1];

            case \is_object($value):
                return \get_class($value);

            default:
                return $this->dumper->dump($value);
        }
    }
}
