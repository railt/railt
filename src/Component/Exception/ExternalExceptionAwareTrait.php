<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Exception;

use Railt\Component\Position\HighlightInterface;

/**
 * Trait ExternalExceptionAwareTrait
 */
trait ExternalExceptionAwareTrait
{
    /**
     * @var int
     */
    protected $column = 1;

    /**
     * @param string $name
     * @return MutableExceptionInterface|$this
     */
    public function withFile(string $name): MutableExceptionInterface
    {
        $this->file = $name;

        return $this;
    }

    /**
     * @param int $line
     * @return MutableExceptionInterface|$this
     */
    public function withLine(int $line): MutableExceptionInterface
    {
        $this->line = \max(1, $line);

        return $this;
    }

    /**
     * @param int $column
     * @return MutableExceptionInterface|$this
     */
    public function withColumn(int $column): MutableExceptionInterface
    {
        $this->column = \max(1, $column);

        return $this;
    }

    /**
     * @return int
     */
    public function getColumn(): int
    {
        return $this->column;
    }

    /**
     * @param int $code
     * @return MutableExceptionInterface|$this
     */
    public function withCode(int $code = 0): MutableExceptionInterface
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @param string $message
     * @param mixed ...$args
     * @return MutableExceptionInterface|$this
     */
    public function withMessage(string $message, ...$args): MutableExceptionInterface
    {
        $this->message = \vsprintf($message, $args);

        return $this;
    }
}
