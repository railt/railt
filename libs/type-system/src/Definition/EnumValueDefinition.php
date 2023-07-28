<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Definition;

use Railt\TypeSystem\Definition\Common\HasDeprecationTrait;
use Railt\TypeSystem\Definition\Common\HasDeprecationInterface;
use Railt\TypeSystem\Execution\Common\HasDirectivesInterface;
use Railt\TypeSystem\Execution\Common\HasDirectivesTrait;
use Railt\TypeSystem\NamedDefinition;

class EnumValueDefinition extends NamedDefinition implements
    HasDeprecationInterface,
    HasDirectivesInterface
{
    use HasDeprecationTrait;
    use HasDirectivesTrait;

    /**
     * @param non-empty-string $name
     */
    public function __construct(
        string $name,
        private mixed $value = null,
    ) {
        parent::__construct($name);
    }

    /**
     * @param non-empty-string $name
     */
    public static function fromName(string $name): self
    {
        return new self($name, $name);
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function setValue(mixed $value): void
    {
        $this->value = $value;
    }

    public function withValue(mixed $value): self
    {
        $self = clone $this;
        $self->setValue($value);

        return $self;
    }

    public function __toString(): string
    {
        return \sprintf('enum-value<%s>', $this->getName());
    }
}
