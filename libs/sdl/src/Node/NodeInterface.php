<?php

declare(strict_types=1);

namespace Railt\SDL\Node;

use Phplrt\Contracts\Ast\NodeInterface as GenericNodeInterface;
use Phplrt\Contracts\Position\PositionInterface;
use Phplrt\Contracts\Source\ReadableInterface;

/**
 * @internal This is an internal library interface, please do not use it in your code.
 * @psalm-internal Railt\SDL
 */
interface NodeInterface extends GenericNodeInterface
{
    /**
     * Returns position of the {@see NodeInterface} inside the
     * {@see ReadableInterface} source.
     */
    public function getPosition(): PositionInterface;

    /**
     * Returns the position line of the AST node in the source.
     *
     * @return int<1, max>
     */
    public function getLine(): int;

    /**
     * Returns the position column of the AST node in the source.
     *
     * @return int<1, max>
     */
    public function getColumn(): int;

    /**
     * Returns the offset of the position of the AST node (in bytes)
     * in the source.
     *
     * @return int<0, max>
     */
    public function getOffset(): int;

    /**
     * Returns the source object from which this AST node object was obtained.
     */
    public function getSource(): ReadableInterface;
}
