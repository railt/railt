<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Builder;

use Railt\SDL\TypeSystem\Type\InterfaceType;
use GraphQL\Contracts\TypeSystem\DefinitionInterface;
use Railt\SDL\Ast\Definition\InterfaceTypeDefinitionNode;
use GraphQL\Contracts\TypeSystem\Type\InterfaceTypeInterface;

/**
 * @property-read InterfaceTypeDefinitionNode $ast
 */
class InterfaceTypeBuilder extends TypeBuilder
{
    /**
     * @return DefinitionInterface|InterfaceTypeInterface
     */
    public function build(): InterfaceTypeInterface
    {
        $interface = new InterfaceType();
        $interface->name = $this->ast->name->value;

        $this->registerType($interface);

        $interface->description = $this->description($this->ast->description);
        $interface->fields = \iterator_to_array($this->buildFields($this->ast->fields));
        $interface->interfaces = \iterator_to_array($this->buildImplementedInterfaces($this->ast->interfaces));

        return $interface;
    }
}
