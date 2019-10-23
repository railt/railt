<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Tests\Feature\FeatureContext\Support;

/**
 * Trait NumericalTrait
 */
trait NumericalTrait
{
    /**
     * @param string|int $numerical
     * @return mixed
     */
    protected function number($numerical): int
    {
        if (\is_numeric($numerical)) {
            return (int)$numerical;
        }

        switch (\strtolower(\trim((string)$numerical))) {
            case 'none':
            case 'no':
            case 'zero':
            case 'empty':
                return 0;

            case 'one':
            case 'first':
            case 'once':
                return 1;

            case 'two':
            case 'second':
            case 'twice':
                return 2;

            case 'three':
            case 'third':
                return 3;

            case 'four':
            case 'fourth':
                return 4;

            case 'five':
            case 'fifth':
                return 5;

            case 'six':
            case 'sixth':
                return 6;

            case 'seven':
            case 'seventh':
                return 7;

            case 'eight':
            case 'eighth':
                return 8;

            case 'nine':
            case 'ninth':
                return 9;

            case 'ten':
            case 'tenth':
                return 10;

            default:
                throw new \InvalidArgumentException('Only [one...ten] interval is allowed, but ' . $numerical . ' given');
        }
    }
}
