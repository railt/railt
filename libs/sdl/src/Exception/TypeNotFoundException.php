<?php

declare(strict_types=1);

namespace Railt\SDL\Exception;

use Phplrt\Contracts\Position\PositionInterface;
use Phplrt\Contracts\Source\ReadableInterface;

class TypeNotFoundException extends CompilationException
{
    final public const CODE_TYPE_NOT_FOUND = 0x01 + parent::CODE_LAST;

    final public const CODE_DIRECTIVE_NOT_FOUND = 0x02 + parent::CODE_LAST;

    protected const CODE_LAST = self::CODE_DIRECTIVE_NOT_FOUND;

    public static function fromTypeName(string $name, ReadableInterface $source, PositionInterface $position): self
    {
        $message = \sprintf('Type "%s" not found or could not be loaded', $name);

        return new static($message, $source, $position, self::CODE_TYPE_NOT_FOUND);
    }

    public static function fromDirectiveName(string $name, ReadableInterface $source, PositionInterface $position): self
    {
        $message = \sprintf('Directive "@%s" not found or could not be loaded', $name);

        return new static($message, $source, $position, self::CODE_DIRECTIVE_NOT_FOUND);
    }
}
