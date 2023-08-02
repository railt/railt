<?php

declare(strict_types=1);

namespace Railt\Extension\Router\Exception;

class RouteDefinitionException extends \InvalidArgumentException implements RouterExceptionInterface
{
    public const CODE_CONTAINER_NOT_DEFINED = 0x01;
    public const CODE_INVALID_ACTION = 0x02;
    public const CODE_ACTION_NOT_DEFINED = 0x03;
    public const CODE_ACTION_NOT_CALLABLE = 0x04;

    final public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function fromContainerNotDefined(string $action): self
    {
        $message = 'Action "%s" requires a service container definition';
        $message = \sprintf($message, $action);

        return new static($message, self::CODE_CONTAINER_NOT_DEFINED);
    }

    public static function fromInvalidAction(string $action): self
    {
        $message = 'Action "%s" contains invalid format';
        $message = \sprintf($message, $action);

        return new static($message, self::CODE_INVALID_ACTION);
    }

    public static function fromActionNotDefined(string $action, string $method): self
    {
        $message = 'Unable to resolve handler "%s" from action "%s"';
        $message = \sprintf($message, $method, $action);

        return new static($message, self::CODE_ACTION_NOT_DEFINED);
    }

    public static function fromActionIsNotCallable(string $action, mixed $given = null): self
    {
        $message = 'Action "%s" expects to be callable';
        $message = \sprintf($message, $action);

        if (\func_num_args() === 2) {
            $message .= \sprintf(', but %s given', \get_debug_type($given));
        }

        return new static($message, self::CODE_ACTION_NOT_CALLABLE);
    }
}
