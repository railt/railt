<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Rule;

/**
 * Class Repetition
 */
class Repetition extends Rule
{
    /**
     * Minimum bound.
     * @var int
     */
    protected $min = 0;

    /**
     * Maximum bound.
     * @var int
     */
    protected $max = 0;

    /**
     * Repetition constructor.
     *
     * @param string|int $name Rule name.
     * @param int $min Minimum bound.
     * @param int $max Maximum bound.
     * @param mixed $children Children.
     * @param string|null $nodeId Node ID.
     */
    public function __construct($name, $min, $max, $children, string $nodeId = null)
    {
        $this->min = \max(0, (int)$min);
        $this->max = \max(-1, (int)$max);

        parent::__construct($name, $children, $nodeId);

        \assert($min <= $max || $max === -1,
            \sprintf('Cannot repeat with a min (%d) greater than max (%d).', $min, $max));
    }

    /**
     * Get minimum bound.
     * @return int
     */
    public function getMin(): int
    {
        return $this->min;
    }

    /**
     * Check whether the maximum repetition is unbounded.
     * @return bool
     */
    public function isInfinite(): bool
    {
        return $this->getMax() === -1;
    }

    /**
     * Get maximum bound.
     * @return int
     */
    public function getMax(): int
    {
        return $this->max;
    }
}
