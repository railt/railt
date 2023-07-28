<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Definition;

use Railt\TypeSystem\Definition\Common\HasArgumentsInterface;
use Railt\TypeSystem\Definition\Common\HasArgumentsTrait;
use Railt\TypeSystem\Definition\Common\HasDeprecationInterface;
use Railt\TypeSystem\Definition\Common\HasDeprecationTrait;
use Railt\TypeSystem\Execution\Common\HasDirectivesInterface;
use Railt\TypeSystem\Execution\Common\HasDirectivesTrait;
use Railt\TypeSystem\NamedDefinition;
use Railt\TypeSystem\OutputTypeInterface;

class FieldDefinition extends NamedDefinition implements
    HasDeprecationInterface,
    HasDirectivesInterface,
    HasArgumentsInterface
{
    use HasDeprecationTrait;
    use HasDirectivesTrait;
    use HasArgumentsTrait;

    /**
     * @param non-empty-string $name
     */
    public function __construct(
        string $name,
        private readonly OutputTypeInterface $type,
    ) {
        parent::__construct($name);
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
