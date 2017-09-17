<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Exceptions;

/**
 * Trait ExceptionHelper
 * @method \Exception __construct(string $message, ?int $code, ?\Throwable $prev)
 */
trait ExceptionHelper
{
    /**
     * @param string $message
     * @param array ...$args
     * @return self|$this
     */
    public static function new(string $message, ...$args): self
    {
        return new static(sprintf($message, ...$args));
    }

    /**
     * @param int $code
     * @return self|$this
     */
    public function withCode(int $code = 0): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @param \Throwable $previous
     * @return self|$this
     */
    public function withPrevious(\Throwable $previous): self
    {
        $this->previous = $previous;

        return $this;
    }

    /**
     * @param string $file
     * @param int $line
     * @return self|$this
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
