<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Builder;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Reflection\Base\BaseObject;
use Railt\Reflection\Builder\Inheritance\TypeInheritance;
use Railt\Reflection\Builder\Support\Builder;
use Railt\Reflection\Builder\Support\Compilable;
use Railt\Reflection\Builder\Support\FieldsBuilder;
use Railt\Reflection\Contracts\Types\FieldType;
use Railt\Reflection\Contracts\Types\InterfaceType;
use Railt\Reflection\Contracts\Types\ObjectType;
use Railt\Reflection\Exceptions\TypeConflictException;

/**
 * Class ObjectBuilder
 */
class ObjectBuilder extends BaseObject implements Compilable
{
    use Builder {
        compileIfNotCompiled as precompile;
    }

    use FieldsBuilder;

    /**
     * @var TypeInheritance
     */
    protected $inheritance;

    /**
     * SchemaBuilder constructor.
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     * @throws \Railt\Reflection\Exceptions\TypeConflictException
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document)
    {
        $this->bootBuilder($ast, $document);
        $this->inheritance = new TypeInheritance();
    }

    /**
     * @param TreeNode $ast
     * @return bool
     * @throws \Railt\Reflection\Exceptions\TypeConflictException
     */
    public function compile(TreeNode $ast): bool
    {
        $interface = null;

        if ($ast->getId() === '#Implements') {
            /** @var TreeNode $child */
            foreach ($ast->getChildren() as $child) {
                $name                    = $child->getChild(0)->getValueValue();
                $this->interfaces[$name] = $this->getCompiler()->get($name);
            }

            return true;
        }

        return false;
    }

    /**
     * @return bool
     * @throws TypeConflictException
     */
    public function compileIfNotCompiled(): bool
    {
        if (($result = $this->precompile()) === true) {
            foreach ($this->interfaces as $interface) {
                $this->checkInterface($interface);
            }
        }

        return $result;
    }

    /**
     * @param InterfaceType $interface
     * @return void
     * @throws \Railt\Reflection\Exceptions\TypeConflictException
     */
    private function checkInterface(InterfaceType $interface): void
    {
        $this->checkFields($this, $interface);
    }

    /**
     * @param ObjectType $object
     * @param InterfaceType $interface
     * @return void
     * @throws TypeConflictException
     */
    private function checkFields(ObjectType $object, InterfaceType $interface): void
    {
        foreach ($interface->getFields() as $field) {
            if (! $object->hasField($field->getName())) {
                $error = 'Interface "%s" contains field "%s" and must therefore be declared in the remaining type "%s"';
                $error = \sprintf($error, $interface->getName(), $field->getName(), $object->getName());

                throw new TypeConflictException($error);
            }

            $objectField = $object->getField($field->getName());

            $this->inheritance->checkType($objectField, $field);

            $this->checkArguments($objectField, $field);
        }
    }

    /**
     * @param FieldType $object
     * @param FieldType $interface
     * @return void
     * @throws TypeConflictException
     */
    private function checkArguments(FieldType $object, FieldType $interface): void
    {
        foreach ($interface->getArguments() as $argument) {
            if (! $object->hasArgument($argument->getName())) {
                $error = 'Field "%s" of Interface "%s" contains argument "%s" ' .
                    'and must therefore be declared in the remaining type "%s"';

                $interfaceName = $interface->getParent()->getName();
                $remaining     = $this->getName() . '.' . $object->getName();

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
            $this->inheritance->checkType($objectArgument, $argument);
        }
    }
}
