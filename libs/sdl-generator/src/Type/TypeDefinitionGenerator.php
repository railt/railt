<?php

declare(strict_types=1);

namespace Railt\SDL\Generator\Type;

use Railt\TypeSystem\Definition\NamedTypeDefinition;

/**
 * @template TDefinition of NamedTypeDefinition
 *
 * @template-extends DefinitionGenerator<TDefinition>
 */
abstract class TypeDefinitionGenerator extends DefinitionGenerator {}
