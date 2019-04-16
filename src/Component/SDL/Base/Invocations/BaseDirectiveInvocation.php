<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\SDL\Base\Invocations;

use Railt\Component\SDL\Base\Dependent\BaseDependent;
use Railt\Component\SDL\Base\Invocations\Argument\HasPassedArguments;
use Railt\Component\SDL\Contracts\Invocations\DirectiveInvocation;
use Railt\Component\SDL\Contracts\Type;

/**
 * Class BaseDirectiveInvocation
 */
abstract class BaseDirectiveInvocation extends BaseDependent implements DirectiveInvocation
{
    use HasPassedArguments;

    /**
     * Directive type name
     */
    protected const TYPE_NAME = Type::DIRECTIVE_INVOCATION;
}
