<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Hoa\Compiler\Llk\Sampler;

use Hoa\Compiler;
use Hoa\Compiler\Exception\Exception;
use Hoa\Compiler\Llk\Parser;
use Hoa\Compiler\Llk\Rule\Choice;
use Hoa\Compiler\Llk\Rule\Concatenation;
use Hoa\Compiler\Llk\Rule\Ekzit;
use Hoa\Compiler\Llk\Rule\Entry;
use Hoa\Compiler\Llk\Rule\Repetition;
use Hoa\Compiler\Llk\Rule\Rule;
use Hoa\Compiler\Llk\Rule\Token;
use Hoa\Iterator;
use Hoa\Visitor;
use Hoa\Visitor\Visit;

/**
 * Class \Hoa\Compiler\Llk\Sampler\BoundedExhaustive.
 *
 * This generator aims at producing all possible data (exhaustive) up to a given
 * size n (bounded).
 * This algorithm is based on multiset (set with repetition).
 * Repetition unfolding: upper bound of + and * is set to n.
 *
 * @copyright Copyright © 2007-2017 Hoa community
 * @license New BSD License
 */
class BoundedExhaustive extends Sampler implements Iterator
{
    /**
     * Stack of rules to explore.
     *
     * @var array
     */
    protected $_todo;

    /**
     * Stack of rules that have already been covered.
     *
     * @var array
     */
    protected $_trace;

    /**
     * Current iterator key.
     *
     * @var int
     */
    protected $_key     = -1;

    /**
     * Current iterator value.
     *
     * @var string
     */
    protected $_current;

    /**
     * Bound.
     *
     * @var int
     */
    protected $_length  = 5;

    /**
     * Construct a generator.
     *
     * @param Parser $compiler Compiler/parser.
     * @param Visit $tokenSampler Token sampler.
     * @param int $length Max data length.
     * @throws Exception
     */
    public function __construct(
        Parser $compiler,
        Visit       $tokenSampler,
        $length = 5
    ) {
        parent::__construct($compiler, $tokenSampler);
        $this->setLength($length);
    }

    /**
     * Get the current iterator value.
     *
     * @return string
     */
    public function current()
    {
        return $this->_current;
    }

    /**
     * Get the current iterator key.
     *
     * @return int
     */
    public function key()
    {
        return $this->_key;
    }

    /**
     * Useless here.
     *
     * @return void
     */
    public function next(): void
    {
    }

    /**
     * Rewind the internal iterator pointer.
     *
     * @return void
     */
    public function rewind(): void
    {
        $ruleName       = $this->_rootRuleName;
        $this->_current = null;
        $this->_key     = -1;
        $this->_trace   = [];
        $handle         = new Ekzit($ruleName, 0);
        $this->_todo    = [
            $handle,
            new Entry($ruleName, 0, [$handle]),
        ];
    }

    /**
     * Compute the current iterator value, i.e. generate a new solution.
     *
     * @return bool
     */
    public function valid()
    {
        if (false === $this->unfold()) {
            return false;
        }

        $handle = null;

        foreach ($this->_trace as $trace) {
            if ($trace instanceof Token) {
                $handle .= $this->generateToken($trace);
            }
        }

        ++$this->_key;
        $this->_current = $handle;

        return $this->backtrack();
    }

    /**
     * Unfold rules from the todo stack.
     *
     * @return bool
     */
    protected function unfold()
    {
        while (0 < \count($this->_todo)) {
            $pop = \array_pop($this->_todo);

            if ($pop instanceof Ekzit) {
                $this->_trace[] = $pop;
            } else {
                $ruleName = $pop->getRule();
                $next     = $pop->getData();
                $rule     = $this->_rules[$ruleName];
                $out      = $this->boundedExhaustive($rule, $next);

                if (true !== $out && true !== $this->backtrack()) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * The bounded-exhaustive algorithm.
     *
     * @param Rule $rule Rule to cover.
     * @param int $next Next rule.
     * @return bool
     */
    protected function boundedExhaustive(Rule $rule, $next)
    {
        $children = $rule->getChildren();

        if ($rule instanceof Repetition) {
            if (0 === $next) {
                $this->_trace[] = new Entry(
                    $rule->getName(),
                    $rule->getMin()
                );

                \array_pop($this->_todo);
                $this->_todo[]  = new Ekzit(
                    $rule->getName(),
                    $rule->getMin(),
                    $this->_todo
                );

                for ($i = 0, $min = $rule->getMin(); $i < $min; ++$i) {
                    $this->_todo[] = new Ekzit(
                        $children,
                        0
                    );
                    $this->_todo[] = new Entry(
                        $children,
                        0
                    );
                }
            } else {
                $nbToken = 0;

                foreach ($this->_trace as $trace) {
                    if ($trace instanceof Token) {
                        ++$nbToken;
                    }
                }

                $max = $rule->getMax();

                if (-1 !== $max && $next > $max) {
                    return false;
                }

                $this->_todo[] = new Ekzit(
                    $rule->getName(),
                    $next,
                    $this->_todo
                );
                $this->_todo[] = new Ekzit($children, 0);
                $this->_todo[] = new Entry($children, 0);
            }

            return true;
        }
        if ($rule instanceof Choice) {
            if (\count($children) <= $next) {
                return false;
            }

            $this->_trace[] = new Entry(
                $rule->getName(),
                $next,
                $this->_todo
            );
            $nextRule      = $children[$next];
            $this->_todo[] = new Ekzit($nextRule, 0);
            $this->_todo[] = new Entry($nextRule, 0);

            return true;
        }
        if ($rule instanceof Concatenation) {
            $this->_trace[] = new Entry(
                $rule->getName(),
                $next
            );

            for ($i = \count($children) - 1; $i >= 0; --$i) {
                $nextRule      = $children[$i];
                $this->_todo[] = new Ekzit($nextRule, 0);
                $this->_todo[] = new Entry($nextRule, 0);
            }

            return true;
        }
        if ($rule instanceof Token) {
            $nbToken = 0;

            foreach ($this->_trace as $trace) {
                if ($trace instanceof Token) {
                    ++$nbToken;
                }
            }

            if ($nbToken >= $this->getLength()) {
                return false;
            }

            $this->_trace[] = $rule;
            \array_pop($this->_todo);

            return true;
        }

        return false;
    }

    /**
     * Backtrack to the previous choice-point.
     *
     * @return bool
     */
    protected function backtrack()
    {
        $found = false;

        do {
            $last = \array_pop($this->_trace);

            if ($last instanceof Entry) {
                $rule  = $this->_rules[$last->getRule()];
                $found = $rule instanceof Choice;
            } elseif ($last instanceof Ekzit) {
                $rule  = $this->_rules[$last->getRule()];
                $found = $rule instanceof Repetition;
            }
        } while (0 < \count($this->_trace) && false === $found);

        if (false === $found) {
            return false;
        }

        $rule          = $last->getRule();
        $next          = $last->getData() + 1;
        $this->_todo   = $last->getTodo();
        $this->_todo[] = new Entry(
            $rule,
            $next,
            $this->_todo
        );

        return true;
    }

    /**
     * Set upper-bound, the maximum data length.
     *
     * @param int $length Length.
     * @return int
     * @throws Exception
     */
    public function setLength($length)
    {
        if (0 >= $length) {
            throw new Exception(
                'Length must be greater than 0, given %d.',
                0,
                $length
            );
        }

        $old           = $this->_length;
        $this->_length = $length;

        return $old;
    }

    /**
     * Get upper-bound.
     *
     * @return int
     */
    public function getLength(): ?int
    {
        return $this->_length;
    }
}
