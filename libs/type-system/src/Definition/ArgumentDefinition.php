<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Definition;

use Railt\TypeSystem\Definition\Common\HasDeprecationTrait;
use Railt\TypeSystem\Definition\Common\HasDeprecationInterface;
use Railt\TypeSystem\Execution\Common\HasDirectivesInterface;
use Railt\TypeSystem\Execution\Common\HasDirectivesTrait;
use Railt\TypeSystem\InputTypeInterface;
use Railt\TypeSystem\NamedDefinition;

class ArgumentDefinition extends NamedDefinition implements
    HasDeprecationInterface,
    HasDirectivesInterface
{
    use HasDeprecationTrait;
    use HasDirectivesTrait;

    private mixed $defaultValue = null;
    private bool $hasDefaultValue = false;

    /**
     * @param non-empty-string $name
     */
    public function __construct(
        string $name,
        private readonly InputTypeInterface $type,
    ) {
        parent::__construct($name);
    }

    public function setDefaultValue(mixed $value): void
    {
        $this->defaultValue = $value;
        $this->hasDefaultValue = true;
    }

    public function removeDefaultValue(): void
    {
        $this->defaultValue = null;
        $this->hasDefaultValue = false;
    }

    public function withDefaultValue(mixed $value): self
    {
        $self = clone $this;
        $self->setDefaultValue($value);

        return $self;
    }

    public function withoutDefaultValue(): self
    {
        $self = clone $this;
        $self->removeDefaultValue();

        return $self;
    }

    public function getDefaultValue(): mixed
    {
        return $this->defaultValue;
    }

    public function hasDefaultValue(): bool
    {
        return $this->hasDefaultValue;
    }

    public function getType(): InputTypeInterface
    {
        return $this->type;
    }

    public function __toString(): string
    {
        /** @var non-empty-string */
        return \vsprintf('argument<%s: %s>', [
            $this->getName(),
            (string)$this->getType(),
        ]);
    }
}
