<?php
/**
 * This file is part of railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Generator\Grammar\Reader\Productions;

use Railt\Compiler\Generator\Grammar\Lexer;
use Railt\Compiler\Generator\Grammar\Reader\Context\Item;

/**
 * Class Repetition
 */
class Repetition extends Group
{
    public const INF = -1;

    /**
     * @var int
     */
    protected $min;

    /**
     * @var int
     */
    protected $max;

    /**
     * Repetition constructor.
     * @param Group $parent
     * @param int $min
     * @param int $max
     */
    public function __construct(Group $parent, int $min, int $max = self::INF)
    {
        $this->min = $min;
        $this->max = $max;

        parent::__construct($parent);
    }

    /**
     * @param Group $parent
     * @param Item $item
     * @return Repetition
     */
    public static function make(Group $parent, Item $item): self
    {
        return new static($parent, ...self::interval($item));
    }

    /**
     * @param Item $item
     * @return array
     */
    private static function interval(Item $item): array
    {
        switch (true) {
            case $item->is(Lexer::T_ZERO_OR_ONE):
                return [0, 1];
            case $item->is(Lexer::T_ONE_OR_MORE):
                return [1, self::INF];
            case $item->is(Lexer::T_ZERO_OR_MORE):
                return [0, self::INF];
            case $item->is(Lexer::T_N_TO_M):
                return [(int)$item->context(0), (int)$item->context(1)];
            case $item->is(Lexer::T_ZERO_TO_M):
                return [0, (int)$item->context(0)];
            case $item->is(Lexer::T_N_OR_MORE):
                return [(int)$item->context(0), self::INF];
            case $item->is(Lexer::T_EXACTLY_N):
                return [(int)$item->context(0), (int)$item->context(0)];
        }
    }
}
