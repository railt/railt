<?php

declare(strict_types=1);

namespace Railt\SDL\Node;

use Phplrt\Contracts\Position\PositionInterface;
use Phplrt\Contracts\Source\ReadableInterface;
use Phplrt\Position\Position;
use Phplrt\Source\File;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL
 */
abstract class Node implements NodeInterface
{
    /**
     * Contains information about {@see Node} position.
     *
     * Note: May be initialized by the {@see null} literal value.
     */
    private ?PositionInterface $position = null;

    /**
     * Contains information about {@see ReadableInterface} source object.
     *
     * Note: May be initialized by the {@see null} literal value.
     */
    private ?ReadableInterface $source = null;

    /**
     * @var int<0, max>
     */
    private int $offset = 0;

    /**
     * @var array<non-empty-string, mixed>
     */
    private array $attributes = [];

    /**
     * @param non-empty-string $name
     */
    public function getAttribute(string $name, mixed $default = null): mixed
    {
        return $this->attributes[$name] ?? $default;
    }

    /**
     * @param non-empty-string $name
     */
    public function hasAttribute(string $name): bool
    {
        return isset($this->attributes[$name])
            && \array_key_exists($name, $this->attributes)
        ;
    }

    /**
     * @param non-empty-string $name
     */
    public function setAttribute(string $name, mixed $value): void
    {
        $this->attributes[$name] = $value;
    }

    /**
     * @return array<non-empty-string, mixed>
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param array<non-empty-string, mixed> $attributes
     */
    public function setAttributes(array $attributes): void
    {
        $this->attributes = $attributes;
    }

    public function getPosition(): PositionInterface
    {
        return $this->position ??= Position::fromOffset($this->getSource(), $this->getOffset());
    }

    public function getLine(): int
    {
        $position = $this->getPosition();

        return $position->getLine();
    }

    public function getColumn(): int
    {
        $position = $this->getPosition();

        return $position->getColumn();
    }

    public function getOffset(): int
    {
        return \max($this->offset, 0);
    }

    /**
     * @internal This is an internal method
     *
     * @param int<0, max> $offset
     */
    public function setContext(ReadableInterface $source, int $offset): void
    {
        $this->position = null;

        $this->source = $source;
        $this->offset = \max($offset, 0);
    }

    public function getSource(): ReadableInterface
    {
        return $this->source ??= File::empty();
    }

    /**
     * @psalm-suppress MixedReturnTypeCoercion
     */
    public function getIterator(): \Traversable
    {
        $reflection = new \ReflectionObject($this);

        foreach ($reflection->getProperties() as $property) {
            $attributes = $property->getAttributes(Visitable::class);

            if ($attributes === []) {
                continue;
            }

            yield $property->getName() => $property->getValue($this);
        }
    }

    public function __debugInfo(): array
    {
        // Preload position if source is initialized
        if ($this->source !== null) {
            $this->getPosition();
        }

        $trace = \debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS);

        // Skip variables export for symfony/var-dumper
        foreach ($trace as $exec) {
            if (isset($exec['class']) && \str_starts_with($exec['class'], 'Symfony\Component\VarDumper')) {
                return [];
            }
        }

        return \get_object_vars($this);
    }
}
