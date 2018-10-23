<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Reflection\Coercion;

use Railt\SDL\Base\Dependent\BaseArgument;
use Railt\SDL\Contracts\Definitions\TypeDefinition;
use Railt\SDL\Contracts\Dependent\ArgumentDefinition;

/**
 * Class ArgumentCoercion
 */
class ArgumentCoercion extends BaseTypeCoercion
{
    /**
     * @param TypeDefinition $type
     * @return bool
     */
    public function match(TypeDefinition $type): bool
    {
        return $type instanceof ArgumentDefinition;
    }

    /**
     * @param TypeDefinition|ArgumentDefinition $type
     */
    public function apply(TypeDefinition $type): void
    {
        if ($type->hasDefaultValue()) {
            $this->normalizeDefinedValue($type);
            return;
        }

        if (! $type->hasDefaultValue()) {
            $this->inferenceValue($type);
            return;
        }
    }

    /**
     * @param ArgumentDefinition|BaseArgument $argument
     */
    private function normalizeDefinedValue(ArgumentDefinition $argument): void
    {
        $value = $argument->getDefaultValue();

        /**
         * The value can be automatically adjusted without loss of meaning:
         *
         * <code>
         *      # Will transforms to List:
         *      field(arg: [Int]    = 23)      → field(arg: [Int]    = [23])
         *      field(arg: [String] = "s")     → field(arg: [String] = ["s"])
         *      field(arg: [ID]     = "id")    → field(arg: [ID]     = ["id"])
         *      field(arg: [Float]  = 4.2)     → field(arg: [Float]  = [4.2])
         *      field(arg: [Input]  = {a: 23}) → field(arg: [Input]  = [{a: 23}])
         *      # etc...
         *
         *      # Excluding "NULL":
         *      field(arg: [Float]  = null)    → field(arg: [Float]  = null)
         * </code>
         */
        $isListDefinedByNonList = ($value !== null && ! \is_array($value));

        /**
         * The allowable conversion method for NULL:
         * <code>
         *      # As it is while Nullable type initialized by NULL:
         *      field(arg: [ID] = null)      →   field(arg: [ID] = null)
         *
         *      # But apply coercion while NonNull type initialized by NULL:
         *      field(arg: [ID]! = null)     →   field(arg: [ID]! = [null])
         * </code>
         */
        $isNonNullListDefinedByNull = ($argument->isNonNull() && $value === null);

        if (($isListDefinedByNonList || $isNonNullListDefinedByNull) && $argument->isList()) {
            /**
             * Warn: Do not change to `(array)$value` since leads
             * to the destructuring of some iterators (like instance of InputInvocation::class).
             */
            $this->set($argument, [$value]);
        }
    }

    /**
     * @param ArgumentDefinition|BaseArgument $argument
     */
    private function inferenceValue(ArgumentDefinition $argument): void
    {
        /**
         * Any code initialization like:
         * <code>
         *      field(argument: [Type]): Type
         * </code>
         *
         * Will transform to:
         * <code>
         *      field(argument: [Type] = []): Type
         * </code>
         */
        if ($argument->isList()) {
            $this->set($argument, []);
            return;
        }

        /**
         * Any code initialization like:
         * <code>
         *      field(argument: Type): Type
         * </code>
         *
         * Will transform to:
         * <code>
         *      field(argument: Type = NULL): Type
         * </code>
         */
        if (! $argument->isNonNull()) {
            $this->set($argument, null);
            return;
        }
    }

    /**
     * @param BaseArgument $argument
     * @param $value
     */
    private function set(BaseArgument $argument, $value): void
    {
        $invocation = function ($value): void {
            /** @var BaseArgument $this */
            $this->defaultValue    = $value;
            $this->hasDefaultValue = true;
        };

        $invocation->call($argument, $value);
    }
}
