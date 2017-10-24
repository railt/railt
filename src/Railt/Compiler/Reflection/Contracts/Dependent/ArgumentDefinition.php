<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Contracts\Dependent;

use Railt\Compiler\Reflection\Contracts\Behavior\AllowsTypeIndication;
use Railt\Compiler\Reflection\Contracts\Behavior\Inputable;
use Railt\Compiler\Reflection\Contracts\Definitions\Definition;
use Railt\Compiler\Reflection\Contracts\Definitions\DirectiveDefinition;
use Railt\Compiler\Reflection\Contracts\Definitions\InputDefinition;
use Railt\Compiler\Reflection\Contracts\Dependent\Argument\HasArguments;
use Railt\Compiler\Reflection\Contracts\Invocations\Directive\HasDirectives;

/**
 * Interface ArgumentDefinition
 */
interface ArgumentDefinition extends DependentDefinition, AllowsTypeIndication, HasDirectives
{
    /**
     * @return HasArguments|InputDefinition|DirectiveDefinition|FieldDefinition
     */
    public function getParent(): HasArguments;

    /**
     * @return Inputable|Definition
     */
    public function getType(): Inputable;

    /**
     * @return mixed
     */
    public function getDefaultValue();

    /**
     * @return bool
     */
    public function hasDefaultValue(): bool;
}
