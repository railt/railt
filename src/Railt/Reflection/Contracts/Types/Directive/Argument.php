<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Contracts\Types\Directive;

use Railt\Reflection\Contracts\Behavior\Child;
use Railt\Reflection\Contracts\Behavior\Invokable;
use Railt\Reflection\Contracts\Types\ArgumentType;
use Railt\Reflection\Contracts\Types\NamedTypeInterface;

/**
 * Interface Argument
 */
interface Argument extends Invokable, NamedTypeInterface, Child
{
    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @return ArgumentType
     */
    public function getArgument(): ArgumentType;
}
