<?php

declare(strict_types=1);

namespace Railt\SDL\Exception;

use Phplrt\Contracts\Position\PositionInterface;
use Phplrt\Contracts\Source\ReadableInterface;

interface RuntimeExceptionInterface extends LanguageExceptionInterface
{
    /**
     * Returns exception source.
     */
    public function getSource(): ReadableInterface;

    /**
     * Returns exception position.
     */
    public function getPosition(): PositionInterface;
}
