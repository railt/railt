<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Execution\Common;

use Railt\TypeSystem\Execution\Directive;

/**
 * @mixin HasDirectivesInterface
 * @psalm-require-implements HasDirectivesInterface
 */
trait HasDirectivesTrait
{
    /**
     * @var array<non-empty-string, Directive>
     */
    private array $directives = [];

    /**
     * @param iterable<Directive> $directives
     */
    public function setDirectives(iterable $directives): void
    {
        $this->directives = [];

        foreach ($directives as $directive) {
            $this->addDirective($directive);
        }
    }

    /**
     * @param iterable<Directive> $directives
     */
    public function withDirectives(iterable $directives): self
    {
        $self = clone $this;
        $self->setDirectives($directives);

        return $self;
    }

    public function removeDirectives(): void
    {
        $this->directives = [];
    }

    public function withoutDirectives(): self
    {
        $self = clone $this;
        $self->removeDirectives();

        return $self;
    }

    public function addDirective(Directive $directive): void
    {
        $this->directives[$directive->getHash()] = $directive;
    }

    public function withAddedDirective(Directive $directive): self
    {
        $self = clone $this;
        $self->addDirective($directive);

        return $self;
    }

    /**
     * @param Directive|non-empty-string $directive
     */
    public function removeDirective(Directive|string $directive): void
    {
        if ($directive instanceof Directive) {
            $this->removeDirectiveByInstance($directive);
        } else {
            $this->removeDirectiveByName($directive);
        }
    }

    private function removeDirectiveByInstance(Directive $directive): void
    {
        unset($this->directives[$directive->getHash()]);
    }

    /**
     * @param non-empty-string $name
     */
    private function removeDirectiveByName(string $name): void
    {
        foreach ($this->directives as $hash => $directive) {
            if ($directive->getName() === $name) {
                unset($this->directives[$hash]);
            }
        }
    }

    /**
     * @param Directive|non-empty-string $argument
     */
    public function withoutDirective(Directive|string $argument): self
    {
        $self = clone $this;
        $self->removeDirective($argument);

        return $self;
    }

    /**
     * @param non-empty-string|null $name
     *
     * @return iterable<Directive>
     */
    public function getDirectives(string $name = null): iterable
    {
        if ($name === null) {
            /** @var list<Directive> */
            return \array_values($this->directives);
        }

        /** @var iterable<Directive> */
        return $this->getDirectivesByName($name);
    }

    /**
     * @param non-empty-string $name
     *
     * @return \Iterator<array-key, Directive>
     */
    private function getDirectivesByName(string $name): \Iterator
    {
        foreach ($this->directives as $directive) {
            if ($directive->getName() === $name) {
                yield $directive;
            }
        }
    }

    /**
     * @param non-empty-string $name
     */
    public function hasDirective(string $name): bool
    {
        foreach ($this->directives as $directive) {
            if ($directive->getName() === $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param non-empty-string|null $name
     *
     * @return int<0, max>
     */
    public function getNumberOfDirectives(string $name = null): int
    {
        if ($name === null) {
            /** @var int<0, max> */
            return \count($this->directives);
        }

        /** @var int<0, max> */
        return \iterator_count($this->getDirectivesByName($name));
    }
}
