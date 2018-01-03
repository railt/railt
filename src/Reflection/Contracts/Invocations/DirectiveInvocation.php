<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Contracts\Invocations;

use Railt\Reflection\Contracts\Dependent\DependentDefinition;
use Railt\Reflection\Contracts\Invocations\Argument\HasPassedArguments;

/**
 * Interface DirectiveInvocation
 */
interface DirectiveInvocation extends HasPassedArguments, DependentDefinition, Invocable
{
}
