<?php

declare(strict_types=1);

namespace Railt\Http\Factory\Exception;

use Railt\Contracts\Http\Factory\Exception\ParsingExceptionInterface;

class ParsingException extends \InvalidArgumentException implements
    ParsingExceptionInterface
{
    public const CODE_INVALID_FORMAT = 0x01;
    public const CODE_EMPTY_REQUEST = 0x02;
    public const CODE_INVALID_QUERY_FIELD = 0x03;
    public const CODE_INVALID_VARIABLES_FIELD = 0x04;
    public const CODE_INVALID_OPERATION_NAME_FIELD = 0x05;

    final public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function fromJsonException(\JsonException $e): self
    {
        $message = \sprintf('An error occurred while parsing JSON: %s', $e->getMessage());

        return new static($message, self::CODE_INVALID_FORMAT, $e);
    }

    public static function fromEmptyRequest(): self
    {
        return new static('An empty GraphQL request received', self::CODE_EMPTY_REQUEST);
    }

    private static function getPrettyTypeName(mixed $actual): string
    {
        $type = \is_object($actual) ? 'object' : \get_debug_type($actual);

        if (\is_scalar($actual)) {
            $type .= '(' . \print_r($actual, true) . ')';
        }

        return $type;
    }

    public static function fromInvalidQueryField(mixed $actual): self
    {
        $message = \vsprintf('GraphQL query field must be a string, but %s given', [
            self::getPrettyTypeName($actual),
        ]);

        return new static($message, self::CODE_INVALID_QUERY_FIELD);
    }

    public static function fromInvalidVariablesField(mixed $actual): self
    {
        $message = \vsprintf('GraphQL variables field must be an object or null, but %s given', [
            self::getPrettyTypeName($actual),
        ]);

        return new static($message, self::CODE_INVALID_VARIABLES_FIELD);
    }

    public static function fromInvalidOperationNameField(mixed $actual): self
    {
        $message = \vsprintf('GraphQL operation name field must be a string or null, but %s given', [
            self::getPrettyTypeName($actual),
        ]);

        return new static($message, self::CODE_INVALID_OPERATION_NAME_FIELD);
    }
}
