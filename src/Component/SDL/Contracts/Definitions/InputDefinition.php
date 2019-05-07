<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\SDL\Contracts\Definitions;

use Railt\Component\SDL\Contracts\Behavior\Inputable;
use Railt\Component\SDL\Contracts\Dependent\Argument\HasArguments;
use Railt\Component\SDL\Contracts\Invocations\Directive\HasDirectives;

/**
 * Interface InputDefinition
 */
interface InputDefinition extends TypeDefinition, HasArguments, HasDirectives, Inputable
{
}
