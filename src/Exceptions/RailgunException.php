<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Exceptions;

/**
 * Class RailgunException
 * @package Railgun\Exceptions
 * @method \Exception __construct(string $message, ?int $code, ?\Throwable $prev)
 */
trait RailgunException
{
    /**
     * @param string $message
     * @param array ...$args
     * @return self|RailgunException
     */
    public static function new(string $message, ...$args): self
    {
        return new static(sprintf($message, ...$args));
    }

    /**
     * @param int $code
     * @return self
     */
    public function code(int $code = 0): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @param \Throwable $previous
     * @return RailgunException|$this
     */
    public function from(\Throwable $previous): self
    {
        $this->previous = $previous;

        return $this;
    }

    /**
     * @param string $file
     * @param int $line
     * @return RailgunException|$this
     */
    public function in(string $file, int $line = null): self
    {
        $this->file = $file;

        if ($line !== null) {
            $this->line = 0;
        }

        return $this;
    }
}
