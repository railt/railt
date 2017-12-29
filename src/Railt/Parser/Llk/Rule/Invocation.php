<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Llk\Rule;

/**
 * Class \Railt\Parser\Llk\Rule\Invocation.
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
    protected $_rule;

    /**
     * Data.
     *
     * @var mixed
     */
    protected $_data;

    /**
     * Piece of todo sequence.
     *
     * @var array
     */
    protected $_todo;

    /**
     * Depth in the trace.
     *
     * @var int
     */
    protected $_depth        = -1;

    /**
     * Whether the rule is transitional or not (i.e. not declared in the grammar
     * but created by the analyzer).
     *
     * @var bool
     */
    protected $_transitional = false;

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
        $this->_rule         = $rule;
        $this->_data         = $data;
        $this->_todo         = $todo;
        $this->_depth        = $depth;
        $this->_transitional = \is_int($rule);
    }

    /**
     * Get rule name.
     *
     * @return  string
     */
    public function getRule()
    {
        return $this->_rule;
    }

    /**
     * Get data.
     *
     * @return  mixed
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * Get todo sequence.
     *
     * @return  array
     */
    public function getTodo()
    {
        return $this->_todo;
    }

    /**
     * Set depth in trace.
     *
     * @param int $depth Depth.
     * @return  int
     */
    public function setDepth($depth)
    {
        $old          = $this->_depth;
        $this->_depth = $depth;

        return $old;
    }

    /**
     * Get depth in trace.
     *
     * @return  int
     */
    public function getDepth()
    {
        return $this->_depth;
    }

    /**
     * Check whether the rule is transitional or not.
     *
     * @return  bool
     */
    public function isTransitional()
    {
        return $this->_transitional;
    }
}
