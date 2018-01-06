<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Rule;

/**
 * Class \Railt\Compiler\Rule\Invocation.
 *
 * Parent of entry and ekzit rules.
 *
 * @copyright Copyright © 2007-2017 Hoa community
 * @license New BSD License
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
    protected $depth        = -1;

    /**
     * Whether the rule is transitional or not (i.e. not declared in the grammar
     * but created by the analyzer).
     *
     * @var bool
     */
    protected $transitional = false;

    /**
     * Constructor.
     *
     * @param string $rule Rule name.
     * @param mixed $data Data.
     * @param array $todo Todo.
     * @param int $depth Depth.
     */
    public function __construct(
        $rule,
        $data,
        array $todo = null,
        $depth      = -1
    ) {
        $this->rule         = $rule;
        $this->data         = $data;
        $this->todo         = $todo;
        $this->depth        = $depth;
        $this->transitional = \is_int($rule);
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
     * Set depth in trace.
     *
     * @param int $depth Depth.
     * @return  int
     */
    public function setDepth($depth)
    {
        $old          = $this->depth;
        $this->depth  = $depth;

        return $old;
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
     * Check whether the rule is transitional or not.
     *
     * @return  bool
     */
    public function isTransitional()
    {
        return $this->transitional;
    }
}
