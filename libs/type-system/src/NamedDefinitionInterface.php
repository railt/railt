<?php

declare(strict_types=1);

namespace Railt\TypeSystem;

use Railt\TypeSystem\Common\HasDescriptionInterface;
use Railt\TypeSystem\Common\HasNameInterface;

interface NamedDefinitionInterface extends
    HasNameInterface,
    HasDescriptionInterface,
    DefinitionInterface {}
