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
class Repetition extends Rule implements Renderable
{
    public const INF_MAX_VALUE = -1;

    /**
     * Minimum bound
     * @var int
     */
    protected $min = 0;

    /**
     * Maximum bound
     * @var int
     */
    protected $max = 0;

    /**
     * Constructor.
     *
     * @param string $name Name
     * @param int $min Minimum bound
     * @param int $max Maximum bound
     * @param mixed $children Children
     * @param string|null $nodeId
     * @throws \InvalidArgumentException
     */
    public function __construct($name, $min, $max, $children, string $nodeId = null)
    {
        parent::__construct($name, $children, $nodeId);

        $this->min = \max(0, (int)$min);
        $this->max = \max(-1, (int)$max);

        \assert($this->validate($this->min, $this->max));
    }

    /**
     * @param int $min
     * @param int $max
     * @return bool
     * @throws \InvalidArgumentException
     */
    private function validate(int $min, int $max): bool
    {
        if ($max !== self::INF_MAX_VALUE && $min > $max) {
            $error = \sprintf('Cannot repeat with a min (%d) greater than max (%d).', $min, $max);
            throw new \InvalidArgumentException($error);
        }

        return true;
    }

    /**
     * @return array
     */
    public function args(): array
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
     * Get minimum bound
     * @return int
     */
    public function getMin(): int
    {
        return $this->min;
    }

    /**
     * Check whether the maximum repetition is unbounded
     * @return bool
     */
    public function isInfinite(): bool
    {
        return $this->getMax() === self::INF_MAX_VALUE;
    }

    /**
     * Get maximum bound
     * @return int
     */
    public function getMax(): int
    {
        return $this->max;
    }
}
