<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Finder;

/**
 * Class Depth
 */
class Depth
{
    /**
     * @var int|null
     */
    private $to;

    /**
     * @var int|null
     */
    private $from;

    /**
     * @return Depth
     */
    public static function any(): Depth
    {
        return new static();
    }

    /**
     * @param int $depth
     * @return Depth
     */
    public static function equals(int $depth): Depth
    {
        return new static($depth, $depth);
    }

    /**
     * @param int $depth
     * @return Depth
     */
    public static function lte(int $depth): Depth
    {
        return new static(0, $depth);
    }

    /**
     * @param int $depth
     * @return Depth
     */
    public static function gte(int $depth): Depth
    {
        return new static($depth);
    }

    /**
     * Depth constructor.
     * @param int|null $to
     * @param int|null $from
     */
    public function __construct(int $from = 0, int $to = null)
    {
        $this->to($to)->from = $from;
    }

    /**
     * @param int $depth
     * @return Depth
     */
    public function exactly(int $depth): Depth
    {
        $this->from = $this->to = $depth;

        return $this;
    }

    /**
     * @param int $depth
     * @return Depth
     */
    public function from(int $depth = 0): Depth
    {
        $this->from = $depth;

        return $this;
    }

    /**
     * @param int|null $depth
     * @return Depth
     */
    public function to(int $depth = null): Depth
    {
        $this->to = $depth ?? \PHP_INT_MAX;

        return $this;
    }

    /**
     * @param int $depth
     * @return bool
     */
    public function match(int $depth): bool
    {
        \assert($this->from <= $this->to);

        return $depth <= $this->to && $depth >= $this->from;
    }

    /**
     * @param int $depth
     * @return bool
     */
    public function notFinished(int $depth): bool
    {
        return $depth <= $this->to;
    }
}
