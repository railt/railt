<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Build;

use Railt\SDL\Node\Statement\Definition\ObjectTypeDefinitionNode;
use Railt\SDL\Node\Statement\Extension\ObjectTypeExtensionNode;
use Railt\TypeSystem\Definition\Type\ObjectType;

/**
 * @template-extends BuildObjectLikeTypeDefinitionCommand<ObjectTypeDefinitionNode|ObjectTypeExtensionNode, ObjectType>
 *
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL\Compiler\Command
 */
final class BuildObjectTypeDefinitionCommand extends BuildObjectLikeTypeDefinitionCommand {}
