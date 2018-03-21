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
 * Class Invocation
 */
abstract class Invocation
{
    /**
     * Rule
     * @var string|int
     */
    protected $rule;

    /**
     * Data
     * @var mixed
     */
    protected $data;

    /**
     * Piece of future sequence
     * @var array
     */
    protected $future;

    /**
     * Depth in the trace
     * @var int
     */
    protected $depth = -1;

    /**
     * Whether the rule is transitional or not (i.e. not declared in the grammar
     * but created by the analyzer).
     * @var bool
     */
    protected $transitional;

    /**
     * Constructor.
     *
     * @param string|int $rule
     * @param mixed $data
     * @param array $future
     * @param int $depth
     */
    public function __construct($rule, $data, array $future = null, $depth = -1)
    {
        $this->rule         = $rule;
        $this->data         = $data;
        $this->future       = $future ?? [];
        $this->depth        = $depth;
        $this->transitional = \is_int($rule);
    }

    /**
     * Get rule name.
     * @return string|int
     */
    public function getRule()
    {
        return $this->rule;
    }

    /**
     * Get data.
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Get future sequence.
     * @return array
     */
    public function getTodo(): array
    {
        return $this->future;
    }

    /**
     * Get depth in trace.
     * @return int
     */
    public function getDepth(): int
    {
        return $this->depth;
    }

    /**
     * Set depth in trace.
     * @param int $depth Depth.
     * @return self|$this
     */
    public function setDepth(int $depth): self
    {
        $this->depth = $depth;

        return $this;
    }

    /**
     * Check whether the rule is transitional or not.
     * @return bool
     */
    public function isTransitional(): bool
    {
        return $this->transitional;
    }
}
