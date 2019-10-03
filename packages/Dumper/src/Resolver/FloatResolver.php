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
 * Class FloatResolver
 */
class FloatResolver extends Resolver
{
    /**
     * @var string
     */
    protected const FLOAT_NAN = 'NaN';

    /**
     * @var string
     */
    protected const FLOAT_POS_INF = 'Infinity';

    /**
     * @var string
     */
    protected const FLOAT_NEG_INF = '-Infinity';

    /**
     * @param mixed $value
     * @return bool
     */
    public function match($value): bool
    {
        return \is_float($value);
    }

    /**
     * @param mixed $value
     * @return string
     */
    public function type($value): string
    {
        return 'float';
    }

    /**
     * @param mixed $value
     * @return string
     */
    public function value($value): string
    {
        switch (true) {
            case $value === \NAN:
                return static::FLOAT_NAN;

            case \is_infinite($value):
                return $value > 0 ? static::FLOAT_POS_INF : static::FLOAT_NEG_INF;

            default:
                return \rtrim(\sprintf('%F', $value), '.0');
        }
    }
}
