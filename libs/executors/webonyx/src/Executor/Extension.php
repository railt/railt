<?php

declare(strict_types=1);

namespace Railt\Executor\Webonyx\Executor;

use Railt\Contracts\Http\Response\ExtensionInterface;

/**
 * @template TValue of mixed
 *
 * @template-implements ExtensionInterface<TValue>
 */
final class Extension implements ExtensionInterface
{
    /**
     * @param non-empty-string $name
     * @param TValue $value
     */
    public function __construct(
        private readonly string $name,
        private readonly mixed $value,
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return TValue
     */
    public function getValue(): mixed
    {
        return $this->value;
    }
}
