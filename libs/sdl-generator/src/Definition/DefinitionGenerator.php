<?php

declare(strict_types=1);

namespace Railt\SDL\Generator\Type;

use Railt\SDL\Generator\Config;
use Railt\SDL\Generator\Generator;
use Railt\TypeSystem\DefinitionInterface;

/**
 * @template TDefinition of DefinitionInterface
 */
abstract class DefinitionGenerator extends Generator
{
    /**
     * @param TDefinition $type
     */
    public function __construct(
        protected readonly DefinitionInterface $type,
        Config $config = new Config(),
    ) {
        parent::__construct($config);
    }
}
