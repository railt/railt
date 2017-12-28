<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Llk\Rule;

use Railt\Parser;
use Railt\Parser\Exception\RuleException;

/**
 * Class \Railt\Parser\Llk\Rule\Repetition.
 *
 * The repetition rule.
 *
 * @copyright Copyright © 2007-2017 Hoa community
 * @license New BSD License
 */
class Repetition extends Rule
{
    /**
     * Minimum bound.
     *
     * @var int
     */
    protected $_min = 0;

    /**
     * Maximum bound.
     *
     * @var int
     */
    protected $_max = 0;

    /**
     * Constructor.
     *
     * @param string $name Name.
     * @param int $min Minimum bound.
     * @param int $max Maximum bound.
     * @param mixed $children Children.
     * @param string $nodeId Node ID.
     * @throws \Railt\Parser\Exception\RuleException
     */
    public function __construct($name, $min, $max, $children, $nodeId)
    {
        parent::__construct($name, $children, $nodeId);

        $min = \max(0, (int) $min);
        $max = \max(-1, (int) $max);

        if (-1 !== $max && $min > $max) {
            throw new RuleException(
                'Cannot repeat with a min (%d) greater than max (%d).',
                0,
                [$min, $max]
            );
        }

        $this->_min = $min;
        $this->_max = $max;
    }

    /**
     * Get minimum bound.
     *
     * @return int
     */
    public function getMin()
    {
        return $this->_min;
    }

    /**
     * Get maximum bound.
     *
     * @return int
     */
    public function getMax()
    {
        return $this->_max;
    }

    /**
     * Check whether the maximum repetition is unbounded.
     *
     * @return bool
     */
    public function isInfinite()
    {
        return -1 === $this->getMax();
    }
}
