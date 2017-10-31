<?php
/**
 * This file is part of railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Validation;

use Railt\Compiler\Reflection\Contracts\Behavior\AllowsTypeIndication;
use Railt\Compiler\Reflection\Support;
use Railt\Compiler\Exceptions\TypeRedefinitionException;
use Railt\Compiler\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Compiler\Reflection\Contracts\Definitions\Definition;
use Railt\Compiler\Reflection\Validation\Definitions\TypeIndication;
use Railt\Compiler\Reflection\Validation\Definitions\DefinitionValidator;

/**
 * Class Validator
 */
class Validator
{
    use Support;

    /**
     * @var array|DefinitionValidator[]
     */
    private $definitions = [];

    /**
     * @var Inheritance
     */
    private $inheritance;

    /**
     * Validator constructor.
     */
    public function __construct()
    {
        $this->inheritance = new Inheritance();

        $this->definitions = [
            new TypeIndication($this),
        ];
    }

    /**
     * @param Definition $definition
     * @return void
     */
    public function verifyDefinition(Definition $definition): void
    {
        foreach ($this->definitions as $validator) {
            if ($validator->match($definition)) {
                $validator->verify($definition);
            }
        }
    }

    /**
     * @param AllowsTypeIndication $parent
     * @param AllowsTypeIndication $child
     * @return void
     * @throws \Railt\Compiler\Exceptions\TypeConflictException
     */
    public function verifyPostConditionInheritance(AllowsTypeIndication $parent, AllowsTypeIndication $child): void
    {
        $this->inheritance->verify($parent, $child);
    }

    /**
     * @param AllowsTypeIndication $parent
     * @param AllowsTypeIndication $child
     * @return void
     * @throws \Railt\Compiler\Exceptions\TypeConflictException
     */
    public function verifyPreConditionInheritance(AllowsTypeIndication $parent, AllowsTypeIndication $child): void
    {
        $this->inheritance->verify($child, $parent);
    }

    /**
     * @param array $container
     * @param string $value
     * @param string $type
     * @return array
     * @throws TypeRedefinitionException
     */
    public function uniqueValues(array $container, string $value, string $type): array
    {
        if (!\array_key_exists($value, $container)) {
            $container[$value] = $value;

            return $container;
        }

        $error = \sprintf('Can not redefine already defined %s %s', $type, $value);
        throw new TypeRedefinitionException($error);
    }

    /**
     * @param array $container
     * @param TypeDefinition $definition
     * @return array
     * @throws TypeRedefinitionException
     */
    public function uniqueDefinitions(array $container, TypeDefinition $definition): array
    {
        if (!\array_key_exists($definition->getName(), $container)) {
            $container[$definition->getName()] = $definition;

            return $container;
        }

        $error = \sprintf('Can not redefine already defined %s', $this->typeToString($definition));
        throw new TypeRedefinitionException($error);
    }

    /**
     * @param mixed $container
     * @param TypeDefinition $definition
     * @return Definition
     * @throws TypeRedefinitionException
     */
    public function uniqueDefinition($container, TypeDefinition $definition): Definition
    {
        // Verify container is empty
        if ($container === null) {
            return $definition;
        }

        // Verify container type is same with new type
        if ($container instanceof TypeDefinition && $container->getUniqueId() === $definition->getUniqueId()) {
            return $definition;
        }

        $error = \sprintf('Can not redefine already defined %s', $this->typeToString($definition));
        throw new TypeRedefinitionException($error);
    }
}
