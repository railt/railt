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
class Repetition extends Rule
{
    public const INF_MAX_VALUE = -1;

    /**
     * Minimum bound.
     *
     * @var int
     */
    protected $min = 0;

    /**
     * Maximum bound.
     *
     * @var int
     */
    protected $max = 0;

    /**
     * Constructor.
     *
     * @param string $name Name.
     * @param int $min Minimum bound.
     * @param int $max Maximum bound.
     * @param mixed $children Children.
     * @param string $nodeId Node ID.
     */
    public function __construct($name, $min, $max, $children, $nodeId)
    {
        parent::__construct($name, $children, $nodeId);

        $min = \max(0, (int) $min);
        $max = \max(-1, (int) $max);

        if (-1 !== $max && $min > $max) {
            throw new \InvalidArgumentException(
                'Cannot repeat with a min (%d) greater than max (%d).',
                0,
                [$min, $max]
            );
        }

        $this->min = $min;
        $this->max = $max;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            $this->name,
            $this->min,
            $this->max,
            $this->children,
            $this->nodeId,
        ];
    }

    /**
     * Get minimum bound.
     *
     * @return  int
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * Get maximum bound.
     *
     * @return  int
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * Check whether the maximum repetition is unbounded.
     *
     * @return  bool
     */
    public function isInfinite()
    {
        return -1 === $this->getMax();
    }
}
