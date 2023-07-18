<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Build;

use Railt\SDL\Node\Statement\Definition\InterfaceTypeDefinitionNode;
use Railt\SDL\Node\Statement\Extension\InterfaceTypeExtensionNode;
use Railt\TypeSystem\InterfaceTypeDefinition;

/**
 * @template-extends BuildObjectLikeTypeDefinitionCommand<InterfaceTypeDefinitionNode|InterfaceTypeExtensionNode, InterfaceTypeDefinition>
 */
final class BuildInterfaceTypeDefinitionCommand extends BuildObjectLikeTypeDefinitionCommand
{
}
