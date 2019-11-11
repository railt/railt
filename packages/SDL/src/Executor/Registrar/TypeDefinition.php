<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Executor\Registrar;

use Phplrt\Contracts\Ast\NodeInterface;
use Phplrt\Source\Exception\NotAccessibleException;
use Phplrt\Visitor\Traverser;
use Railt\SDL\Ast\Definition\TypeDefinitionNode;
use Railt\SDL\Exception\TypeErrorException;

/**
 * Class TypeDefinition
 */
class TypeDefinition extends TypeRegistrar
{
    /**
     * @var string
     */
    private const ERROR_TYPE_REDEFINITION = 'There can be only one type named %s';

    /**
     * @param NodeInterface $type
     * @return int|void
     * @throws NotAccessibleException
     * @throws TypeErrorException
     * @throws \RuntimeException
     */
    public function enter(NodeInterface $type)
    {
        if ($type instanceof TypeDefinitionNode) {
            $this->assertUniqueness($type);

            $this->registry->typeMap->put($type->name->value, $type);

            //
            // Temporary optimization.
            // If there is an implementation of nested types,
            // then this code should be deleted.
            //
            return Traverser::DONT_TRAVERSE_CHILDREN;
        }
    }

    /**
     * @param TypeDefinitionNode $type
     * @return void
     * @throws NotAccessibleException
     * @throws TypeErrorException
     * @throws \RuntimeException
     */
    private function assertUniqueness(TypeDefinitionNode $type): void
    {
        if ($this->exists($type->name, $this->dictionary->typeMap, $this->registry->typeMap)) {
            $message = \sprintf(self::ERROR_TYPE_REDEFINITION, $type->name->value);

            throw new TypeErrorException($message, $type);
        }
    }
}
