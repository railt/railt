<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Exceptions;

/**
 * Trait Helper
 * @deprecated Will be replaced by an internal compiler exception
 */
trait Helper
{
    /**
     * @var int
     */
    protected $column = 0;

    /**
     * @param string $message
     * @param array ...$params
     * @return $this|static|\Throwable
     */
    public static function create(string $message, ...$params): \Throwable
    {
        return new static(sprintf($message, ...$params));
    }

    /**
     * @param int $code
     * @return self|$this
     */
    public function withCode(int $code = 0)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @param \Throwable $previous
     * @return self|$this
     */
    public function withParent(\Throwable $previous)
    {
        $this->previous = $previous;

        return $this;
    }

    /**
     * @param string $message
     * @param array ...$params
     * @throws $this|static
     * @throws \Throwable
     */
    public static function throw(string $message, ...$params): void
    {
        throw static::create($message, ...$params);
    }

    /**
     * @param string $file
     * @return $this
     */
    public function in(string $file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @param int $line
     * @param int $column
     * @return $this
     */
    public function on(int $line = 0, int $column = 0)
    {
        $this->line = $line;
        $this->column = $column;

        return $this;
    }

    /**
     * @return int
     */
    public function getColumn(): int
    {
        return $this->column;
    }
}
