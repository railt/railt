<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Parser\Rule;

/**
 * Class Repetition
 */
class Repetition extends BaseProduction
{
    public const INF_MAX_VALUE = -1;

    /**
     * @var int
     */
    protected $min;

    /**
     * @var int
     */
    protected $max;

    /**
     * Repetition constructor.
     * @param string|int $id
     * @param int $min
     * @param int $max
     * @param array $children
     * @param null|string $name
     */
    public function __construct($id, int $min, int $max = self::INF_MAX_VALUE, array $children = [], ?string $name = null)
    {
        \assert($max === self::INF_MAX_VALUE || $max >= $min,
            'Min repetition value must be less than max');

        $this->min = $min;
        $this->max = $max;

        parent::__construct($id, $children, $name);
    }

    /**
     * @return int
     */
    public function from(): int
    {
        return $this->min;
    }

    /**
     * @return int
     */
    public function to(): int
    {
        return $this->max;
    }

    /**
     * Check whether the maximum repetition is unbounded.
     *
     * @return bool
     */
    public function isInfinite(): bool
    {
        return $this->max === self::INF_MAX_VALUE;
    }
}
