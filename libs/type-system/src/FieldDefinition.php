<?php

declare(strict_types=1);

namespace Railt\TypeSystem;

final class FieldDefinition extends Definition implements
    NamedDefinitionInterface,
    ArgumentDefinitionProviderInterface
{
    use DescriptionAwareTrait;
    use DeprecationAwareTrait;
    use DirectivesProviderTrait;
    use ArgumentDefinitionProviderTrait;

    /**
     * @param non-empty-string $name
     */
    public function __construct(
        private readonly string $name,
        private readonly OutputTypeInterface $type,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): OutputTypeInterface
    {
        return $this->type;
    }

    public function __toString(): string
    {
        /** @var non-empty-string */
        return \vsprintf('field<%s: %s>', [
            $this->getName(),
            (string)$this->getType(),
        ]);
    }
}
