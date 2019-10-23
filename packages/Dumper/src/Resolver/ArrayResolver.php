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
 * Class ArrayResolver
 */
class ArrayResolver extends Resolver
{
    /**
     * @param mixed $value
     * @return bool
     */
    public function match($value): bool
    {
        return \is_array($value);
    }

    /**
     * @param mixed $value
     * @return string
     */
    public function type($value): string
    {
        return 'array';
    }

    /**
     * @param mixed $array
     * @return string
     */
    public function value($array): string
    {
        [$result, $i] = [[], 0];

        foreach ($array as $key => $value) {
            if ($i >= 2) {
                $result[] = '… +' . (\count($array) - 2);
                break;
            }

            $result[] = $this->dumper->value($key) . ' => ' . $this->dumper->dump($value);
            ++$i;
        }

        return '[' . \implode(', ', $result) . ']';
    }
}
