<?php

declare(strict_types=1);

namespace Railt\Executor\Webonyx\Builder;

use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\Type;
use Railt\TypeSystem\Execution\InputObject;
use Railt\TypeSystem\ListType;
use Railt\TypeSystem\NonNullType;
use Railt\TypeSystem\TypeInterface;
use Railt\TypeSystem\WrappingTypeInterface;
use Railt\Executor\Webonyx\Builder\Internal\BuilderFactory;

/**
 * @template TInput of object
 * @template TOutput of mixed
 *
 * @template-implements BuilderInterface<TInput, TOutput>
 */
abstract class Builder implements BuilderInterface
{
    public function __construct(
        protected readonly BuilderFactory $builder,
    ) {
    }

    protected function value(mixed $value): mixed
    {
        if ($value instanceof InputObject || \is_iterable($value)) {
            $result = [];

            /**
             * @var array-key $key
             * @var mixed $item
             */
            foreach ($value as $key => $item) {
                /** @psalm-suppress MixedAssignment : Okay */
                $result[$key] = $this->value($item);
            }

            return $result;
        }

        return $value;
    }

    /**
     * @psalm-suppress all
     */
    protected function type(TypeInterface $type): Type
    {
        return match (true) {
            $type instanceof NonNullType => new NonNull(
                $this->type($type->getOfType()),
            ),
            $type instanceof ListType => new ListOfType(
                $this->type($type->getOfType()),
            ),
            $type instanceof WrappingTypeInterface => $this->type($type->getOfType()),
            default => $this->builder->getType($type),
        };
    }

    /**
     * @param non-empty-string $expected
     */
    protected static function typeError(string $expected, mixed $value): \TypeError
    {
        $trace = \debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS);

        $message = \vsprintf('Argument #1 ($input) must be of type %s, %s given', [
            $expected,
            \get_debug_type($value)
        ]);

        $reflection = new \ReflectionObject($error = new \TypeError($message));

        $file = $reflection->getProperty('file');
        $file->setValue($error, $trace[1]['file'] ?? __FILE__);

        $line = $reflection->getProperty('line');
        $line->setValue($error, $trace[1]['line'] ?? __LINE__);

        return $error;
    }
}
