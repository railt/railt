<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Discovery\Composer;

/**
 * Class ArrayWalker
 */
class ArrayWalker
{
    /**
     * @var \Closure
     */
    private $filter;

    /**
     * ArrayWalker constructor.
     *
     * @param \Closure $filter
     */
    public function __construct(\Closure $filter)
    {
        $this->filter = $filter;
    }

    /**
     * @param array $data
     * @param array $current
     * @return array
     */
    public function filter(array $data, array $current = []): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            if (\is_int($key)) {
                if (! $this->filterInt($value, $current)) {
                    continue;
                }

                $result[] = \is_array($value) ? $this->filter($value, $current) : $value;
                continue;
            }

            if (! $this->filterString((string)$key, $value, $current)) {
                continue;
            }

            $result[$key] = \is_array($value)
                ? $this->filter($value, \array_merge($current, [(string)$key]))
                : $value;
        }

        return $result;
    }

    /**
     * @param mixed $value
     * @param array $current
     * @return bool
     */
    private function filterInt($value, array $current): bool
    {
        return \is_scalar($value) && ($this->filter)(\array_merge($current, [(string)$value]));
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param array $current
     * @return bool
     */
    private function filterString(string $key, $value, array $current): bool
    {
        if (! ($this->filter)(\array_merge($current, [$key]))) {
            return false;
        }

        if (\is_scalar($value) && ! ($this->filter)(\array_merge($current, [$value]))) {
            return false;
        }

        return true;
    }
}
