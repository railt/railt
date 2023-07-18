<?php

declare(strict_types=1);

namespace Railt\SDL\Exception;

use Phplrt\Contracts\Position\PositionInterface;
use Phplrt\Contracts\Source\ReadableInterface;

class TypeAlreadyDefinedException extends CompilationException
{
    final public const CODE_TYPE_ALREADY_DEFINED = 0x01 + parent::CODE_LAST;

    final public const CODE_DIRECTIVE_ALREADY_DEFINED = 0x02 + parent::CODE_LAST;

    final public const CODE_SCHEMA_ALREADY_DEFINED = 0x03 + parent::CODE_LAST;

    protected const CODE_LAST = self::CODE_SCHEMA_ALREADY_DEFINED;

    public static function fromTypeName(string $name, ReadableInterface $source, PositionInterface $position): self
    {
        $message = \sprintf('Cannot redefine already defined type "%s"', $name);

        return new static($message, $source, $position, self::CODE_TYPE_ALREADY_DEFINED);
    }

    public static function fromDirectiveName(string $name, ReadableInterface $source, PositionInterface $position): self
    {
        $message = \sprintf('Cannot redefine already defined directive "@%s"', $name);

        return new static($message, $source, $position, self::CODE_DIRECTIVE_ALREADY_DEFINED);
    }

    public static function fromSchema(ReadableInterface $source, PositionInterface $position): self
    {
        $message = 'Cannot redefine already defined schema';

        return new static($message, $source, $position, self::CODE_SCHEMA_ALREADY_DEFINED);
    }
}
