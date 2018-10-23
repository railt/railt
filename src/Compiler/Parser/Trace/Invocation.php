<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Parser\Trace;

/**
 * Class Invocation
 */
abstract class Invocation
{
    /**
     * Rule.
     *
     * @var string
     */
    protected $rule;

    /**
     * Data.
     *
     * @var mixed
     */
    protected $data;

    /**
     * Piece of todo sequence.
     *
     * @var array
     */
    protected $todo;

    /**
     * Depth in the trace.
     *
     * @var int
     */
    protected $depth = -1;

    /**
     * Whether the rule is transitional or not (i.e. not declared in the grammar
     * but created by the analyzer).
     *
     * @var bool
     */
    protected $transitional = false;

    /**
     * @var int
     */
    private $offset = 0;

    /**
     * Constructor.
     *
     * @param string|int $rule Rule name.
     * @param mixed $data Data.
     * @param array $then Next step jumpers
     * @param int $depth Depth.
     */
    public function __construct($rule, int $data, array $then = null, int $depth = -1)
    {
        $this->rule         = $rule;
        $this->transitional = \is_int($rule);

        $this->data         = $data;
        $this->todo         = $then;
        $this->depth        = $depth;
    }

    /**
     * @param int $depth
     * @return Invocation
     */
    public function in(int $depth): self
    {
        $this->depth = $depth;

        return $this;
    }

    /**
     * @param int $data
     * @return Invocation
     */
    public function with(int $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param array $jumps
     * @return Invocation
     */
    public function then(array $jumps): self
    {
        $this->todo = $jumps;

        return $this;
    }

    /**
     * @param string|int $rule
     * @param int $data
     * @return static
     */
    public static function new($rule, int $data = 0)
    {
        return new static($rule, $data);
    }

    /**
     * @param int $offset
     * @return Invocation
     */
    public function at(int $offset): self
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * Get rule name.
     *
     * @return  string
     */
    public function getRule()
    {
        return $this->rule;
    }

    /**
     * Get data.
     *
     * @return  mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Get todo sequence.
     *
     * @return  array
     */
    public function getTodo()
    {
        return $this->todo;
    }

    /**
     * Get depth in trace.
     *
     * @return  int
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * Set depth in trace.
     *
     * @param int $depth Depth.
     * @return  int
     */
    public function setDepth($depth)
    {
        $old         = $this->depth;
        $this->depth = $depth;

        return $old;
    }

    /**
     * Check whether the rule is transitional or not.
     *
     * @return  bool
     */
    public function isTransitional()
    {
        return $this->transitional;
    }
}
