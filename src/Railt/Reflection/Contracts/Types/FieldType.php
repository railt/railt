<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Contracts\Types;

use Railt\Reflection\Contracts\Behavior\Inputable;
use Railt\Reflection\Contracts\Behavior\InvokableType;
use Railt\Reflection\Contracts\Containers\HasArguments;
use Railt\Reflection\Contracts\Containers\HasDirectives;

/**
 * Interface FieldType
 */
interface FieldType extends HasDirectives, HasArguments, NamedTypeInterface
{
    /**
     * @return Inputable|InputType|EnumType|ScalarType
     */
    public function getType(): Inputable;

    /**
     * @return bool
     */
    public function isList(): bool;

    /**
     * @return bool
     */
    public function isNonNull(): bool;

    /**
     * @return bool
     */
    public function isNonNullList(): bool;
}
