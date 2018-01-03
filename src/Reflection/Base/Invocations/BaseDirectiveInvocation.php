<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Base\Invocations;

use Railt\Reflection\Base\Dependent\BaseDependent;
use Railt\Reflection\Base\Invocations\Argument\HasPassedArguments;
use Railt\Reflection\Contracts\Invocations\DirectiveInvocation;

/**
 * Class BaseDirectiveInvocation
 */
abstract class BaseDirectiveInvocation extends BaseDependent implements DirectiveInvocation
{
    use HasPassedArguments;

    /**
     * Directive type name
     */
    protected const TYPE_NAME = 'Directive';
}
