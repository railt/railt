<?php

declare(strict_types=1);

namespace Railt\TypeSystem;

interface NamedDefinitionInterface extends
    DefinitionInterface,
    NameAwareInterface,
    DescriptionAwareInterface,
    DirectivesProviderInterface
{
}
