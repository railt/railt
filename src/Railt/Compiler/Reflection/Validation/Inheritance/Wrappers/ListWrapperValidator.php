<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Validation\Inheritance\Wrappers;

use Railt\Compiler\Exceptions\TypeRedefinitionException;
use Railt\Compiler\Reflection\Contracts\Behavior\AllowsTypeIndication as Wrapping;
use Railt\Compiler\Reflection\Contracts\Dependent\DependentDefinition;
use Railt\Compiler\Reflection\Validation\Base\BaseValidator;

/**
 * Class ListContainerValidator
 */
class ListWrapperValidator extends BaseValidator implements WrapperValidator
{
    /**
     * @param Wrapping $type
     * @return bool
     */
    public function match($type): bool
    {
        return $type->isList() && $type instanceof DependentDefinition;
    }

    /**
     * @param Wrapping|DependentDefinition $type
     * @param Wrapping|DependentDefinition $overridenBy
     * @param bool $direct
     * @return void
     * @throws TypeRedefinitionException
     */
    public function validate(Wrapping $type, Wrapping $overridenBy, bool $direct = true): void
    {
        $this->validateOverridenByList($type, $overridenBy, $direct);
        $this->validateOverridenByListOFNulls($type, $overridenBy, $direct);
        $this->validateOverridenByNullableList($type, $overridenBy, $direct);
    }

    /**
     * @param Wrapping|DependentDefinition $a
     * @param Wrapping|DependentDefinition $b
     * @param bool $direct
     * @return void
     * @throws TypeRedefinitionException
     */
    private function validateOverridenByList(Wrapping $a, Wrapping $b, bool $direct = true): void
    {
        if (! $b->isList()) {
            $error = \sprintf('List %s of %s can not be overridden by non-list %s',
                $this->typeToString($a),
                $this->typeToString($a->getParent()),
                $this->typeToString($b)
            );

            throw new TypeRedefinitionException($error, $this->getCallStack());
        }
    }

    /**
     * @param Wrapping|DependentDefinition $a
     * @param Wrapping|DependentDefinition $b
     * @param bool $direct
     * @return void
     * @throws TypeRedefinitionException
     */
    private function validateOverridenByNullableList(Wrapping $a, Wrapping $b, bool $direct = true): void
    {
        if ($a->isNonNull() && ! $b->isNonNull()) {
            $error = \sprintf('List %s of %s can not be overridden by nullable list %s',
                $this->typeToString($a),
                $this->typeToString($a->getParent()),
                $this->typeToString($b)
            );

            throw new TypeRedefinitionException($error, $this->getCallStack());
        }
    }

    /**
     * @param Wrapping|DependentDefinition $a
     * @param Wrapping|DependentDefinition $b
     * @param bool $direct
     * @return void
     * @throws TypeRedefinitionException
     */
    private function validateOverridenByListOFNulls(Wrapping $a, Wrapping $b, bool $direct = true): void
    {
        if ($a->isListOfNonNulls() && ! $b->isListOfNonNulls()) {
            $error = \sprintf('List %s of %s can not be overridden by nullable %s',
                $this->typeToString($a),
                $this->typeToString($a->getParent()),
                $this->typeToString($b)
            );

            throw new TypeRedefinitionException($error, $this->getCallStack());
        }
    }
}
