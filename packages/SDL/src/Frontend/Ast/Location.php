<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Frontend\Ast;

use Phplrt\Position\Position;
use Phplrt\Position\PositionInterface;
use Phplrt\Contracts\Source\FileInterface;
use Phplrt\Contracts\Source\ReadableInterface;
use Phplrt\Source\Exception\NotAccessibleException;

/**
 * Contains a range of byte offsets that identify the region of the source
 * from which the AST derived.
 */
final class Location implements \JsonSerializable
{
    /**
     * @var ReadableInterface|FileInterface
     */
    public ReadableInterface $source;

    /**
     * @var int
     */
    public int $start;

    /**
     * @var int
     */
    public int $end;

    /**
     * @var PositionInterface|null
     */
    private ?PositionInterface $startPosition = null;

    /**
     * @var PositionInterface|null
     */
    private ?PositionInterface $endPosition = null;

    /**
     * Location constructor.
     *
     * @param ReadableInterface $source
     * @param int $start
     * @param int $end
     */
    public function __construct(ReadableInterface $source, int $start, int $end)
    {
        $this->source = $source;
        $this->start = $start;
        $this->end = $end;
    }

    /**
     * @return array
     * @throws NotAccessibleException
     * @throws \RuntimeException
     */
    public function jsonSerialize(): array
    {
        $source = $this->source instanceof FileInterface
            ? $this->source->getPathname()
            : '{ ... }';

        return [
            'source' => $source,
            'start'  => ['line' => $this->getStartLine(), 'column' => $this->getStartColumn()],
            'end'    => ['line' => $this->getEndLine(), 'column' => $this->getEndColumn()],
        ];
    }

    /**
     * @return int
     * @throws NotAccessibleException
     * @throws \RuntimeException
     */
    public function getStartLine(): int
    {
        return $this->getStartPosition()->getLine();
    }

    /**
     * @return PositionInterface
     * @throws NotAccessibleException
     * @throws \RuntimeException
     */
    public function getStartPosition(): PositionInterface
    {
        return $this->startPosition ?? $this->startPosition = Position::fromOffset($this->source, $this->start);
    }

    /**
     * @return int
     * @throws NotAccessibleException
     * @throws \RuntimeException
     */
    public function getStartColumn(): int
    {
        return $this->getStartPosition()->getColumn();
    }

    /**
     * @return int
     * @throws NotAccessibleException
     * @throws \RuntimeException
     */
    public function getEndLine(): int
    {
        return $this->getEndPosition()->getLine();
    }

    /**
     * @return PositionInterface
     * @throws NotAccessibleException
     * @throws \RuntimeException
     */
    public function getEndPosition(): PositionInterface
    {
        return $this->endPosition ?? $this->endPosition = Position::fromOffset($this->source, $this->end);
    }

    /**
     * @return int
     * @throws NotAccessibleException
     * @throws \RuntimeException
     */
    public function getEndColumn(): int
    {
        return $this->getEndPosition()->getColumn();
    }
}
