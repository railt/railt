<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Build;

use Railt\SDL\Node\Statement\Definition\ObjectTypeDefinitionNode;
use Railt\SDL\Node\Statement\Extension\ObjectTypeExtensionNode;
use Railt\TypeSystem\ObjectTypeDefinition;

/**
 * @template-extends BuildObjectLikeTypeDefinitionCommand<ObjectTypeDefinitionNode|ObjectTypeExtensionNode, ObjectTypeDefinition>
 */
final class BuildObjectTypeDefinitionCommand extends BuildObjectLikeTypeDefinitionCommand
{
}
