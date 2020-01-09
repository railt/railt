<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Introspection\Builder;

use GraphQL\Contracts\TypeSystem\ArgumentInterface;
use GraphQL\Contracts\TypeSystem\DirectiveInterface;
use GraphQL\Contracts\TypeSystem\EnumValueInterface;
use GraphQL\Contracts\TypeSystem\FieldInterface;
use GraphQL\Contracts\TypeSystem\InputFieldInterface;
use GraphQL\Contracts\TypeSystem\Type\NamedTypeInterface;
use GraphQL\Contracts\TypeSystem\Type\TypeInterface;

/**
 * Interface RegistryInterface
 */
interface RegistryInterface
{
    /**
     * @param string $type
     * @return NamedTypeInterface
     */
    public function get(string $type): NamedTypeInterface;

    /**
     * @param array $data
     * @return TypeInterface
     */
    public function type(array $data): TypeInterface;

    /**
     * @param array $argument
     * @return ArgumentInterface
     */
    public function argument(array $argument): ArgumentInterface;

    /**
     * @param array $field
     * @return FieldInterface
     */
    public function field(array $field): FieldInterface;

    /**
     * @param array $field
     * @return InputFieldInterface
     */
    public function inputField(array $field): InputFieldInterface;

    /**
     * @param array $value
     * @return EnumValueInterface
     */
    public function enumValue(array $value): EnumValueInterface;

    /**
     * @param array $directive
     * @return DirectiveInterface
     */
    public function directive(array $directive): DirectiveInterface;
}
