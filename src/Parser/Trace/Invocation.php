<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Trace;

/**
 * Class Invocation
 * @internal the class is part of the internal logic
 */
abstract class Invocation extends TraceItem
{
    /**
     * Rule.
     *
     * @var string|int
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
     * Invocation constructor.
     *
     * @param string|int $rule
     * @param int $state
     * @param array|null $todo
     */
    public function __construct($rule, int $state = 0, array $todo = null)
    {
        $this->rule = $rule;
        $this->data = $state;
        $this->todo = $todo;
    }

    /**
     * Get rule name.
     *
     * @return string|int
     */
    public function getRule()
    {
        return $this->rule;
    }

    /**
     * @return int
     */
    public function getData(): int
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function getTodo(): array
    {
        return $this->todo;
    }

    /**
     * @return bool
     */
    public function isTransitional(): bool
    {
        return \is_int($this->rule);
    }
}
