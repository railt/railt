<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Builder;

use GraphQL\TypeSystem\Type\InterfaceType;
use Railt\SDL\Builder\Common\FieldsBuilderTrait;
use GraphQL\Contracts\TypeSystem\DefinitionInterface;
use Railt\SDL\Ast\Definition\InterfaceTypeDefinitionNode;
use GraphQL\Contracts\TypeSystem\Type\InterfaceTypeInterface;

/**
 * @property InterfaceTypeDefinitionNode $ast
 */
class InterfaceTypeBuilder extends TypeBuilder
{
    use FieldsBuilderTrait;

    /**
     * @return DefinitionInterface|InterfaceTypeInterface
     * @throws \RuntimeException
     */
    public function build(): InterfaceTypeInterface
    {
        $interface = new InterfaceType([
            'name'        => $this->ast->name->value,
            'description' => $this->value($this->ast->description),
        ]);

        $this->registerType($interface);

        return $interface
            ->withFields($this->buildFields($this->ast->fields))
            ->withInterfaces($this->buildImplementedInterfaces($this->ast->interfaces));
    }
}
