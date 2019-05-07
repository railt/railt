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
 * Class IteratorResolver
 */
class IteratorResolver extends ObjectResolver
{
    /**
     * @param mixed $value
     * @return bool
     */
    public function match($value): bool
    {
        if ($value instanceof \IteratorAggregate) {
            return ! $value->getIterator() instanceof \Generator;
        }

        return $value instanceof \Traversable;
    }

    /**
     * @param \Traversable $iterator
     * @return string
     */
    public function value($iterator): string
    {
        return parent::value($iterator) . $this->resolveIteratorSuffix($iterator);
    }

    /**
     * @param iterable $iterator
     * @return string
     */
    private function resolveIteratorSuffix(iterable $iterator): string
    {
        $keys = $values = [];

        foreach ($iterator as $key => $value) {
            $keys[] = $this->dumper->type($key);
            $keys = \array_unique($keys);

            $values[] = $this->dumper->type($value);
            $values = \array_unique($values);
        }

        $keysString = $this->keys($keys);

        return $keysString
            ? \sprintf('<%s,%s>', $keysString, $this->values($values))
            : \sprintf('<%s>', $this->values($values));
    }

    /**
     * @param array $keys
     * @return string
     */
    private function keys(array $keys): string
    {
        if (\count($keys) >= 3) {
            return 'mixed';
        }

        $result = \implode('|', $keys);

        return $result === 'int' ? '' : $result;
    }

    /**
     * @param array $values
     * @return string
     */
    private function values(array $values): string
    {
        if (\count($values) >= 3) {
            return 'mixed';
        }

        return \implode('|', $values);
    }
}
