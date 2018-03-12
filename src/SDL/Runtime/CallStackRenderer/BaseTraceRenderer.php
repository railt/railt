<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Runtime\CallStackRenderer;

/**
 * Class BaseTraceRenderer
 */
abstract class BaseTraceRenderer implements TraceRenderer
{
    /**
     * @var string
     */
    protected $file;

    /**
     * @var int
     */
    protected $line = 0;

    /**
     * @var int
     */
    protected $column = 0;

    /**
     * @return string
     */
    public function toMessageString(): string
    {
        return \sprintf('in %s:%s', $this->getFile(), $this->getLine());
    }

    /**
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * @return int
     */
    public function getLine(): int
    {
        return $this->line;
    }

    /**
     * @return int
     */
    public function getColumn(): int
    {
        return $this->column;
    }
}
