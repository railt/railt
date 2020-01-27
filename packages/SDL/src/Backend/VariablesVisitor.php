<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend;

use Phplrt\Contracts\Ast\NodeInterface;
use Phplrt\Source\Exception\NotAccessibleException;
use Phplrt\Visitor\Visitor;
use Railt\SDL\Exception\RuntimeErrorException;
use Railt\SDL\Exception\TypeErrorException;
use Railt\SDL\Frontend\Ast\Definition\ArgumentDefinitionNode;
use Railt\SDL\Frontend\Ast\Definition\InputFieldDefinitionNode;
use Railt\SDL\Frontend\Ast\Executable\ArgumentNode;
use Railt\SDL\Frontend\Ast\Value\VariableValueNode;
use Railt\TypeSystem\Value\ValueInterface;

/**
 * Class VariablesVisitor
 */
class VariablesVisitor extends Visitor
{
    /**
     * @var array
     */
    private array $variables;

    /**
     * @var ValueFactory
     */
    private ValueFactory $factory;

    /**
     * VariablesResolver constructor.
     *
     * @param array $variables
     */
    public function __construct(array $variables)
    {
        $this->factory = new ValueFactory();
        $this->variables = $variables;
    }

    /**
     * @param NodeInterface $node
     * @return mixed|ValueInterface|null
     * @throws NotAccessibleException
     * @throws TypeErrorException
     * @throws \RuntimeException
     */
    public function enter(NodeInterface $node)
    {
        switch (true) {
            case $node instanceof InputFieldDefinitionNode:
            case $node instanceof ArgumentDefinitionNode:
                $node->defaultValue = $this->resolve($node->defaultValue);
                break;

            case $node instanceof ArgumentNode:
                $node->value = $this->resolve($node->value);
                break;
        }

        return null;
    }

    /**
     * @param ValueInterface|null $value
     * @return ValueInterface|null
     * @throws NotAccessibleException
     * @throws TypeErrorException
     * @throws \RuntimeException
     */
    private function resolve(?ValueInterface $value): ?ValueInterface
    {
        if ($value instanceof VariableValueNode) {
            return $this->fetch($value);
        }

        return $value;
    }

    /**
     * @param VariableValueNode $var
     * @return ValueInterface
     * @throws NotAccessibleException
     * @throws TypeErrorException
     * @throws \RuntimeException
     */
    private function fetch(VariableValueNode $var): ValueInterface
    {
        $this->assertExists($var);

        $value = $this->variables[$var->getName()];

        if ($value instanceof VariableValueNode) {
            return $this->fetch($value);
        }

        return $this->factory->make($value, $var);
    }

    /**
     * @param VariableValueNode $var
     * @return void
     * @throws NotAccessibleException
     * @throws RuntimeErrorException
     * @throws \RuntimeException
     */
    private function assertExists(VariableValueNode $var): void
    {
        if (! isset($this->variables[$var->getName()])) {
            $error = \sprintf('Variable $%s not defined', $var->getName());

            throw new RuntimeErrorException($error, $var);
        }
    }
}
