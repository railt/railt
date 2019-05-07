<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Contracts\Dependent;

use Railt\SDL\Contracts\Behavior\AllowsTypeIndication;
use Railt\SDL\Contracts\Definitions\TypeDefinition;
use Railt\SDL\Contracts\Dependent\Argument\HasArguments;
use Railt\SDL\Contracts\Invocations\Directive\HasDirectives;

/**
 * Interface ArgumentDefinition
 */
interface ArgumentDefinition extends DependentDefinition, AllowsTypeIndication, HasDirectives
{
    /**
     * @return HasArguments|TypeDefinition
     */
    public function getParent(): TypeDefinition;

    /**
     * @return mixed
     */
    public function getDefaultValue();

    /**
     * @return bool
     */
    public function hasDefaultValue(): bool;
}
