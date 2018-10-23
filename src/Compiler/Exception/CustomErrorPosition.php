<?php
/**
 * This file is part of Lexer package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Exception;

use Railt\Io\Readable;

/**
 * Trait CustomErrorPosition
 */
trait CustomErrorPosition
{
    /**
     * @var int
     */
    protected $column = 0;

    /**
     * @param Readable $readable
     * @param int $offset
     * @return static|$this|self
     */
    public function inFile(Readable $readable, int $offset): self
    {
        if ($readable->isFile()) {
            $position = $readable->getPosition($offset);

            $this->file   = $readable->getPathname();
            $this->line   = $position->getLine();
            $this->column = $position->getColumn();
        }

        return $this;
    }

    /**
     * Should return a source code column on which the error occurred.
     *
     * @return int
     */
    public function getColumn(): int
    {
        return $this->column;
    }

    /**
     * @param string $message
     * @param Readable $readable
     * @param int $offset
     * @return static|$this|self
     */
    public static function fromFile(string $message, Readable $readable, int $offset): self
    {
        $instance = new static($message);
        $instance->inFile($readable, $offset);

        return $instance;
    }

    /**
     * @throws static|$this|self
     */
    public function throw(): void
    {
        throw $this;
    }
}
