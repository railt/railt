<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Build;

use Railt\SDL\Node\Statement\Definition\InterfaceTypeDefinitionNode;
use Railt\SDL\Node\Statement\Extension\InterfaceTypeExtensionNode;
use Railt\TypeSystem\Definition\Type\InterfaceType;

/**
 * @template-extends BuildObjectLikeTypeDefinitionCommand<InterfaceTypeDefinitionNode|InterfaceTypeExtensionNode, InterfaceType>
 *
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Railt\SDL\Compiler\Command
 */
final class BuildInterfaceTypeDefinitionCommand extends BuildObjectLikeTypeDefinitionCommand {}
