<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Contracts\Types;

use Railt\Reflection\Contracts\Behavior\InvokableType;
use Railt\Reflection\Contracts\Containers\HasDirectives;

/**
 * Interface ArgumentType
 */
interface ArgumentType extends HasDirectives, NamedTypeInterface
{
    /**
     * @return mixed
     */
    public function getDefaultValue();

    /**
     * @return bool
     */
    public function hasDefaultValue(): bool;

    /**
     * @return InvokableType
     */
    public function getRelatedType(): InvokableType;

    /**
     * @return int
     */
    public function getPosition(): int;
}
