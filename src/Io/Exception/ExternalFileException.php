<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Io\Exception;

use Railt\Io\PositionInterface;
use Railt\Io\Readable;

/**
 * An error that may occur in an external file.
 */
class ExternalFileException extends \LogicException implements ExternalExceptionInterface
{
    /**
     * @var int
     */
    protected $column = 1;

    /**
     * @param Readable $file
     * @param PositionInterface $position
     * @return ExternalExceptionInterface|$this
     */
    public function throwsAt(Readable $file, PositionInterface $position): ExternalExceptionInterface
    {
        return $this->throwsIn($file, $position->getLine(), $position->getColumn());
    }

    /**
     * @param Readable $file
     * @param int $offsetOrLine
     * @param int|null $column
     * @return ExternalFileException|$this
     */
    public function throwsIn(Readable $file, int $offsetOrLine = 0, int $column = null): ExternalExceptionInterface
    {
        $this->file = $file->getPathname();

        if ($column === null) {
            $position = $file->getPosition($offsetOrLine);
            [$offsetOrLine, $column] = [$position->getLine(), $position->getColumn()];
        }

        [$this->line, $this->column] = [$offsetOrLine, $column];

        return $this;
    }

    /**
     * @param ExternalFileException $exception
     * @return ExternalFileException|$this|self|static
     */
    public function from(self $exception): self
    {
        $this->file = $exception->getFile();
        $this->line = $exception->getLine();
        $this->column = $exception->getColumn();

        return $this;
    }

    /**
     * @return int
     */
    final public function getColumn(): int
    {
        return $this->column;
    }
}
