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
