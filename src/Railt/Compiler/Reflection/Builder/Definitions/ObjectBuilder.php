<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Builder\Definitions;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Compiler\Reflection\Base\Definitions\BaseObject;
use Railt\Compiler\Reflection\Builder\Dependent\Field\FieldsBuilder;
use Railt\Compiler\Reflection\Builder\DocumentBuilder;
use Railt\Compiler\Reflection\Builder\Inheritance\TypeInheritance;
use Railt\Compiler\Reflection\Builder\Invocations\Directive\DirectivesBuilder;
use Railt\Compiler\Reflection\Builder\Process\Compilable;
use Railt\Compiler\Reflection\Builder\Process\Compiler;
use Railt\Compiler\Reflection\Contracts\Definitions\InterfaceDefinition;
use Railt\Compiler\Reflection\Contracts\Definitions\ObjectDefinition;
use Railt\Compiler\Reflection\Contracts\Dependent\FieldDefinition;
use Railt\Compiler\Exceptions\TypeConflictException;

/**
 * Class ObjectBuilder
 */
class ObjectBuilder extends BaseObject implements Compilable
{
    use Compiler;
    use FieldsBuilder;
    use DirectivesBuilder;

    /**
     * @var TypeInheritance
     */
    protected $inheritance;

    /**
     * SchemaBuilder constructor.
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     * @throws \Railt\Compiler\Exceptions\TypeConflictException
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document)
    {
        $this->bootBuilder($ast, $document);

        $this->inheritance = new TypeInheritance();
    }

    /**
     * @param TreeNode $ast
     * @return bool
     * @throws \Railt\Compiler\Exceptions\TypeConflictException
     */
    public function compile(TreeNode $ast): bool
    {
        if ($ast->getId() === '#Implements') {
            /** @var TreeNode $child */
            foreach ($ast->getChildren() as $child) {
                $name = $child->getChild(0)->getValueValue();

                $interface = $this->getCompiler()->get($name);

                $this->interfaces = $this->verifyDefinition($this->interfaces, $interface);
            }

            return true;
        }

        return false;
    }

    /**
     * @return void
     * @throws \Railt\Compiler\Exceptions\TypeConflictException
     */
    public function verify(): void
    {
        foreach ($this->interfaces as $interface) {
            $this->checkInterface($interface);
        }
    }

    /**
     * @param InterfaceDefinition $interface
     * @return void
     * @throws \Railt\Compiler\Exceptions\TypeConflictException
     */
    private function checkInterface(InterfaceDefinition $interface): void
    {
        $this->checkFields($this, $interface);
    }

    /**
     * @param ObjectDefinition $object
     * @param InterfaceDefinition $interface
     * @return void
     * @throws TypeConflictException
     */
    private function checkFields(ObjectDefinition $object, InterfaceDefinition $interface): void
    {
        foreach ($interface->getFields() as $field) {
            if (! $object->hasField($field->getName())) {
                $error = 'Interface "%s" contains field "%s" and must therefore be declared in the remaining type "%s"';
                $error = \sprintf($error, $interface->getName(), $field->getName(), $object->getName());

                throw new TypeConflictException($error);
            }

            $objectField = $object->getField($field->getName());

            $this->inheritance->verify($objectField, $field);

            $this->checkArguments($objectField, $field);
        }
    }

    /**
     * @param FieldDefinition $object
     * @param FieldDefinition $interface
     * @return void
     * @throws TypeConflictException
     */
    private function checkArguments(FieldDefinition $object, FieldDefinition $interface): void
    {
        foreach ($interface->getArguments() as $argument) {
            if (! $object->hasArgument($argument->getName())) {
                $error = 'Field "%s" of Interface "%s" contains argument "%s" ' .
                    'and must therefore be declared in the remaining type "%s"';

                $interfaceName = $interface->getParent()->getName();
                $remaining = $this->getName() . '.' . $object->getName();

                $error = \sprintf(
                    $error,
                    $object->getName(),
                    $interfaceName,
                    $argument->getName(),
                    $remaining
                );

                throw new TypeConflictException($error);
            }

            $objectArgument = $object->getArgument($argument->getName());
            $this->inheritance->verify($objectArgument, $argument);
        }
    }
}
